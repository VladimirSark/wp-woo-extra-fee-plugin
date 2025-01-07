<?php
class ExtraFee {
    private $collection_fee_product_id;

    public function __construct($collection_fee_product_id) {
        $this->collection_fee_product_id = $collection_fee_product_id;
        add_action('woocommerce_cart_calculate_fees', array($this, 'add_extra_fee_to_cart'));
        add_action('woocommerce_before_calculate_totals', array($this, 'add_collection_fee_product_to_cart'));
    }

    public function calculate_extra_fee() {
        $shipping_classes = array();
        $cart = WC()->cart->get_cart();

        foreach ($cart as $cart_item) {
            $product = $cart_item['data'];
            $shipping_class = $product->get_shipping_class();
            if (!empty($shipping_class) && !in_array($shipping_class, $shipping_classes)) {
                $shipping_classes[] = $shipping_class;
            }
        }

        if (count($shipping_classes) > 1) {
            return 10; // Example extra fee amount
        }

        return 0;
    }

    public function add_extra_fee_to_cart() {
        $extra_fee = $this->calculate_extra_fee();
        if ($extra_fee > 0) {
            WC()->cart->add_fee(__('Extra Fee', 'wp-woo-extra-fee-plugin'), $extra_fee);
        }
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

        if (count($shipping_classes) > 1) {
            $found = false;
            foreach ($cart->get_cart() as $cart_item) {
                if ($cart_item['product_id'] == $this->collection_fee_product_id) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cart->add_to_cart($this->collection_fee_product_id);
            }
        }
    }
}

// Initialize the ExtraFee class with the Collection fee product ID
$collection_fee_product_id = 34666; // Replace with your Collection fee product ID
new ExtraFee($collection_fee_product_id);