<?php
class ExtraFee {
    public function __construct() {
        add_action('woocommerce_cart_calculate_fees', array($this, 'add_extra_fee_to_cart'));
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
}