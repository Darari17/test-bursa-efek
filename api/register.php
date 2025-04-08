<?php
require_once 'helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Metode request harus POST');
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['email']) || empty($data['password'])) {
    jsonResponse(false, 'Email dan password harus diisi');
}

$email = validateInput($data['email']);
$password = validateInput($data['password']);

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    jsonResponse(false, 'Email sudah terdaftar');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->execute([$email, $hashedPassword]);

$token = generateToken($email);

jsonResponse(true, 'Registrasi berhasil', [
    'user' => ['email' => $email],
    'token' => $token
]);
?>