<?php
session_start();

$conn = include "db.php";

if (!isset($_GET["id"])) {
  echo "<script>alert('Secret ID is required!'); window.location.href='index.php';</script>";
  exit();
} else {
  $secret_id = $_GET["id"];
}

$error_message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["otp"])) {
  try {
    $stmt = $conn->prepare(
      "SELECT message, iv, salt FROM secrets WHERE id = ?",
    );
    $stmt->bind_param("s", $secret_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $secret = $result->fetch_assoc();

    $data = [
      "encryptedMessage" => $secret["message"],
      "salt" => $secret["salt"],
      "iv" => $secret["iv"],
    ];

    $otp = $_POST["otp"];
  } catch (Exception $e) {
    $error_message = $e->getMessage();
    echo $error_message;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WhisperBox | View Secret</title>
    <link rel="stylesheet" href="secret.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
</head>

<body>
    <div class="container">
        <header>
            <div class="header">
                <ul>
                    <li id="secrets"><a href="index.php">WhisperBox</a></li>
                    <a href="about.php">
                        <li>About</li>
                    </a>
                </ul>
            </div>
        </header>

        <main>
            <div class="content">
                <div class="secret-header">
                    <i class="fa-solid <?php echo isset($secret)
                      ? "fa-unlock"
                      : "fa-lock"; ?>"></i>
                    <h1>Secret <?php echo isset($secret)
                      ? "Unlocked"
                      : "Locked"; ?></h1>
                    <p class="secret-id">Secret ID: <span><?php echo htmlspecialchars(
                      $secret_id,
                    ); ?></span></p>
                </div>

                <div class="<?php echo isset($secret)
                  ? "success-box"
                  : "warning-box"; ?>">
                <?php if (isset($secret)): ?>
                    <i class="fa-solid fa-circle-check"></i>
                    <p><strong>Info:</strong> The secret has been opened and now destroyed. You can only view it here right now.</p>
                    <?php else: ?>
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    <p><strong>Warning:</strong> Opening this secret will destroy it forever. Make sure you're ready to view it before entering the OTP.</p>
                <?php endif; ?>
                </div>

                <?php if (isset($secret)): ?>
                  Decrypted Message: <br />
                <div id="message_container" class="message-container">
                  <p>
                  </p>
                </div>
                <?php else: ?>
                <div class="otp-section">
                    <form method="POST" class="otp-form" id="otp_form">
                        <div class="input-group">
                            <label for="otp">Enter OTP to unlock secret:</label>
                            <input
                                type="text"
                                id="otp"
                                name="otp"
                                placeholder="Enter 4-digit OTP"
                                maxlength="4"
                                pattern="[0-9]{4}"
                                required
                                autocomplete="off"
                            />
                        </div>

                        <?php if (!empty($error_message)): ?>
                            <div class="error-message">
                                <i class="fa-solid fa-times-circle"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="unlock-btn">
                            <i class="fa-solid fa-unlock"></i>
                            Unlock Secret
                        </button>
                    </form>
                </div>
                <?php endif; ?>

                <div class="info-section">
                    <div class="info-item">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>End-to-End Encrypted</span>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-trash"></i>
                        <span>Self-Destructing</span>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-user-secret"></i>
                        <span>Anonymous</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="constants.js"></script>
    <script src="decrypt.js"></script>

    <?php if (isset($secret)): ?>
    <script>
        const messageContainer = document.getElementById('message_container');
    const encryptedData = <?php echo json_encode($data); ?>;

      const otp = '<?php echo $otp; ?>';

      decryptMessage(encryptedData, otp).then(message => {
        messageContainer.querySelector('p').innerText = message;
      });
    </script>
    <?php else: ?>
    <script>
            // Auto-format OTP input
            document.getElementById('otp').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Focus on OTP input when page loads
            window.addEventListener('load', function() {
                document.getElementById('otp').focus();
            });
        </script>
    <?php endif; ?>

</body>

</html>
