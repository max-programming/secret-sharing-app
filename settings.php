<?php
session_start();

include 'utils.php';

$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
  header("Location: login.php");
  exit;
}

$conn = include 'db.php';

$stmt = $conn->prepare("SELECT username, password, email FROM userinfo WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$password = $user['password'];
$username = $user['username'];
$email = $user['email'];

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save-changes'])) {
  $new_username = $_POST['username'];
  $new_email = $_POST['email'];
  $current_password = $_POST['current-password'];
  $new_password = $_POST['new-password'];
  $confirm_password = $_POST['confirm-password'];

  $hashed_password = "";

  if (!empty(trim($new_password)) && !empty(trim($confirm_password)) && !empty(trim($current_password))) {
    if ($new_password !== $confirm_password) {
      echo "<script>alert('Passwords do not match!'); window.location.href='settings.php';</script>";
      exit;
    }
    if (password_verify($current_password, $password)) {
      $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    } else {
      echo "<script>alert('Current password is incorrect!'); window.location.href='settings.php';</script>";
      exit;
    }
  }

  if (!empty(trim($new_email))) {
    validate_email($new_email, "settings");
  }

  $update_stmt = $conn->prepare("UPDATE userinfo SET username = ?, email = ?, password = ? WHERE id = ?");
  $update_stmt->bind_param("ssss", $new_username, $new_email, $hashed_password, $_SESSION['user_id']);
  $update_stmt->execute();
  $update_stmt->close();

  header("Location: profile.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile Settings</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="settings.css" type="text/css" />
</head>

<body>
  <div class="container">
    <div class="header">
      <a href="profile.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to Profile
      </a>
      <h2>Profile Settings</h2>
    </div>

    <div class="profile-pic">
      <div class="avatar-container">
        <img
          src="https://api.dicebear.com/7.x/bottts/svg?seed=<?php echo $username; ?>"
          alt="Avatar" />
        <div class="tooltip">Avatar is auto-generated based on your username</div>
      </div>
    </div>

    <form class="settings-form" method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" />
      </div>

      <div class="form-group">
        <label for="current-password">Current Password</label>
        <input type="password" id="current-password" name="current-password" />
      </div>

      <div class="form-group">
        <label for="new-password">New Password</label>
        <input type="password" id="new-password" name="new-password" />
      </div>

      <div class="form-group">
        <label for="confirm-password">Confirm New Password</label>
        <input type="password" id="confirm-password" name="confirm-password" />
      </div>

      <div class="form-actions">
        <button type="submit" name="save-changes" class="save-btn">Save Changes</button>
        <button type="button" class="cancel-btn">Cancel</button>
      </div>
    </form>
  </div>
</body>

</html>