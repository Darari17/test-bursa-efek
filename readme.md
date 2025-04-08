# 📘 Dokumentasi API

## 1. Autentikasi

### 🔐 Register

- **URL:** `/api/register.php`  
- **Method:** `POST`

#### Request Body:
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

#### Response Sukses:
```json
{
  "status": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "email": "user@example.com"
    },
    "token": "generated_token_here"
  }
}
```

---

### 🔑 Login

- **URL:** `/api/login.php`  
- **Method:** `POST`

#### Request Body:
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

#### Response Sukses:
```json
{
  "status": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "email": "user@example.com"
    },
    "token": "generated_token_here"
  }
}
```

---

## 2. Kategori Produk

### 📦 Get All Categories

- **URL:** `/api/category-products.php`  
- **Method:** `GET`  
- **Header:**
  ```
  Authorization: Bearer your_token_here
  ```

#### Response Sukses:
```json
{
  "status": true,
  "message": "Daftar kategori",
  "data": [
    {
      "id": 1,
      "name": "Elektronik",
      "created_at": "2023-01-01 00:00:00"
    }
  ]
}
```

---

### ➕ Create Category

- **URL:** `/api/category-products.php`  
- **Method:** `POST`  
- **Header:**
  ```
  Authorization: Bearer your_token_here
  ```

#### Request Body:
```json
{
  "name": "Furniture"
}
```

#### Response Sukses:
```json
{
  "status": true,
  "message": "Kategori berhasil dibuat",
  "data": {
    "id": 2,
    "name": "Furniture",
    "created_at": "2023-01-01 00:00:00"
  }
}
```

---

## 3. Produk

### 🛍️ Get All Products

- **URL:** `/api/products.php`  
- **Method:** `GET`  
- **Header:**
  ```
  Authorization: Bearer your_token_here
  ```

#### Response Sukses:
```json
{
  "status": true,
  "message": "Daftar produk",
  "data": [
    {
      "id": 1,
      "product_category_id": 1,
      "name": "TV LED",
      "price": "5000000.00",
      "image": "tv.jpg",
      "created_at": "2023-01-01 00:00:00",
      "category_name": "Elektronik"
    }
  ]
}
```

---

### ➕ Create Product

- **URL:** `/api/products.php`  
- **Method:** `POST`  
- **Header:**
  ```
  Authorization: Bearer your_token_here
  ```

#### Request Body:
```json
{
  "product_category_id": 1,
  "name": "Laptop",
  "price": 8000000,
  "image": "laptop.jpg"
}
```

#### Response Sukses:
```json
{
  "status": true,
  "message": "Produk berhasil dibuat",
  "data": {
    "id": 2,
    "product_category_id": 1,
    "name": "Laptop",
    "price": "8000000.00",
    "image": "laptop.jpg",
    "created_at": "2023-01-01 00:00:00",
    "category_name": "Elektronik"
  }
}
```

---

## 🚀 Cara Instalasi

1. Clone repository:
```bash
git clone [repo-url]
cd toko-web-api
```

2. Buat database MySQL dan import struktur tabel dari file SQL yang disediakan.

3. Konfigurasi koneksi database di `config/db.php`.

4. Letakkan folder di web server seperti **XAMPP/LAMPP/etc**.

5. Akses API melalui endpoint yang tersedia.

---

## 📌 Catatan

- Semua endpoint **kecuali** login dan register membutuhkan header `Authorization`.
- Token didapatkan dari response **login/register**.
- Untuk operasi CRUD lainnya (update, delete) bisa dilihat langsung di kode sumber.

---

## ⚙️ Persyaratan Sistem

- PHP **7.4** atau lebih baru  
- MySQL **5.7** atau lebih baru  
- Web server seperti **Apache** atau **Nginx**