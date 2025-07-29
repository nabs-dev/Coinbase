<?php
session_start();
$host = "localhost";
$user = "u8gr0sjr9p4p4";
$password = "9yxuqyo3mt85";
$dbname = "dbaui6a02xiifv";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
