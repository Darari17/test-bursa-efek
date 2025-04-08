<?php
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../helpers/helper.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

$endpoints = [
    '/api/login' => ['file' => '../api/login.php', 'methods' => ['POST']],
    '/api/register' => ['file' => '../api/register.php', 'methods' => ['POST']],
    '/api/products' => ['file' => '../api/products.php', 'methods' => ['GET', 'POST']],
    '/api/category-products' => ['file' => '../api/category-products.php', 'methods' => ['GET', 'POST']]
];

foreach ($endpoints as $path => $config) {
    if (strpos($request_uri, $path) === 0 && in_array($request_method, $config['methods'])) {
        require __DIR__.'/'.$config['file'];
        exit;
    }
}

jsonResponse(false, 'Endpoint tidak ditemukan', null, 404);
?>