<?php
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

    if (!isset($secret)) {
      $data = null;
    }

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
  <title>WhisperBox | Access Secret</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #dfeb0a, #2e1fb2, #000000);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    .container {
      background: #2c2f38;
      border-radius: 12px;
      padding: 30px;
      width: 360px;
      text-align: center;
      box-shadow: 0 6px 18px rgba(2, 231, 70, 0.4);
    }

    h1 {
      margin-bottom: 20px;
      font-size: 22px;
      color: #f9d342;
    }

    .id {
      background: #4b5474;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: bold;
      color: #fff;
    }

    .otp-inputs {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin: 20px 0;
    }

    .otp-inputs input {
      width: 50px;
      height: 50px;
      text-align: center;
      font-size: 20px;
      border-radius: 8px;
      border: 2px solid #555;
      background: #222;
      color: #fff;
    }

    .warning {
      background: rgba(255, 165, 0, 0.2);
      border: 1px solid #ffa500;
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      color: #ffa500;
    }

    .btn {
      background: #f9d342;
      color: #000;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      padding: 12px 20px;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
    }

    .btn:disabled {
      background: #555;
      color: #999;
      cursor: not-allowed;
    }

    .footer {
      margin-top: 15px;
      font-size: 12px;
      opacity: 0.8;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="access-secret">
    <h1>Access Secret</h1>
    <div class="id">
      <p>
        Secret's ID:
        <?= htmlspecialchars($secret_id) ?>
      </p>
    </div>

    <?php if (isset($secret)): ?>
    <div id="message_container">
    <h3>Decrypted Message</h3>
      <p></p>
    </div>
    <div class="invalid_otp" style="display: none;">
      <h1>Invalid OTP</h1>
      <p>The OTP you entered is incorrect. Please try again.</p>
      <button class="btn" onclick="window.location.replace(window.location.href)">Retry</button>
    </div>
    <?php elseif ($data == null): ?>
    <div class="no_secret">
      <h1>⚠ Secret Does Not Exist</h1>
      <p>
        It seems that this secret may have already been accessed or has expired.
      </p>
      <button class="btn" onclick="window.location.href='index.php'">
        Go Home
      </button>
    </div>
    <?php else: ?>
    <form method="POST">
      <div class="otp-inputs">
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
        <input type="text" maxlength="1" />
      </div>

      <input type="hidden" name="otp" id="otp"  />

      <div class="warning">
        ⚠ One-Time Access <br />
        This secret will be permanently destroyed after viewing. Make sure
        you're ready to access it now.
      </div>

      <button type="submit" class="btn" id="submitBtn" disabled>
        Submit OTP
      </button>
    </form>
    <?php endif; ?>

    <div class="footer">End-to-end encrypted • Self-destructing</div>
    </div>
  </div>

  <script src="constants.js"></script>
  <script src="decrypt.js"></script>
<?php if (isset($secret)): ?>
<script>
const invalidOtp = document.querySelector(".invalid_otp");
const noSecret = document.querySelector(".no_secret");
const messageContainer = document.getElementById("message_container");
const encryptedData = <?php echo json_encode($data); ?>;

  const otp = '<?php echo $otp; ?>';

  if (!!encryptedData.secret) {
  decryptMessage(encryptedData, otp).then(message => {
    messageContainer.querySelector('p').innerText = message;
    invalidOtp.style.display = "block";
  }).catch(error => {
    messageContainer.style.display = "none";
    invalidOtp.style.display = "block";
  });
  }
</script>
<?php else: ?>
  <script>
    const inputs = document.querySelectorAll(".otp-inputs input");
    const btn = document.getElementById("submitBtn");
    const hiddenOtp = document.getElementById("otp");

    inputs.forEach((input, index) => {
      input.addEventListener("input", () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
        updateOtp();
      });

      input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && input.value === "" && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });

    function updateOtp() {
      const otpValue = [...inputs].map((inp) => inp.value).join("");
      hiddenOtp.value = otpValue;
      btn.disabled = otpValue.length !== inputs.length;
    }
  </script>
  <?php endif; ?>
</body>

</html>
