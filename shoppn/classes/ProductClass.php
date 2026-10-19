<?php

require_once __DIR__ . '/../core/db_class.php';


class ProductClass extends Database
{
    // BRAND METHODS

    public function addBrand($name)
    {
        $sql = "INSERT INTO brands (brand_name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function getAllBrands()
    {
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBrandById($id)
    {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $brand = $result->fetch_assoc();

        $stmt->close();

        return $brand;
    }

    public function updateBrand($id, $name)
    {
        $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // CATEGORY METHODS

    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (cat_name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE cat_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $category = $result->fetch_assoc();

        $stmt->close();

        return $category;
    }

    public function updateCategory($id, $name)
    {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    
    // PRODUCT METHODS

    public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        $sql = "INSERT INTO products
                (product_cat, product_brand, product_title, product_price,
                 product_desc, product_image, product_keywords)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iisdsss",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords)
    {
        $sql = "UPDATE products
                SET product_cat = ?,
                    product_brand = ?,
                    product_title = ?,
                    product_price = ?,
                    product_desc = ?,
                    product_image = ?,
                    product_keywords = ?
                WHERE product_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iisdsssi",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords,
            $id
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function getProductById($id)
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                JOIN categories c ON p.product_cat = c.cat_id
                JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        $stmt->close();

        return $product;
    }

    public function getAllProducts()
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                JOIN categories c ON p.product_cat = c.cat_id
                JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";

        $result = $this->conn->query($sql);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // TASK 10: CUSTOMER-FACING PRODUCT QUERIES

public function getFeaturedProducts($limit = 6)
{
    $limit = max(1, min(50, (int) $limit));

    $sql = "SELECT * FROM products
            ORDER BY RAND()
            LIMIT ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();

    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $products;
}

public function getProductsByCategory($cat_id)
{
    $sql = "SELECT * FROM products
            WHERE product_cat = ?
            ORDER BY product_id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $cat_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $products;
}

public function getProductsByBrand($brand_id)
{
    $sql = "SELECT * FROM products
            WHERE product_brand = ?
            ORDER BY product_id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $products;
}

public function searchProducts($query)
{
    $sql = "SELECT * FROM products
            WHERE product_title LIKE ?
               OR product_keywords LIKE ?
            ORDER BY product_id DESC";

    $search = "%" . $query . "%";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();

    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $products;
}



public function addToCart($product_id, $ip_address, $customer_id)
{
    // Check whether this product is already in the cart.
    if ($customer_id === null) {
        // Guest cart: identify the item using its IP address.
        $sql = "SELECT p_id FROM cart
                WHERE p_id = ? AND ip_add = ? AND c_id IS NULL";
    } else {
        // Logged-in customer cart.
        $sql = "SELECT p_id FROM cart
                WHERE p_id = ? AND ip_add = ? AND c_id = ?";
    }

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        error_log("Cart SELECT prepare failed: " . $this->conn->error);
        return false;
    }

    if ($customer_id === null) {
        $stmt->bind_param("is", $product_id, $ip_address);
    } else {
        $stmt->bind_param("isi", $product_id, $ip_address, $customer_id);
    }

    if (!$stmt->execute()) {
        error_log("Cart SELECT failed: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();

    // If the item exists, increase its quantity.
    if ($exists) {
        if ($customer_id === null) {
            $sql = "UPDATE cart
                    SET qty = qty + 1
                    WHERE p_id = ? AND ip_add = ? AND c_id IS NULL";
        } else {
            $sql = "UPDATE cart
                    SET qty = qty + 1
                    WHERE p_id = ? AND ip_add = ? AND c_id = ?";
        }
    } else {
        // Otherwise, create a new cart item.
        if ($customer_id === null) {
            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty)
                    VALUES (?, ?, NULL, 1)";
        } else {
            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty)
                    VALUES (?, ?, ?, 1)";
        }
    }

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        error_log("Cart INSERT/UPDATE prepare failed: " . $this->conn->error);
        return false;
    }

    if ($customer_id === null) {
        $stmt->bind_param("is", $product_id, $ip_address);
    } else {
        $stmt->bind_param("isi", $product_id, $ip_address, $customer_id);
    }

    $success = $stmt->execute();

    if (!$success) {
        error_log("Cart INSERT/UPDATE failed: " . $stmt->error);
    }

    $stmt->close();

    return $success;
}

}