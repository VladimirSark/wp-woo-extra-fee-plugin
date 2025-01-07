<?php
// filepath: includes/class-extra-fee.php

class ExtraFee {
    private $collection_fee_product_id;

    public function __construct($collection_fee_product_id) {
        $this->collection_fee_product_id = $collection_fee_product_id;
        add_action('woocommerce_before_calculate_totals', array($this, 'add_collection_fee_product_to_cart'));
    }

    public function add_collection_fee_product_to_cart($cart) {
        $shipping_classes = array();
        foreach ($cart->get_cart() as $cart_item) {
            $product = $cart_item['data'];
            $shipping_class = $product->get_shipping_class();
            if (!empty($shipping_class) && !in_array($shipping_class, $shipping_classes)) {
                $shipping_classes[] = $shipping_class;
            }
        }

        $num_shipping_classes = count($shipping_classes);
        $num_fees_to_add = max(0, $num_shipping_classes - 1);

        // Remove existing collection fee products
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if ($cart_item['product_id'] == $this->collection_fee_product_id) {
                $cart->remove_cart_item($cart_item_key);
            }
        }

        // Add the required number of collection fee products
        for ($i = 0; $i < $num_fees_to_add; $i++) {
            $cart->add_to_cart($this->collection_fee_product_id);
        }
    }
}

// Initialize the ExtraFee class with the Collection fee product ID
$collection_fee_product_id = 34666; // Replace with your Collection fee product ID
new ExtraFee($collection_fee_product_id);