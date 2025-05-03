<?php
session_start();
include('db.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการเพลง</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    <?php echo '<!-- CSS loaded with version: ' . filemtime('style.css') . ' -->'; ?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
    
        /* แอนิเมชันจางเข้ามาและเลื่อนขึ้น */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }

        /* container ห่อทุกกล่องเพลง */
        .song-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 20px;
            padding: 20px;
        }
        /* ให้เพลงขยับเล็กน้อยเมื่อเมาส์วาง */
        .song {
    background: #1e1e1e;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
    max-width: 300px;
    width: 90%;
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    position: relative; /* ใช้ position relative เพื่อให้ transform ทำงาน */
}

.song:hover {
    transform: translateY(-10px); /* ขยับขึ้นเมื่อเมาส์ชี้ */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5); /* เพิ่มเงาชัดขึ้น */
    background-color: #333; /* เปลี่ยนสีพื้นหลังให้เข้มขึ้น */
}



        .song img {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            display: block;
            margin-bottom: 10px;
        }

        .song h3 {
            margin: 10px 0;
            font-size: 1.2rem;
            color: #fff;
            text-align: center;
        }

        .song audio {
            width: 100%;
            margin-top: 10px;
        }

        p {
            text-align: center;
            color: #ccc;
        }
    </style>
</head>
<body>

<?php include 'nav.php'; ?>

<h1 class="animated">🎵 รายการเพลง</h1>

<div class="song-container ">
<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM songs";
if (!empty($search)) {
    $search = "%" . $search . "%";
    $sql .= " WHERE title LIKE :search";
}

try {
    $stmt = $pdo->prepare($sql);
    if (!empty($search)) {
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    }
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="song animated">
                <?php
                $imagePath = !empty($row['image']) ? 'images/' . $row['image'] : 'images/default.jpg';
                if (!file_exists($imagePath)) {
                    $imagePath = 'images/default.jpg';
                }
                ?>
                <img src="<?= $imagePath ?>" alt="รูปเพลง">
                <h3><?= htmlspecialchars($row['title']); ?></h3>
                <audio controls>
                    <source src="song/<?= htmlspecialchars($row['filename']); ?>" type="audio/mpeg">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นเสียง
                </audio>
            </div>
        <?php endwhile;
    } else {
        echo "<p>ไม่พบเพลงที่ค้นหา</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
</div>

</body>
</html>