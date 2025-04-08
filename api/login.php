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
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    jsonResponse(false, 'Email tidak terdaftar');
}

if (!password_verify($password, $user['password'])) {
    jsonResponse(false, 'Password salah');
}

$token = generateToken($email);

jsonResponse(true, 'Login berhasil', [
    'user' => ['email' => $user['email']],
    'token' => $token
]);

?>