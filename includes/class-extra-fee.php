<?php
class ExtraFee {
    private $collection_fee_amount;
    private $collection_fee_name;

    public function __construct($collection_fee_amount, $collection_fee_name) {
        $this->collection_fee_amount = $collection_fee_amount;
        $this->collection_fee_name = $collection_fee_name;
        add_action('woocommerce_cart_calculate_fees', array($this, 'add_collection_fee'));
        add_action('woocommerce_single_product_summary', array($this, 'show_shipping_class_on_product_page'), 30); // Adjusted priority to 30
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
            $woocommerce->cart->add_fee($this->collection_fee_name, $total_fee, true); // true makes the fee taxable
        }
    }

    public function show_shipping_class_on_product_page() {
        global $product;

        $shipping_class_id = $product->get_shipping_class_id();

        if ($shipping_class_id > 0) {
            $shipping_class = get_term($shipping_class_id, 'product_shipping_class');
            $shipping_class_name = $shipping_class->name;
            echo '<p class="shipping-class">Sandėliuojama: ' . $shipping_class_name . '</p>';
        }
    }
}

// Initialize the ExtraFee class with the Collection fee amount and name
$collection_fee_amount = 0.82; // Set your collection fee amount here
$collection_fee_name = 'Siuntimas iš skirtingų sandėlių'; // Set your collection fee name here
new ExtraFee($collection_fee_amount, $collection_fee_name);