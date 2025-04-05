<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input from the login form
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if both email and password are empty
    if (empty($email) && empty($password)) {
        echo "<script>alert('Both email and password are invalid! Please provide them.'); window.location = 'signup1.php';</script>";
        exit();
    }

    // Prepare SQL query to fetch user by email using mysqli
    $stmt = $conn->prepare("SELECT Customer_Id, Name, Password FROM Customer WHERE Email = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Fetch result as an associative array
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['Password'])) {
            // Store session variables
            $_SESSION['user_id'] = $user['Customer_Id'];
            $_SESSION['user_name'] = $user['Name'];
            $_SESSION['user_type'] = 'customer';

           
            echo "<script>alert('Login successful'); window.location = 'home.php';</script>";
            exit();
        } else {
         
            echo "<script>alert('Invalid email or password!'); window.location = 'signup1.php';</script>";
            exit();
        }
    } else {
     
        echo "<script>alert('Invalid email or password!'); window.location = 'signup1.php';</script>";
        exit();
    }

    $stmt->close();
}
?>