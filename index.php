<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>

<?php if ($is_logged_in): ?>
    <a href="#"><i class="fa-solid fa-user"></i>
        <li id="profile">Profile</li>
    </a>
    <a href="#"><i class="fa-solid fa-gear"></i>
        <li id="settings">Settings</li>
    </a>
    <button>
        <a href="#">
            <li id="logout">Logout</li>
        </a>
    </button>
<?php else: ?>
    <button id="login">
        <a href="login.php">
            <li id="login2">Login</li>
        </a>
    </button>
    <a href="register.php">
        <li>Register</li>
    </a>

<?php endif; ?>