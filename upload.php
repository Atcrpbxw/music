<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการเพลง</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>📤 อัปโหลดและจัดการเพลง</h1>

    <!-- ฟอร์มสำหรับอัปโหลดเพลง -->
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="ชื่อเพลง" required>
        <input type="file" name="file" accept=".mp3" required>
        <button type="submit" name="upload">อัปโหลด</button>
    </form>

    <?php
    // เชื่อมต่อฐานข้อมูลด้วย PDO
    require 'db.php';

    // ฟังก์ชั่นสำหรับอัปโหลดเพลง
    if (isset($_POST['upload'])) {
        $title = $_POST['title'];
        $file = $_FILES['file'];

        // ตั้งชื่อไฟล์ใหม่ ป้องกันภาษาไทย/ช่องว่าง
        $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $file['name']);
        $target = "music/" . $safeName;

        // ตรวจสอบการอัปโหลดไฟล์
        if (move_uploaded_file($file['tmp_name'], $target)) {
            // ใช้ PDO สำหรับการเตรียมคำสั่ง SQL
            try {
                $stmt = $pdo->prepare("INSERT INTO songs (title, filename) VALUES (?, ?)");
                $stmt->execute([$title, $safeName]);
                echo "<p>✅ อัปโหลดสำเร็จ!</p>";
            } catch (PDOException $e) {
                echo "<p>❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>❌ เกิดข้อผิดพลาดในการอัปโหลดไฟล์</p>";
        }
    }

    // ฟังก์ชั่นสำหรับลบเพลง
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        // ดึงชื่อไฟล์จากฐานข้อมูลเพื่อจะได้ลบไฟล์จริง
        $stmt = $pdo->prepare("SELECT filename FROM songs WHERE id = ?");
        $stmt->execute([$id]);
        $song = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($song) {
            // ลบไฟล์จากโฟลเดอร์ music
            $fileToDelete = "music/" . $song['filename'];
            if (unlink($fileToDelete)) {
                // ลบข้อมูลจากฐานข้อมูล
                $stmt = $pdo->prepare("DELETE FROM songs WHERE id = ?");
                $stmt->execute([$id]);
                echo "<p>✅ ลบเพลงเรียบร้อยแล้ว!</p>";
            } else {
                echo "<p>❌ ไม่สามารถลบไฟล์เพลงได้</p>";
            }
        } else {
            echo "<p>❌ เพลงไม่พบในฐานข้อมูล</p>";
        }
    }

    // แสดงรายการเพลง
    $stmt = $pdo->prepare("SELECT * FROM songs");
    $stmt->execute();
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($songs): ?>
        <h2>รายการเพลงที่อัปโหลด</h2>
        <ul>
            <?php foreach ($songs as $song): ?>
                <li>
                    <?php echo htmlspecialchars($song['title']); ?>
                    <a href="upload.php?delete=<?php echo $song['id']; ?>" onclick="return confirm('คุณต้องการลบเพลงนี้หรือไม่?');">❌ ลบ</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>ไม่พบเพลงในระบบ</p>
    <?php endif; ?>

    <p><a href="index.php">🔙 กลับไปหน้าเพลง</a></p>
</body>
</html>
