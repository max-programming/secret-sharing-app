<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo isset($page_title) ? $page_title : 'WhisperBox'; ?></title>
  <?php if (isset($css_file)): ?>
    <link rel="stylesheet" href="<?php echo $css_file; ?>" type="text/css" />
  <?php endif; ?>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
  <?php if (isset($additional_css)): ?>
    <?php echo $additional_css; ?>
  <?php endif; ?>
</head>

<body>
  <div class="container">
    <?php if (isset($show_header) && $show_header): ?>
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
    <?php endif; ?>