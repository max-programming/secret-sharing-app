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
            <form method="POST" class="form">
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

$conn = include 'db.php';
include 'utils.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    check_empty_value($username, 'Username', 'login');
    check_empty_value($password, 'Password', 'login');

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and set session variables
            session_start();
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