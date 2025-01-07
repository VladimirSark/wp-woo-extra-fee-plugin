<?php
/*
Plugin Name: WooCommerce Extra Fee for Different Shipping Classes
Description: Adds an extra fee if a customer adds products with different shipping classes to the cart.
Version: 1.0
Author: Your Name
*/

// Hook into WooCommerce to add the extra fee
add_action('woocommerce_cart_calculate_fees', 'add_extra_fee_for_different_shipping_classes');

function add_extra_fee_for_different_shipping_classes() {
    global $woocommerce;

    $shipping_classes = array();
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product_id = $cart_item['product_id'];
        $shipping_class_id = get_the_terms( $product_id, 'product_shipping_class' );
        if ( $shipping_class_id && ! is_wp_error( $shipping_class_id ) ) {
            $shipping_classes[] = $shipping_class_id[0]->term_id;
        }
    }

    // Remove duplicate shipping classes
    $shipping_classes = array_unique($shipping_classes);

    // Check if there are more than one shipping class
    if ( count( $shipping_classes ) > 1 ) {
        $extra_fee = 10; // Set your extra fee amount here
        $woocommerce->cart->add_fee( 'Extra Fee for Different Shipping Classes', $extra_fee );
    }
}
?>