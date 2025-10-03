<?php

session_start();
$conn = include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $encryptedMessage = $_POST["encryptedMessage"];
  $salt = $_POST["salt"];
  $iv = $_POST["iv"];

  $user_id = (int) $_SESSION["user_id"];

  $stmt = $conn->prepare(
    "INSERT INTO secrets (message, iv, salt, user_id) VALUES (?, ?, ?, ?)",
  );
  $stmt->bind_param("sssi", $encryptedMessage, $iv, $salt, $user_id);

  $stmt->execute();
  $stmt->close();

  $idResult = $conn->query(
    "SELECT id FROM secrets WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1",
  );
  $id = $idResult->fetch_assoc();
  $generatedId = $id["id"];

  $conn->close();

  echo json_encode(["id" => $generatedId]);
}
