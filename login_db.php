<?php
session_start();
// Include the database connection code
require 'db.php';

// Retrieve and validate user input
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Please enter a valid email address";
    header('location: register.php');
} else if (strlen($password) < $minLength || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
    $_SESSION['error'] = "Please enter a valid password";
    header('location: register.php');
} else {

    // Prepare and execute the SQL query
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $hashedPassword = $row['password']; 

                if ($row['role'] == 'admin') {
                    $_SESSION['admin_login'] = $row['id'];
                    header("location: admin.php");
                } else {
                    $_SESSION['user_login'] = $row['id'];
                    header("location: user.php");
                } 
            } else {
                $_SESSION['error'] = "Invalid credentials";
                header('location: login.php');
                } 
        } catch (PDOException $e) {
        // Login failed
        echo "Login failed: " . $e->getMessage();
        $_SESSION['error'] = "Invalid credentials";
        header('location: login.php');
    }
}
?>