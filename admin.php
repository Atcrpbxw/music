<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: login.php');
    exit(); // เพิ่มการใช้ exit เพื่อป้องกันไม่ให้โค้ดด้านล่างทำงานต่อหลังจาก redirect
}

// เชื่อมต่อกับฐานข้อมูล
include 'db.php'; // ตรวจสอบให้แน่ใจว่ามีการเชื่อมต่อ PDO ในไฟล์นี้
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>อัปโหลดเพลง</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    <?php echo '<!-- CSS loaded with version: ' . filemtime('style.css') . ' -->'; ?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(0, 0, 0);
            color: #333;
            margin: 150px;
            padding: 0;
        }

        header, h1 {
            text-align: center;
            margin: 20px 0;
            color: #4CAF50;
        }

        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            text-align: center;
        }

        .form-container input[type="text"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        .message p {
            font-size: 16px;
            font-weight: bold;
        }

        .message p.success {
            color: #28a745;
        }

        .message p.error {
            color: #dc3545;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
        /* รายการเพลง */
        ul {
            list-style-type: none;
            padding: 0;
        }

        .formli {
            background-color: #fff;
            margin: 10px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            text-decoration: none;
        }

        p {
            text-align: center;
            text-decoration: none;
        }

        .message {
            text-align: center;
            font-size: 1.1rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">
      <div class="logo"><a href="#" class="logo-text">Music</a></div>
      <nav class="navbar">
        <ul class="nav-links">
          <li><a href="index.php" class="nav-link <?= $page == 'index' ? 'active' : '' ?>"><i class='bx bxs-home'></i> หน้าหลัก</a></li>
        <li>
          <form method="GET" action="index.php" class="search-form">
            <input type="text" name="search" placeholder="ค้นหาเพลง...">
            <button type="submit">ค้นหา</button>
          </form>
        </li>
        <?php if (isset($_SESSION['admin_login']) || isset($_SESSION['user_login'])) { ?>
          <span class="user-name"><?php echo isset($row['firstname']) ? htmlspecialchars($row['firstname']) : ''; ?></span>
          <a href="logout.php" class="button">Logout</a>
        <?php } else { ?>
          <a href="login.php" class="button"><i class='bx bx-user'></i> เข้าสู่ระบบ</a>
        <?php } ?>
    </ul>
</nav>

        <!-- ปุ่ม 3 ขีด -->
        <div class="ham-menu" onclick="toggleMenu()">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>
</header>
    
    <h1>📤 อัปโหลดเพลงใหม่</h1>

    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="ชื่อเพลง" required>
            <input type="file" name="file" accept=".mp3" required>
            <input type="file" name="image" accept="image/*"> <!-- เพิ่มช่องอัปโหลดรูป -->
            <button type="submit" name="upload">อัปโหลด</button>
        </form>

        <?php
        // เชื่อมต่อฐานข้อมูลด้วย PDO
        require 'db.php';

        if (isset($_POST['upload'])) {
            $title = $_POST['title'];
            $file = $_FILES['file'];

            // ตั้งชื่อไฟล์ใหม่ ป้องกันภาษาไทย/ช่องว่าง
            $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $file['name']);
            $target = "song/" . $safeName;

            // ตรวจสอบการอัปโหลดไฟล์
            if (move_uploaded_file($file['tmp_name'], $target)) {
                // จัดการรูปภาพ
                $imageName = '';
                $image = $_FILES['image'];
                if ($image['name']) {
                    $imageName = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $image['name']);
                    $imageTarget = "images/" . $imageName;
                    
                    if (!move_uploaded_file($image['tmp_name'], $imageTarget)) {
                        $imageName = ''; // ถ้าเกิดข้อผิดพลาดในการอัปโหลด
                    }
                }

                // ใช้ PDO สำหรับการเตรียมคำสั่ง SQL
                try {
                    $stmt = $pdo->prepare("INSERT INTO songs (title, filename, image) VALUES (?, ?, ?)");
                    $stmt->execute([$title, $safeName, $imageName]);
                    echo "<div class='message'><p class='success'>✅ อัปโหลดสำเร็จ!</p></div>";
                } catch (PDOException $e) {
                    echo "<div class='message'><p class='error'>❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage() . "</p></div>";
                }
            } else {
                echo "<div class='message'><p class='error'>❌ เกิดข้อผิดพลาดในการอัปโหลดไฟล์</p></div>";
            }
        }
         // ฟังก์ชั่นสำหรับลบเพลง
        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];

            // ดึงชื่อไฟล์จากฐานข้อมูล
            $stmt = $pdo->prepare("SELECT filename, image FROM songs WHERE id = ?");
            $stmt->execute([$id]);
            $song = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($song) {
                $fileToDelete = "song/" . $song['filename'];
                $imageToDelete = !empty($song['image']) ? "images/" . $song['image'] : '';

                // ลบไฟล์เพลง ถ้ามี
                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete);
                }

                // ลบรูปภาพ ถ้ามี
                if ($imageToDelete && file_exists($imageToDelete)) {
                    unlink($imageToDelete);
                }

                // ลบข้อมูลจากฐานข้อมูล
                $stmt = $pdo->prepare("DELETE FROM songs WHERE id = ?");
                $stmt->execute([$id]);
                echo "<p class='message success'>✅ ลบเพลงและรูปภาพเรียบร้อยแล้ว!</p>";
            } else {
                echo "<p class='message error'>❌ ไม่พบเพลงในฐานข้อมูล</p>";
            }
        }

// แสดงรายการเพลง (อยู่นอก if)
$stmt = $pdo->prepare("SELECT * FROM songs");
$stmt->execute();
$songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($songs): ?>
    <h2>รายการเพลงที่อัปโหลด</h2>
    <ul>
        <?php foreach ($songs as $song): ?>
            <div class="formli">
                <ul>
                    <li>
                        <?= htmlspecialchars($song['title']); ?>
                        <a href="admin.php?delete=<?= $song['id']; ?>" onclick="return confirm('คุณต้องการลบเพลงนี้หรือไม่?');">❌ ลบ</a>
                        <?php if ($song['image']): ?>
                            <br><img src="images/<?= $song['image']; ?>" alt="รูปเพลง" style="width: 100px; height: 100px; border-radius: 5px;">
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>ไม่พบเพลงในระบบ</p>
<?php endif; ?>

    </div>

    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script>
        function toggleMenu() {
            var navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }
    </script>
</div>
</body>
</html>
