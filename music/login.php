<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        /* แอนิเมชันจางเข้ามาและเลื่อนขึ้น */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);/* เริ่มต้นจากตำแหน่งต่ำกว่า */
            }
            100% {
                opacity: 1;
                transform: translateY(0); /* กลับสู่ตำแหน่งปกติ */
            }
        }

        .animated {
            animation: fadeIn 0.5s ease-out forwards; /* จางเข้ามาและเลื่อนขึ้น */
            opacity: 0; /* เริ่มต้นที่โปร่งใส */
        }

    </style>
</head>

<body>
<?php include('nav.php'); ?>
<main class="form-signin text-center">
        <form action="login_db.php" method="POST">
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger">
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                    ?>
                </div>   
            <?php } ?>

            <div class="login-box animated">
            <div class="text-center">
            <h1 class="h3 mb-3 fw-normal ">Login</h1>
                <div class="form-group text-start">
                    <label for="floatingInput" >Email address</label>
                    <input type="email" class="form-control mb-3" name="email" placeholder="name@example.com">
                </div>

                <div class="form-group text-start">
                    <label for="floatingPassword">Password</label>
                    <input type="password" id="myPass" class="form-control mb-3" name="password" placeholder="Password">
                
                <div class="checkbox mb-3">
                <label>
                    <input type="checkbox"  onclick="showPass()"> Show password
                </label>
                </div>

                </div>
                
                <button  name="login" type="submit">Sign in</button>
                
                <div class="form-group text-start ">
                <p>ยังไม่มีบัญชีใช่มั้ย คลิ๊กที่นี่เพื่อ <a href="register.php" style="color: #FF0000">สร้างบัญชี</a></p>
                </div>
            </div>
            </div>
            </div>
            </div>
        </form>
    </main>
        <script>
            function showPass() {
                let myPass = document.getElementById('myPass');
                if (myPass.type === "password") {
                    myPass.type = "text";
                } else {
                    myPass.type = "password";
                }
            }
        </script>
            </body>

</html>
