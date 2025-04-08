<?php
require_once 'auth.php';

authenticateToken();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM category_products WHERE id = ?");
            $stmt->execute([$id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($category) {
                jsonResponse(true, 'Kategori ditemukan', $category);
            } else {
                jsonResponse(false, 'Kategori tidak ditemukan');
            }
        } else {
            $stmt = $conn->query("SELECT * FROM category_products");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(true, 'Daftar kategori', $categories);
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['name'])) {
            jsonResponse(false, 'Nama kategori harus diisi');
        }
        
        $name = validateInput($data['name']);
        
        $stmt = $conn->prepare("INSERT INTO category_products (name) VALUES (?)");
        $stmt->execute([$name]);
        
        $lastId = $conn->lastInsertId();
        $stmt = $conn->prepare("SELECT * FROM category_products WHERE id = ?");
        $stmt->execute([$lastId]);
        $newCategory = $stmt->fetch(PDO::FETCH_ASSOC);
        
        jsonResponse(true, 'Kategori berhasil dibuat', $newCategory);
        break;
        
    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        
        if (empty($_GET['id']) || empty($data['name'])) {
            jsonResponse(false, 'ID dan nama kategori harus diisi');
        }
        
        $id = $_GET['id'];
        $name = validateInput($data['name']);
        
        $stmt = $conn->prepare("UPDATE category_products SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
        
        if ($stmt->rowCount() > 0) {
            $stmt = $conn->prepare("SELECT * FROM category_products WHERE id = ?");
            $stmt->execute([$id]);
            $updatedCategory = $stmt->fetch(PDO::FETCH_ASSOC);
            jsonResponse(true, 'Kategori berhasil diupdate', $updatedCategory);
        } else {
            jsonResponse(false, 'Gagal mengupdate kategori');
        }
        break;
        
    case 'DELETE':
        if (empty($_GET['id'])) {
            jsonResponse(false, 'ID kategori harus diisi');
        }
        
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("DELETE FROM category_products WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            jsonResponse(true, 'Kategori berhasil dihapus');
        } else {
            jsonResponse(false, 'Gagal menghapus kategori');
        }
        break;
        
    default:
        jsonResponse(false, 'Metode request tidak didukung');
}
?>