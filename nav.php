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
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script>

    function toggleMenu() {
        document.querySelector(".nav-links").classList.toggle("active");
        document.querySelector(".ham-menu").classList.toggle("change");
    }
    document.querySelectorAll(".nav-link").forEach(link => {
    link.addEventListener("click", () => {
        document.querySelector(".nav-links").classList.remove("active");
        document.querySelector(".ham-menu").classList.remove("change");
    });
});
</script>