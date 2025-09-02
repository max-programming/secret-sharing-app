<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>

<h1>Welcome!</h1>


<ul>
    <?php if ($is_logged_in): ?>
        <li>Profile</li>
        <li>Settings</li>
        <li>Logout</li>
    <?php else: ?>
        <li>Login</li>
        <li>Register</li>
    <?php endif; ?>
</ul>