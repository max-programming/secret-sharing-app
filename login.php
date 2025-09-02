<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="conatiner">
        <div class="welcome">Welcome back to whim!</div>
        <div class="container2">
            <form class="form">
                <div id="uname">
                    <div class="usericon"><i class="fa-solid fa-user"></i></div>
                    <input type="text" required name="username" placeholder="Username">
                </div>
                <div id="pwd">
                    <div class="pwdicon"><i class="fa-solid fa-lock"></i></div>
                    <input type="password" required name="password" placeholder="Password">
                </div>
                <div class="extra">
                    <div class="fp"><a href="">Forgot Password?</a></div>
                    <div class="reg"><a href="register.html">Create Account</a></div>
                </div>
            </form>
            <div id="login">
                <button type="submit" name="login">Login</button>
            </div>
        </div>
    </div>
</body>
</html>
<?php


?>
