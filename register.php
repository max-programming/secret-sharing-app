<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
if ($is_logged_in) {
    header("Location: index.php");
    exit;
}

$conn = include 'db.php';
include 'utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    check_empty_value($username, "Username", "register");
    check_empty_value($email, "Email", "register");
    check_empty_value($password, "Password", "register");
    check_empty_value($confirm_password, "Confirm Password", "register");
    validate_email($email, "register");



    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='register.php';</script>";
        exit;
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO userinfo (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            // get newly inserted user ID directly
            $user_id = $stmt->insert_id;

            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // redirect without echoing before
            header("Location: index.php?registered=1");
            exit;
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}
$page_title = "Register";
$css_file = "register.css";
$show_header = false;
include 'header.php';
?>
<div class="joinus">Join us!</div>

<form class="form" method="POST">
    <div class="input-group">
        <label for="name">Username</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-user"></i>
            <input type="text" id="username" required name="username" placeholder="Enter your username">
        </div>
    </div>

    <div class="input-group">
        <label for="email">Email</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-at"></i>
            <input type="email" id="email" required name="email" placeholder="Enter your email">
        </div>
    </div>

    <div class="input-group">
        <label for="pwd">Password</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="pwd" required name="password" placeholder="Enter Password">
        </div>
    </div>

    <div class="input-group">
        <label for="cpwd">Confirm Password</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="cpwd" required name="confirm_password" placeholder="Confirm Password">
        </div>
    </div>

    <button type="submit" name="register">Register</button>
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