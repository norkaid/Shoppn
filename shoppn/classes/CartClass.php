
<?php

require_once __DIR__ . '/../core/db_class.php';

if (!class_exists('CartClass', false)) {

class CartClass extends Database
{
    public function isInCart($product_id, $ip, $customer_id = 0)
    {
        if ($customer_id > 0) {
            $sql = "SELECT p_id FROM cart
                    WHERE p_id = ? AND c_id = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("ii", $product_id, $customer_id);
        } else {
            $sql = "SELECT p_id FROM cart
                    WHERE p_id = ? AND ip_add = ?
                    AND (c_id IS NULL OR c_id = 0)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("is", $product_id, $ip);
        }

        if (!$stmt->execute()) {
            error_log($stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = 0)
    {
        $product_id = filter_var($product_id, FILTER_VALIDATE_INT);
        $qty = filter_var($qty, FILTER_VALIDATE_INT);

        if ($product_id === false || $product_id < 1 ||
            $qty === false || $qty < 1) {
            return false;
        }

        if ($customer_id > 0) {
            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty)
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("isii", $product_id, $ip, $customer_id, $qty);
        } else {
            $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty)
                    VALUES (?, ?, NULL, ?)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("isi", $product_id, $ip, $qty);
        }

        $success = $stmt->execute();

        if (!$success) {
            error_log($stmt->error);
        }

        $stmt->close();
        return $success;
    }

    public function getCartItems($ip, $customer_id = 0)
    {
        $sql = "SELECT cart.p_id, cart.ip_add, cart.c_id, cart.qty,
                       products.product_title, products.product_price,
                       products.product_image
                FROM cart
                INNER JOIN products
                    ON cart.p_id = products.product_id";

        if ($customer_id > 0) {
            $sql .= " WHERE cart.c_id = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return [];
            }

            $stmt->bind_param("i", $customer_id);
        } else {
            $sql .= " WHERE cart.ip_add = ?
                      AND (cart.c_id IS NULL OR cart.c_id = 0)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return [];
            }

            $stmt->bind_param("s", $ip);
        }

        if (!$stmt->execute()) {
            error_log($stmt->error);
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $items;
    }

    public function getCartCount($ip, $customer_id = 0)
    {
        if ($customer_id > 0) {
            $sql = "SELECT COUNT(*) AS total FROM cart WHERE c_id = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return 0;
            }

            $stmt->bind_param("i", $customer_id);
        } else {
            $sql = "SELECT COUNT(*) AS total FROM cart
                    WHERE ip_add = ?
                    AND (c_id IS NULL OR c_id = 0)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return 0;
            }

            $stmt->bind_param("s", $ip);
        }

        if (!$stmt->execute()) {
            error_log($stmt->error);
            $stmt->close();
            return 0;
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return (int) ($row['total'] ?? 0);
    }

    public function getCartTotal($ip, $customer_id = 0)
    {
        $items = $this->getCartItems($ip, $customer_id);
        $total = 0;

        foreach ($items as $item) {
            $total += (float) $item['product_price'] * (int) $item['qty'];
        }

        return $total;
    }

    public function removeFromCart($product_id, $ip, $customer_id = 0)
    {
        if ($customer_id > 0) {
            $sql = "DELETE FROM cart WHERE p_id = ? AND c_id = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("ii", $product_id, $customer_id);
        } else {
            $sql = "DELETE FROM cart
                    WHERE p_id = ? AND ip_add = ?
                    AND (c_id IS NULL OR c_id = 0)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("is", $product_id, $ip);
        }

        $success = $stmt->execute();

        if (!$success) {
            error_log($stmt->error);
        }

        $stmt->close();
        return $success;
    }

    public function updateQty($product_id, $new_qty, $ip, $customer_id = 0)
    {
        $product_id = filter_var($product_id, FILTER_VALIDATE_INT);
        $new_qty = filter_var($new_qty, FILTER_VALIDATE_INT);

        if ($product_id === false || $product_id < 1 ||
            $new_qty === false || $new_qty < 1) {
            return false;
        }

        if ($customer_id > 0) {
            $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND c_id = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("iii", $new_qty, $product_id, $customer_id);
        } else {
            $sql = "UPDATE cart SET qty = ?
                    WHERE p_id = ? AND ip_add = ?
                    AND (c_id IS NULL OR c_id = 0)";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                error_log($this->conn->error);
                return false;
            }

            $stmt->bind_param("iis", $new_qty, $product_id, $ip);
        }

        $success = $stmt->execute();

        if (!$success) {
            error_log($stmt->error);
        }

        $stmt->close();
        return $success;
    }
}

}
