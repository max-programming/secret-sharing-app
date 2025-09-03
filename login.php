<?php
session_start();

$conn = include 'db.php';
include 'utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    check_empty_value($username, 'Username', 'login');
    check_empty_value($password, 'Password', 'login');

    $stmt = $conn->prepare("SELECT id, username, password FROM userinfo WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('No user found with that username.');</script>";
    }

    $stmt->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./login.css" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="welcome">Welcome back to WhisperBox!</div>

        <form class="form" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="username" required name="username" placeholder="Enter your username">
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" required name="password" placeholder="Enter your password">
                </div>
            </div>

            <div class="extra">
                <div class="fp"><a href="#">Forgot Password?</a></div>
                <div class="reg"><a href="register.php">Create Account</a></div>
            </div>

            <button type="submit" name="login">Login</button>
        </form>

        <div class="google">
            <img src="google.jpg" id="g"> Continue with Google
        </div>
        <div class="facebook">
            <img src="facebook.webp" id="f"> Continue with Facebook
        </div>
    </div>
</body>

</html>