<?php
require_once 'db.php';

function jsonResponse($status, $message, $data = null) {
    header('Content-Type: application/json');
    $response = ['status' => $status, 'message' => $message];
    if ($data) $response['data'] = $data;
    echo json_encode($response);
    exit;
}

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateToken($email) {
    return md5($email . time());
}

?>