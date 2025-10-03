<?php
session_start();
$is_logged_in = isset($_SESSION["user_id"]);
if (!$is_logged_in) {
  header("Location: login.php");
  exit();
}

$conn = include "db.php";

$stmt = $conn->prepare("SELECT username, email FROM userinfo WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user["username"];
$email = $user["email"];

$stmt->close();

// Fetch user's secrets
$stmt = $conn->prepare(
  "SELECT id, created_at FROM secrets WHERE user_id = ? ORDER BY created_at DESC",
);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$secrets_result = $stmt->get_result();
$secrets = $secrets_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
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
  <script>
    function copyToClipboard(text, el) {
      navigator.clipboard.writeText(text).then((event) => {
        el.textContent = 'Copied!';
        el.style.backgroundColor = '#28a745';
        setTimeout(() => {
          el.textContent = 'Copy';
          el.style.backgroundColor = '#007bff';
        }, 2000);
      }, (err) => {
        console.error('Could not copy text: ', err);
      });
    }
  </script>
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
  <div class="secrets-section">
    <h2>Your Secrets</h2>
    <?php if (empty($secrets)): ?>
      <div class="no-secrets">
        <p>You haven't created any secrets yet.</p>
        <a href="index.php" class="button-link small">Create Your First Secret</a>
      </div>
    <?php else: ?>
      <div class="secrets-list">
        <?php foreach ($secrets as $secret): ?>
          <div class="secret-item" id="secret-<?php echo htmlspecialchars(
                                                $secret["id"],
                                              ); ?>">
            <div class="secret-info">
              <div class="secret-id">
                <label>ID:</label>
                <span><?php echo htmlspecialchars($secret["id"]); ?></span>
              </div>
              <div class="secret-date">
                <label>Created:</label>
                <span><?php echo date(
                        'M j, Y \a\t g:i A',
                        strtotime($secret["created_at"]),
                      ); ?></span>
              </div>
              <div class="secret-url">
                <label>Share URL:</label>
                <div class="url-container">
                  <input type="text" class="url-input" readonly
                    value="<?php echo htmlspecialchars(
                              (isset($_SERVER["HTTPS"]) &&
                                $_SERVER["HTTPS"] === "on"
                                ? "https://"
                                : "http://") .
                                $_SERVER["HTTP_HOST"] .
                                "/secret-sharing-app/view.php?id=" .
                                $secret["id"],
                            ); ?>">
                  <button class="copy-btn" onclick="copyToClipboard(window.location.hostname + '/secret-sharing-app/view.php?id=<?php echo htmlspecialchars($secret['id']); ?>', event.target)">
                    Copy
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  </div>
</body>

</html>