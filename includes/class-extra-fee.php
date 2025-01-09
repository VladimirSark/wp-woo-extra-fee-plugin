<?php
// filepath: includes/class-extra-fee.php

class ExtraFee {
    private $collection_fee_amount;
    private $collection_fee_name;
    private $tax_rate;

    public function __construct($collection_fee_amount, $collection_fee_name, $tax_rate) {
        $this->collection_fee_amount = $collection_fee_amount;
        $this->collection_fee_name = $collection_fee_name;
        $this->tax_rate = $tax_rate;
        add_action('woocommerce_cart_calculate_fees', array($this, 'add_collection_fee'));
    }

    public function add_collection_fee() {
        global $woocommerce;

        $shipping_classes = array();
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $shipping_class_id = get_the_terms($product_id, 'product_shipping_class');
            if ($shipping_class_id && !is_wp_error($shipping_class_id)) {
                $shipping_classes[] = $shipping_class_id[0]->term_id;
            }
        }

        // Remove duplicate shipping classes
        $shipping_classes = array_unique($shipping_classes);

        // Check if there are more than one shipping class
        $num_shipping_classes = count($shipping_classes);
        if ($num_shipping_classes > 1) {
            $num_fees_to_add = $num_shipping_classes - 1;
            $total_fee = $num_fees_to_add * $this->collection_fee_amount;
            $total_tax = $total_fee * $this->tax_rate;
            $total_amount = $total_fee + $total_tax;
            $woocommerce->cart->add_fee($this->collection_fee_name, $total_amount, true);
        }
    }
}

// Initialize the ExtraFee class with the Collection fee amount, name, and tax rate
$collection_fee_amount = 0.82; // Base fee amount
$collection_fee_name = 'Siuntimas iš skirtingų sandėlių'; // Fee name
$tax_rate = 0.21; // Tax rate (21%)
new ExtraFee($collection_fee_amount, $collection_fee_name, $tax_rate);