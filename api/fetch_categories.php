<?php
// fetch_categories.php - Place this in your api folder

// Database connection should already be included before this file
// If you included database.php before this file, $conn should be available

if (!isset($conn)) {
    die("Database configuration missing. Please check your database connection.");
}

$serviceCategories = [];

try {
    $sql = "SELECT id, category_name, category_slug, description 
            FROM service_categories 
            WHERE is_active = 1 
            ORDER BY display_order ASC, category_name ASC";
    
    $result = $conn->query($sql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $serviceCategories[] = $row;
            }
        }
    } else {
        // Log error but don't break the page
        error_log("Error fetching service categories: " . $conn->error);
    }
} catch (Exception $e) {
    error_log("Exception fetching service categories: " . $e->getMessage());
}

// Debug: Uncomment to check if categories are loaded
// echo "<pre>"; print_r($serviceCategories); echo "</pre>";
?>