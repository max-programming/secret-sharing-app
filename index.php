<?php
session_start();
$is_logged_in = isset($_SESSION["user_id"]);

// Get stats for display
$conn = include "db.php";
include "stats_helper.php";
$stats = getStats($conn);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WhisperBox | Home</title>
    <link rel="stylesheet" href="home.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
</head>

<body>
    <div class="container">
        <header>
            <div class="header">
                <ul>
                    <li id="secrets">WhisperBox</li>
                    <?php if ($is_logged_in): ?>
                        <a href="profile.php"><i class="fa-solid fa-user"></i>
                            <li id="profile">Profile</li>
                        </a>
                        <a href="settings.php"><i class="fa-solid fa-gear"></i>
                            <li id="settings">Settings</li>
                        </a>
                        <button>
                            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
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
                        <a href="about.php">
                            <li>About</li>
                        </a>
                    <?php endif; ?>
                </ul>
            </div>
        </header>

        <main>
            <div class="content">
                <div class="hero">
                    <div class="text-block">
                        <h1>Welcome to WhisperBox</h1>
                        <p>
                            Share your thoughts while staying <i>anonymous<i>. </p>
                    </div>
                    <div class="image-container">
                        <img src="image.png" alt="Secret Sharing" />
                    </div>
                </div>

                <div class="features">
                    <div id="box3">
                        Anonymus Messaging <i class="fa-solid fa-user-secret"></i>
                    </div>
                    <div id="box3">
                        OTP Verification <i class="fa-solid fa-check"></i>
                    </div>
                    <div id="box1">
                        End-to-End-Encryption <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div id="box2">
                        Self-Destructive <i class="fa-solid fa-trash"></i>
                    </div>
                </div>

                <form class="button-container" id="message_form">
                    <hr />
                    <div class="encrypt">
                        <textarea id="textarea" placeholder="Enter your message..." name="message" rows="5"></textarea>
                        <button type="submit" id="otp">Encrypt & Generate OTP</button>
                    </div>
                </form>

                <div class="stats-section">
                    <h2>Platform Statistics</h2>
                    <div class="stats-container">
                        <div class="stat-box">
                            <i class="fa-solid fa-lock"></i>
                            <div class="stat-number"><?php echo number_format($stats['created_secrets']); ?></div>
                            <div class="stat-label">Secrets Created</div>
                        </div>
                        <div class="stat-box">
                            <i class="fa-solid fa-fire"></i>
                            <div class="stat-number"><?php echo number_format($stats['destroyed_secrets']); ?></div>
                            <div class="stat-label">Secrets Destroyed</div>
                        </div>
                    </div>
                </div>


            </div>
        </main>
    </div>
</body>

<script src="constants.js"></script>
<script src="index.js"></script>

</html>