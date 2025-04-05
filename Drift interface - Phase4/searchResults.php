<?php
// searchResults.php

session_start();
include 'connection.php';  // or connection.php, so we can query the DB

// Grab the search query from the URL
$q = isset($_GET['q']) ? $_GET['q'] : '';

// Basic query example for a `cars` table
$sql = "SELECT * FROM car WHERE Model LIKE ? OR Brand LIKE ?";
$stmt = $conn->prepare($sql);

// We do wildcards around $q
$likeQ = "%{$q}%";
$stmt->bind_param('ss', $likeQ, $likeQ);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <!-- your CSS, etc. -->
</head>
<body>
<h1>Search Results for "<?php echo htmlspecialchars($q); ?>"</h1>

<?php if ($result && $result->num_rows > 0): ?>
    <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <?php echo htmlspecialchars($row['Brand']); ?>
            <?php echo htmlspecialchars($row['Model']); ?>
            - $<?php echo number_format($row['Price']); ?>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>No matching cars found.</p>
<?php endif; ?>
<script src="home_js/main.js"></script>
</body>
</html>
