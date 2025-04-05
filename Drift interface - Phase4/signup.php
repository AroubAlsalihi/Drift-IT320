<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve POST data
    $fullName = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];

    // Validate age (must be 18 or above)
    if ($age < 18) {
        echo "<script>alert('You must be 18 or older to register.'); window.location = 'signup1.php';</script>";
        exit();
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT Customer_Id FROM Customer WHERE Email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $check_email->close();
        echo "<script>alert('Email already exists'); window.location = 'signup1.php';</script>";
        exit();
    }
    $check_email->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into Customer table
    $insert_user = $conn->prepare("INSERT INTO Customer (Email, Name, Password, Age, PhoneNumber) VALUES (?, ?, ?, ?, ?)");
    $insert_user->bind_param("sssis", $email, $fullName, $hashed_password, $age, $phone);

    if ($insert_user->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_type'] = 'customer';
        $insert_user->close();
        echo "<script>alert('Registration successful'); window.location = 'home.php';</script>";
        exit();
    } 
}
?>