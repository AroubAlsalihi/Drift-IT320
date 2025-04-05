<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$conn = mysqli_connect('localhost', 'root', 'root', 'drift', 3306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>