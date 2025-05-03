<?php
$host = "localhost";
$dbname = "music_db";
$username = "root";
$password = "12345678";

try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle any connection errors
    echo ("Connection failed: " . $e->getMessage());
}
?>
