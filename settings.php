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
          src="https://api.dicebear.com/7.x/bottts/svg?seed=usernameHere"
          alt="Avatar" />
        <div class="tooltip">Avatar is auto-generated based on your username</div>
      </div>
    </div>

    <form class="settings-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="UsernameHere" />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="email@example.com" />
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
        <button type="submit" class="save-btn">Save Changes</button>
        <button type="button" class="cancel-btn" onclick="window.location.href='profile.php'">Cancel</button>
      </div>
    </form>
  </div>
</body>

</html>