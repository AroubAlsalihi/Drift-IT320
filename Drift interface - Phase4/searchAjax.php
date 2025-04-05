<?php
session_start();
include 'connection.php'; // or connection
header('Content-Type: text/html; charset=UTF-8');

// Get search query
$q = isset($_GET['q']) ? $_GET['q'] : '';

// Prepare statement (search in Model or Brand, for example)
$sql = "SELECT Car_Id, Brand, Model FROM car 
        WHERE Brand LIKE ? OR Model LIKE ?
        ORDER BY Brand, Model
        LIMIT 10"; 
$stmt = $conn->prepare($sql);
$like = "%{$q}%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // Return HTML links
    while($row = $result->fetch_assoc()) {
        $carId = $row['Car_Id'];
        $brand = htmlspecialchars($row['Brand']);
        $model = htmlspecialchars($row['Model']);
        
        // Link to your product page, e.g. product.php?carId=...
        echo "<a href='ProductPage.php?id=$carId'>$brand $model</a>";
    }
} else {
    echo "<span style='padding:8px;display:block;'>No results found.</span>";
}