<?php
// test_connection.php - Run this file to test your database connection
// Place this in your root directory (same level as .env file)
// Access it via: http://localhost/runaz-app/test_connection.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.test { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.success { color: #16a34a; }
.error { color: #dc2626; }
.warning { color: #ea580c; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background: #f3f4f6; }
pre { background: #f9fafb; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style></head><body>";

echo "<h1>🔍 Runaz Database Connection Test</h1>";

// Test 1: Check .env file
echo "<div class='test'><h3>Test 1: Environment Configuration</h3>";
if (file_exists('.env')) {
    echo "<span class='success'>✅ .env file found</span><br>";
    
    // Load .env
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $envVars = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value);
            $_ENV[trim($key)] = trim($value);
        }
    }
    
    $appEnv = $_ENV['APP_ENV'] ?? 'development';
    echo "<strong>Environment:</strong> " . htmlspecialchars($appEnv) . "<br>";
    
    if ($appEnv === 'development') {
        echo "<strong>DB Host:</strong> " . htmlspecialchars($_ENV['DB_HOST_DEV'] ?? 'not set') . "<br>";
        echo "<strong>DB Name:</strong> " . htmlspecialchars($_ENV['DB_NAME_DEV'] ?? 'not set') . "<br>";
        echo "<strong>DB User:</strong> " . htmlspecialchars($_ENV['DB_USER_DEV'] ?? 'not set') . "<br>";
        echo "<strong>DB Pass:</strong> " . (empty($_ENV['DB_PASS_DEV']) ? '(empty)' : '******') . "<br>";
    }
} else {
    echo "<span class='error'>❌ .env file NOT found</span><br>";
    echo "Please create a .env file in your root directory<br>";
    exit;
}
echo "</div>";

// Test 2: Check database config file
echo "<div class='test'><h3>Test 2: Database Configuration File</h3>";
if (file_exists('api/config/database.php')) {
    echo "<span class='success'>✅ database.php file found</span><br>";
    try {
        include 'api/config/database.php';
        echo "<span class='success'>✅ database.php loaded successfully</span><br>";
    } catch (Exception $e) {
        echo "<span class='error'>❌ Error loading database.php: " . $e->getMessage() . "</span><br>";
        exit;
    }
} else {
    echo "<span class='error'>❌ database.php file NOT found at api/config/database.php</span><br>";
    echo "Please create this file using the provided template<br>";
    exit;
}
echo "</div>";

// Test 3: Check connection
echo "<div class='test'><h3>Test 3: Database Connection</h3>";
if (isset($conn) && $conn instanceof mysqli) {
    if ($conn->connect_error) {
        echo "<span class='error'>❌ Connection failed: " . $conn->connect_error . "</span><br>";
        echo "<strong>Host:</strong> " . DB_HOST . "<br>";
        echo "<strong>Database:</strong> " . DB_NAME . "<br>";
        echo "<strong>User:</strong> " . DB_USER . "<br>";
        exit;
    } else {
        echo "<span class='success'>✅ Database connected successfully</span><br>";
        echo "<strong>Server info:</strong> " . $conn->server_info . "<br>";
        echo "<strong>Database:</strong> " . DB_NAME . "<br>";
        echo "<strong>Character set:</strong> " . $conn->character_set_name() . "<br>";
    }
} else {
    echo "<span class='error'>❌ Database connection object not found</span><br>";
    exit;
}
echo "</div>";

// Test 4: Check tables
echo "<div class='test'><h3>Test 4: Check Required Tables</h3>";

$requiredTables = ['service_categories', 'user_service_categories'];
$missingTables = [];

foreach ($requiredTables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<span class='success'>✅ $table table exists</span><br>";
    } else {
        echo "<span class='error'>❌ $table table does NOT exist</span><br>";
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "<br><span class='warning'>⚠️ Please run the SQL script to create missing tables</span><br>";
}
echo "</div>";

// Test 5: Check service categories data
if (empty($missingTables)) {
    echo "<div class='test'><h3>Test 5: Service Categories Data</h3>";
    
    $sql = "SELECT id, category_name, category_slug, is_active, display_order 
            FROM service_categories 
            ORDER BY display_order ASC, category_name ASC";
            
    $result = $conn->query($sql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            echo "<span class='success'>✅ Found " . $result->num_rows . " service categories</span><br><br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Category Name</th><th>Slug</th><th>Active</th><th>Order</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                $active = $row['is_active'] ? '<span class="success">Yes</span>' : '<span class="error">No</span>';
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['category_slug']) . "</td>";
                echo "<td>" . $active . "</td>";
                echo "<td>" . $row['display_order'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<span class='warning'>⚠️ service_categories table is empty</span><br>";
            echo "Please run the INSERT statements from the SQL script<br>";
        }
    } else {
        echo "<span class='error'>❌ Error querying service_categories: " . $conn->error . "</span><br>";
    }
    echo "</div>";
}

// Test 6: Test fetch_categories.php
echo "<div class='test'><h3>Test 6: Test fetch_categories.php</h3>";
if (file_exists('api/fetch_categories.php')) {
    echo "<span class='success'>✅ fetch_categories.php file found</span><br>";
    
    $serviceCategories = [];
    ob_start();
    include 'api/fetch_categories.php';
    $output = ob_get_clean();
    
    if (!empty($serviceCategories)) {
        echo "<span class='success'>✅ Successfully loaded " . count($serviceCategories) . " categories</span><br><br>";
        echo "<strong>Categories array:</strong>";
        echo "<pre>" . print_r($serviceCategories, true) . "</pre>";
    } else {
        echo "<span class='error'>❌ No categories loaded from fetch_categories.php</span><br>";
        if (!empty($output)) {
            echo "<strong>Output:</strong><pre>" . htmlspecialchars($output) . "</pre>";
        }
    }
} else {
    echo "<span class='error'>❌ fetch_categories.php file NOT found</span><br>";
    echo "Expected location: api/fetch_categories.php<br>";
}
echo "</div>";

// Summary
echo "<div class='test'><h3>✨ Summary</h3>";
$allGood = empty($missingTables) && isset($serviceCategories) && !empty($serviceCategories);

if ($allGood) {
    echo "<span class='success' style='font-size: 1.2em;'>🎉 All tests passed! Your registration page should work correctly.</span><br><br>";
    echo "<strong>Next steps:</strong><br>";
    echo "1. Delete this test file for security<br>";
    echo "2. Test your registration page<br>";
    echo "3. Verify categories appear correctly<br>";
} else {
    echo "<span class='error' style='font-size: 1.2em;'>⚠️ Some tests failed. Please fix the issues above.</span><br><br>";
    echo "<strong>Common fixes:</strong><br>";
    echo "1. Run the SQL script to create tables<br>";
    echo "2. Check your .env database credentials<br>";
    echo "3. Ensure MySQL service is running<br>";
}

echo "<br><br><strong style='color: #dc2626;'>⚠️ IMPORTANT: Delete this test file after verification!</strong>";
echo "</div>";

$conn->close();

echo "</body></html>";
?>