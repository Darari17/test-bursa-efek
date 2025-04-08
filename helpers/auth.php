<?php
require_once 'helper.php';

function authenticateToken() {
    $headers = getallheaders();
    
    if (!isset($headers['Authorization'])) {
        jsonResponse(false, 'Token tidak ditemukan');
    }
    
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    
    if (empty($token)) {
        jsonResponse(false, 'Token tidak valid');
    }
    
    return $token;
}
?>