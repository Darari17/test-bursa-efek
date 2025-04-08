<?php
require_once 'auth.php';

authenticateToken();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $conn->prepare("
                SELECT p.*, cp.name as category_name 
                FROM products p
                JOIN category_products cp ON p.product_category_id = cp.id
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                jsonResponse(true, 'Produk ditemukan', $product);
            } else {
                jsonResponse(false, 'Produk tidak ditemukan');
            }
        } else {
            $stmt = $conn->query("
                SELECT p.*, cp.name as category_name 
                FROM products p
                JOIN category_products cp ON p.product_category_id = cp.id
            ");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(true, 'Daftar produk', $products);
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['name']) || empty($data['price']) || empty($data['product_category_id'])) {
            jsonResponse(false, 'Nama, harga, dan kategori produk harus diisi');
        }
        
        $name = validateInput($data['name']);
        $price = validateInput($data['price']);
        $categoryId = validateInput($data['product_category_id']);
        $image = isset($data['image']) ? validateInput($data['image']) : null;
        
        $stmt = $conn->prepare("
            INSERT INTO products (product_category_id, name, price, image) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$categoryId, $name, $price, $image]);
        
        $lastId = $conn->lastInsertId();
        $stmt = $conn->prepare("
            SELECT p.*, cp.name as category_name 
            FROM products p
            JOIN category_products cp ON p.product_category_id = cp.id
            WHERE p.id = ?
        ");
        $stmt->execute([$lastId]);
        $newProduct = $stmt->fetch(PDO::FETCH_ASSOC);
        
        jsonResponse(true, 'Produk berhasil dibuat', $newProduct);
        break;
        
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        
        if (empty($_GET['id']) || empty($data['name']) || empty($data['price']) || empty($data['product_category_id'])) {
            jsonResponse(false, 'ID, nama, harga, dan kategori produk harus diisi');
        }
        
        $id = $_GET['id'];
        $name = validateInput($data['name']);
        $price = validateInput($data['price']);
        $categoryId = validateInput($data['product_category_id']);
        $image = isset($data['image']) ? validateInput($data['image']) : null;
        
        $stmt = $conn->prepare("
            UPDATE products 
            SET product_category_id = ?, name = ?, price = ?, image = ?
            WHERE id = ?
        ");
        $stmt->execute([$categoryId, $name, $price, $image, $id]);
        
        if ($stmt->rowCount() > 0) {
            $stmt = $conn->prepare("
                SELECT p.*, cp.name as category_name 
                FROM products p
                JOIN category_products cp ON p.product_category_id = cp.id
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            $updatedProduct = $stmt->fetch(PDO::FETCH_ASSOC);
            jsonResponse(true, 'Produk berhasil diupdate', $updatedProduct);
        } else {
            jsonResponse(false, 'Gagal mengupdate produk');
        }
        break;
        
    case 'DELETE':
        if (empty($_GET['id'])) {
            jsonResponse(false, 'ID produk harus diisi');
        }
        
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            jsonResponse(true, 'Produk berhasil dihapus');
        } else {
            jsonResponse(false, 'Gagal menghapus produk');
        }
        break;
        
    default:
        jsonResponse(false, 'Metode request tidak didukung');
}
?>