<?php
// api/register.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Include database config
require_once __DIR__ . '/config/database.php';

try {
    // Check database connection
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception('Database connection failed');
    }

    // Get form data
    $data = $_POST;
    $files = $_FILES;

    // Validate required fields
    $required = ['email', 'phone', 'password', 'role', 'first_name', 'last_name', 
                 'dob', 'gender', 'address', 'city', 'state', 'lga', 
                 'id_type', 'id_number'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: " . ucfirst(str_replace('_', ' ', $field)));
        }
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Validate age (must be 18+)
    $dob = new DateTime($data['dob']);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    if ($age < 18) {
        throw new Exception('You must be at least 18 years old to register');
    }

    // Check if email or phone already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $data['email'], $data['phone']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        throw new Exception('Email or phone number already registered');
    }
    $stmt->close();

    // Validate file uploads
    $requiredFiles = ['selfie', 'id_front', 'id_back'];
    foreach ($requiredFiles as $file) {
        if (empty($files[$file]) || $files[$file]['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Missing or invalid file: " . ucfirst(str_replace('_', ' ', $file)));
        }
        
        // Validate file type using finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $files[$file]['tmp_name']);
        finfo_close($finfo);
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception("Invalid file type for $file. Only JPEG and PNG allowed");
        }
        
        // Validate file size (5MB max)
        if ($files[$file]['size'] > 5242880) {
            throw new Exception("File $file exceeds 5MB limit");
        }
    }

    // Validate role-specific fields
    if ($data['role'] === 'runner') {
        if (empty($data['categories']) || !is_array($data['categories'])) {
            throw new Exception('Please select at least one service category');
        }
        
        // Validate that categories exist in database
        $placeholders = str_repeat('?,', count($data['categories']) - 1) . '?';
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM service_categories WHERE id IN ($placeholders) AND is_active = 1");
        $types = str_repeat('i', count($data['categories']));
        $stmt->bind_param($types, ...$data['categories']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['count'] != count($data['categories'])) {
            throw new Exception('Invalid service categories selected');
        }
        $stmt->close();
    }

    // Start transaction
    $conn->begin_transaction();

    // 1. Create user account
    $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $conn->prepare("
        INSERT INTO users (email, phone, password_hash, role, status, created_at) 
        VALUES (?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->bind_param("ssss", $data['email'], $data['phone'], $passwordHash, $data['role']);
    $stmt->execute();
    $userId = $conn->insert_id;
    $stmt->close();

    // 2. Insert profile data
    $stmt = $conn->prepare("
        INSERT INTO user_profiles 
        (user_id, first_name, last_name, dob, gender, address, city, state, lga, alt_phone, referral, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $altPhone = !empty($data['alt_phone']) ? $data['alt_phone'] : null;
    $referral = !empty($data['referral']) ? $data['referral'] : null;
    
    $stmt->bind_param(
        "issssssssss",
        $userId,
        $data['first_name'],
        $data['last_name'],
        $data['dob'],
        $data['gender'],
        $data['address'],
        $data['city'],
        $data['state'],
        $data['lga'],
        $altPhone,
        $referral
    );
    $stmt->execute();
    $stmt->close();

    // 3. Handle file uploads
    $uploadDir = __DIR__ . '/../uploads/kyc/' . $userId . '/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception('Failed to create upload directory');
        }
    }

    $filePaths = [];
    foreach ($requiredFiles as $fileKey) {
        $file = $files[$fileKey];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $fileKey . '_' . time() . '_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to upload $fileKey");
        }
        
        // Store relative path
        $filePaths[$fileKey] = 'uploads/kyc/' . $userId . '/' . $filename;
    }

    // 4. Insert KYC data
    $stmt = $conn->prepare("
        INSERT INTO user_kyc 
        (user_id, id_type, id_number, nin, selfie_path, id_front_path, id_back_path, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $nin = !empty($data['nin']) ? $data['nin'] : null;
    
    $stmt->bind_param(
        "issssss",
        $userId,
        $data['id_type'],
        $data['id_number'],
        $nin,
        $filePaths['selfie'],
        $filePaths['id_front'],
        $filePaths['id_back']
    );
    $stmt->execute();
    $stmt->close();

    // 5. Insert role-specific profile
    if ($data['role'] === 'runner') {
        // Insert runner profile
        $stmt = $conn->prepare("
            INSERT INTO runner_profiles 
            (user_id, skills, hourly_rate, experience_years, bio, availability, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $skills = !empty($data['skills']) ? $data['skills'] : null;
        $rate = !empty($data['rate']) ? floatval($data['rate']) : null;
        $experience = !empty($data['experience']) ? intval($data['experience']) : null;
        $bio = !empty($data['bio']) ? $data['bio'] : null;
        $availability = !empty($data['availability']) ? $data['availability'] : null;
        
        $stmt->bind_param(
            "isdiss",
            $userId,
            $skills,
            $rate,
            $experience,
            $bio,
            $availability
        );
        $stmt->execute();
        $stmt->close();
        
        // Insert service categories into junction table
        $stmt = $conn->prepare("
            INSERT INTO user_service_categories (user_id, category_id) 
            VALUES (?, ?)
        ");
        
        foreach ($data['categories'] as $categoryId) {
            $catId = intval($categoryId);
            $stmt->bind_param("ii", $userId, $catId);
            $stmt->execute();
        }
        $stmt->close();
        
    } else {
        // Insert requester profile
        $stmt = $conn->prepare("
            INSERT INTO requester_profiles 
            (user_id, default_service_address, prefer_verified, budget_preference, notes, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $defaultAddress = !empty($data['default_address']) ? $data['default_address'] : null;
        $preferVerified = !empty($data['prefer_verified']) ? $data['prefer_verified'] : 'yes';
        $budgetPref = !empty($data['budget_pref']) ? $data['budget_pref'] : 'Flexible';
        $notes = !empty($data['notes']) ? $data['notes'] : null;
        
        $stmt->bind_param(
            "issss",
            $userId,
            $defaultAddress,
            $preferVerified,
            $budgetPref,
            $notes
        );
        $stmt->execute();
        $stmt->close();
    }

    // 6. Log registration (optional - create table if needed)
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt = $conn->prepare("
        INSERT INTO registration_logs (user_id, step, ip_address, created_at) 
        VALUES (?, 5, ?, NOW())
    ");
    if ($stmt) {
        $stmt->bind_param("is", $userId, $ipAddress);
        $stmt->execute();
        $stmt->close();
    }

    // Commit transaction
    $conn->commit();

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Your account is pending verification.',
        'user_id' => $userId,
        'redirect' => '../login/'
    ]);

} catch (Exception $e) {
    // Rollback on error
    if (isset($conn) && $conn->connect_error === null) {
        $conn->rollback();
    }
    
    // Clean up uploaded files on error
    if (isset($uploadDir) && is_dir($uploadDir)) {
        $filesInDir = glob("$uploadDir/*");
        if ($filesInDir) {
            array_map('unlink', $filesInDir);
        }
        @rmdir($uploadDir);
    }
    
    // Log error
    error_log("Registration error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>