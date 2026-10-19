
<?php

require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartClass();
    }

    public function isInCart($product_id, $ip, $customer_id = 0)
    {
        return $this->cart->isInCart($product_id, $ip, $customer_id);
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = 0)
    {
        return $this->cart->addToCart($product_id, $qty, $ip, $customer_id);
    }

    public function getCartItems($ip, $customer_id = 0)
    {
        return $this->cart->getCartItems($ip, $customer_id);
    }

    public function getCartCount($ip, $customer_id = 0)
    {
        return $this->cart->getCartCount($ip, $customer_id);
    }

    public function getCartTotal($ip, $customer_id = 0)
    {
        return $this->cart->getCartTotal($ip, $customer_id);
    }

    public function removeFromCart($product_id, $ip, $customer_id = 0)
    {
        return $this->cart->removeFromCart($product_id, $ip, $customer_id);
    }

    public function updateQty($product_id, $new_qty, $ip, $customer_id = 0)
    {
        return $this->cart->updateQty($product_id, $new_qty, $ip, $customer_id);
    }
}
