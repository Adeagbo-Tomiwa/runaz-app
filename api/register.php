<?php
// /api/register.php
header('Content-Type: application/json; charset=utf-8');
// Adjust these settings to your environment
$db_dsn = "mysql:host=localhost;dbname=runaz_app;charset=utf8mb4";
$db_user = "localhost";
$db_pass = "";

// Configure file upload
$UPLOAD_BASE = __DIR__ . '/../uploads/kyc'; // store uploads outside webroot if possible
$MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB per file
$ALLOWED_MIMES = [
    'image/jpeg' => '.jpg',
    'image/png'  => '.png',
    'image/webp' => '.webp'
];

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'error'=>'Database connection failed.']);
    exit;
}

function json_err($msg, $code = 400){
    http_response_code($code);
    echo json_encode(['success'=>false,'error'=>$msg]);
    exit;
}

// We expect a POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_err('Invalid request method', 405);
}

// Basic required fields (front-end also validates)
$required = ['role','email','phone','password','password_confirm','first_name','last_name','dob','gender','address','city','state','lga','id_type','id_number'];
foreach ($required as $r) {
    if (empty($_POST[$r])) {
        json_err("Missing required field: $r");
    }
}

// Password match
if ($_POST['password'] !== $_POST['password_confirm']) json_err('Passwords do not match.');

// Sanitize / normalize
$role = in_array($_POST['role'], ['requester','runner']) ? $_POST['role'] : 'requester';
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
if (!$email) json_err('Invalid email address.');
$phone = trim($_POST['phone']);
$password = $_POST['password'];

// check unique email/phone
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR phone = :phone LIMIT 1");
    $stmt->execute([':email'=>$email, ':phone'=>$phone]);
    if ($stmt->fetch()) json_err('Email or phone already registered.');
} catch (Exception $e) {
    json_err('Database error while checking existing users.');
}

// Validate files exist
$expect_files = ['selfie','id_front','id_back'];
foreach ($expect_files as $f) {
    if (!isset($_FILES[$f]) || !is_uploaded_file($_FILES[$f]['tmp_name'])) {
        json_err("Missing uploaded file: $f");
    }
}

// Create upload dir for this submission
$now = new DateTime('now', new DateTimeZone('UTC'));
$folder = $UPLOAD_BASE . '/' . $now->format('Ymd-His') . '-' . bin2hex(random_bytes(6));
if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
    json_err('Failed to create upload directory', 500);
}

function handle_file($key, $folder, $ALLOWED_MIMES, $MAX_FILE_SIZE) {
    if (!isset($_FILES[$key])) return null;
    $f = $_FILES[$key];
    if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException("Upload error for $key");
    if ($f['size'] > $MAX_FILE_SIZE) throw new RuntimeException("$key is too large");
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $type = $finfo->file($f['tmp_name']);
    if (!isset($ALLOWED_MIMES[$type])) throw new RuntimeException("Invalid file type for $key");
    $ext = $ALLOWED_MIMES[$type];
    $safe = bin2hex(random_bytes(12)) . $ext;
    $dest = $folder . '/' . $safe;
    if (!move_uploaded_file($f['tmp_name'], $dest)) throw new RuntimeException("Failed to move uploaded file for $key");
    // Optionally set restrictive permissions
    @chmod($dest, 0644);
    return $dest;
}

try {
    $pdo->beginTransaction();

    // process files
    $selfie_path = handle_file('selfie', $folder, $ALLOWED_MIMES, $MAX_FILE_SIZE);
    $front_path  = handle_file('id_front', $folder, $ALLOWED_MIMES, $MAX_FILE_SIZE);
    $back_path   = handle_file('id_back', $folder, $ALLOWED_MIMES, $MAX_FILE_SIZE);

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $ins = $pdo->prepare("INSERT INTO users
      (role,email,phone,password_hash,first_name,last_name,dob,gender,address,city,state,lga,referral,id_type,id_number,nin,selfie_path,id_front_path,id_back_path,status)
      VALUES
      (:role,:email,:phone,:password_hash,:first_name,:last_name,:dob,:gender,:address,:city,:state,:lga,:referral,:id_type,:id_number,:nin,:selfie_path,:id_front_path,:id_back_path,'pending')
    ");

    $ins->execute([
      ':role'=>$role,
      ':email'=>$email,
      ':phone'=>$phone,
      ':password_hash'=>$password_hash,
      ':first_name'=>trim($_POST['first_name']),
      ':last_name'=>trim($_POST['last_name']),
      ':dob'=>$_POST['dob'],
      ':gender'=>$_POST['gender'],
      ':address'=>trim($_POST['address']),
      ':city'=>trim($_POST['city']),
      ':state'=>trim($_POST['state']),
      ':lga'=>trim($_POST['lga']),
      ':referral'=>isset($_POST['referral'])?trim($_POST['referral']):null,
      ':id_type'=>trim($_POST['id_type']),
      ':id_number'=>trim($_POST['id_number']),
      ':nin'=>isset($_POST['nin'])?trim($_POST['nin']):null,
      ':selfie_path'=>$selfie_path,
      ':id_front_path'=>$front_path,
      ':id_back_path'=>$back_path
    ]);

    $userId = (int)$pdo->lastInsertId();

    // If runner, save runner profile
    if ($role === 'runner') {
        // categories may come as multiple inputs; convert to JSON
        $categories = null;
        if (isset($_POST['categories'])) {
            // If categories is multiple values, it will be an array
            if (is_array($_POST['categories'])) $categories = json_encode($_POST['categories']);
            else $categories = json_encode([$_POST['categories']]);
        }
        $rp = $pdo->prepare("INSERT INTO runner_profiles (user_id,categories,skills,rate,experience,bio,availability)
                             VALUES (:user_id,:categories,:skills,:rate,:experience,:bio,:availability)");
        $rp->execute([
            ':user_id'=>$userId,
            ':categories'=>$categories,
            ':skills'=>isset($_POST['skills'])?trim($_POST['skills']):null,
            ':rate'=>isset($_POST['rate']) && $_POST['rate']!=='' ? (float)$_POST['rate'] : null,
            ':experience'=>isset($_POST['experience']) && $_POST['experience']!=='' ? (int)$_POST['experience'] : null,
            ':bio'=>isset($_POST['bio'])?trim($_POST['bio']):null,
            ':availability'=>isset($_POST['availability'])?trim($_POST['availability']):null
        ]);
    }

    $pdo->commit();

    // Success: you may want to create a session or send verification SMS/email here.
    echo json_encode(['success'=>true, 'message'=>'Registration successful','redirect'=> $role === 'runner' ? '/runners/dashboard.html' : '/requesters/dashboard.html']);

} catch (RuntimeException $re) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // remove created files/folder where appropriate
    // (brief cleanup: attempt to remove files)
    array_map(function($p){ if($p && file_exists($p)) @unlink($p); }, [$selfie_path ?? null, $front_path ?? null, $back_path ?? null]);
    @rmdir($folder);
    json_err($re->getMessage(), 400);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // Cleanup
    array_map(function($p){ if($p && file_exists($p)) @unlink($p); }, [$selfie_path ?? null, $front_path ?? null, $back_path ?? null]);
    @rmdir($folder);
    json_err('Server error. Please try again later.', 500);
}
