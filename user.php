<?php
session_start();
include('db.php');  // รวมไฟล์ db.php ซึ่งจะกำหนดตัวแปร $pdo
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการเพลง</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    <?php echo '<!-- CSS loaded with version: ' . filemtime('style.css') . ' -->'; ?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<?php include 'nav.php'; ?>

    <h1>🎵 รายการเพลง</h1>

    <?php
    // ค้นหาจาก query string
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    // SQL query สำหรับดึงข้อมูลเพลง
    $sql = "SELECT * FROM songs";
    if (!empty($search)) {
        $search = "%" . $search . "%"; // ทำให้เหมือนการค้นหาใน LIKE
        $sql .= " WHERE title LIKE :search";
    }

    try {
        // เตรียมคำสั่ง SQL
        $stmt = $pdo->prepare($sql);
        
        // หากมีการค้นหา ให้ bind parameter
        if (!empty($search)) {
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        }

        // รันคำสั่ง SQL
        $stmt->execute();
        
        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
        <div class="song">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <audio controls>
                <source src="music/<?php echo htmlspecialchars($row['filename']); ?>" type="audio/mpeg">
            </audio>
        </div>
    <?php 
            }
        } else {
    ?>
        <p>ไม่พบเพลงในระบบ</p>
    <?php
        }
    } catch (PDOException $e) {
        // แสดงข้อผิดพลาดหากเกิดข้อผิดพลาดในการเชื่อมต่อหรือ execute SQL
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
    ?>

</body>
</html>
