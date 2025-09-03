<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
  header("Location: login.php");
  exit;
}

$conn = include 'db.php';

$stmt = $conn->prepare("SELECT username, email FROM userinfo WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user['username'];
$email = $user['email'];

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Profile</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="profile.css" type="text/css" />

</head>

<body>
  <div class="container">
    <div class="profile-pic">
      <img
        src="https://api.dicebear.com/7.x/bottts/svg?seed=<?php echo $username; ?>"
        alt="Avatar" />
    </div>

    <div class="field">
      <label>Username:</label>
      <span><?php echo $username; ?></span>
    </div>

    <div class="field">
      <label>Email:</label>
      <span><?php echo $email; ?></span>
    </div>

    <a href="settings.php" class="button-link">Edit Profile</a>
  </div>
</body>

</html>