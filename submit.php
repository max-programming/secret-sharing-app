<?php

session_start();
$conn = include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $encryptedMessage = file_get_contents($_FILES["encryptedMessage"]["tmp_name"]);
    $salt = file_get_contents($_FILES["salt"]["tmp_name"]);
    $iv = file_get_contents($_FILES["iv"]["tmp_name"]);
    $user_id = (int) $_SESSION["user_id"];

    $stmt = $conn->prepare("INSERT INTO secrets (mesaage, iv, salt, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("bbbi", $encryptedMessage, $salt, $iv, $user_id);

    $stmt->send_long_data(0, $encryptedMessage);
    $stmt->send_long_data(1, $salt);
    $stmt->send_long_data(2, $iv);

    $stmt->execute();
    $stmt->close();

    $idResult = $conn->query("SELECT id FROM secrets WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1");
    $id = $idResult->fetch_assoc();
    $generatedId = $id['id'];

    $conn->close();

    echo json_encode(["id" => $generatedId]);

}
