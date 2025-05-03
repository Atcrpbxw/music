<?php 
session_start();
include ('nav.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: translateY(-10px);
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

    .alert {
      max-width: 600px;
      margin: 20px auto 20px;
      padding: 15px 20px;
      border-radius: 8px;
      font-size: 16px;
      display: none;
      opacity: 0;
      text-align: center;
      z-index: 900;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .alert.show {
      display: block;
      animation: fadeIn 0.5s ease-out forwards;
    }
  </style>
</head>

<body>
  <!-- แสดงข้อความแจ้งเตือนใต้ header -->
  <?php if (isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger show">
      <?php 
        echo $_SESSION['error']; 
        unset($_SESSION['error']);
      ?>
    </div>   
  <?php } ?>

  <?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success show">
      <?php 
        echo $_SESSION['success']; 
        unset($_SESSION['success']);
      ?>
    </div>   
  <?php } ?>

  <main class="form-signin text-center">
    <form action="register_db.php" method="POST">
      <div class="container animated" style="max-width: 400px; margin: 100px auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);">
        <div class="text-center">
          <h1 class="h3 mb-3 fw-normal">Sign Up</h1>

          <div class="form-group text-start">
            <label for="floatingInput">Firstname</label>
            <input type="text" class="form-control mb-3" name="firstname" placeholder="Enter your firstname">
          </div>

          <div class="form-group text-start">
            <label for="floatingInput">Lastname</label>
            <input type="text" class="form-control mb-3" name="lastname" placeholder="Enter your lastname">
          </div>

          <div class="form-group text-start">
            <label for="floatingInput">Email address</label>
            <input type="email" class="form-control mb-3" name="email" id="floatingInput" placeholder="name@example.com">
          </div>

          <div class="form-group text-start">
            <label for="floatingPassword">Password</label>
            <input type="password" id="myPass" class="form-control mb-3" name="password" placeholder="Password">
          </div>

          <div class="form-group text-start">
            <div class="checkbox mb-3">
              <label>
                <input type="checkbox" onclick="showPass()"> Show password
              </label>
            </div>
          </div>

          <button name="register" type="submit">Sign in</button>
          <hr>

          <div class="form-group text-start">
            <p>มีบัญชีอยู่แล้วใช่มั้ย คลิ๊กที่นี่เพื่อ <a href="login.php" style="color: #FF0000">เข้าสู่ระบบ</a></p>
          </div>
        </div>
      </div>
    </form>
  </main>

  <script>
    function showPass() {
      let myPass = document.getElementById('myPass');
      myPass.type = myPass.type === "password" ? "text" : "password";
    }

   // ซ่อน alert หลัง 10 วินาที
setTimeout(function () {
  const alerts = document.querySelectorAll('.alert.show');
  alerts.forEach(function(alert) {
    alert.style.transition = 'opacity 0.5s ease-out';
    alert.style.opacity = '0';
    setTimeout(() => {
      alert.style.display = 'none';
    }, 500);
  });
}, 5000);

  </script>
</body>
</html>
