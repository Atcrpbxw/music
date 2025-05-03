<?php
session_start();
// Include the database connection code
require 'db.php';
$minLength = 8;

// Retrieve and validate user input
if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'user';
}
// Perform validation checks on user input
// ...

if (empty($firstname)) {
    $_SESSION['error'] = "กรุณากรอกชื่อ";
    header('location: register.php');
} else if (empty($lastname)) {
    $_SESSION['error'] = "กรุณากรอกนามสกุล";
    header('location: register.php');
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "กรุณากรอกอีเมล";
    header('location: register.php');
} else if (strlen($password) < $minLength) {
    $_SESSION['error'] = "กรุณากรอกรหัสผ่าน";
    header('location: register.php');
} else if (!preg_match('/[A-Z]/', $password)) {
    $_SESSION['error'] = "รหัสผ่านของคุณต้องมีตัวพิมพ์ใหญ่";
    header('location: register.php');
} else if (!preg_match('/[a-z]/', $password)) {
    $_SESSION['error'] = "รหัสผ่านของคุณต้องมีอักขระตัวพิมพ์เล็ก";
    header('location: register.php');
} else if (!preg_match('/\d/', $password)) {
    $_SESSION['error'] = "รหัสผ่านของคุณต้องมีตัวเลขหลัก";
    header('location: register.php');
} else {

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        $_SESSION['error'] = 'มีอีเมลอยู่แล้ว';
        // Redirect the email to the registration page or display an error message
        header('Location: register.php');
        exit;
    } else {
        // // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // // Prepare and execute the SQL query
        try {
            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $email, $hashedPassword,$role]);

            $_SESSION['success'] = "ลงทะเบียนสำเร็จ!";
            header('location: register.php');
        } catch (PDOException $e) {
            $_SESSION['error'] = "มีข้อผิดพลาดเกิดขึ้น โปรดลองอีกครั้ง!";
            echo "Registration failed: " . $e->getMessage();
            header('location: register.php');
        }
    }

}