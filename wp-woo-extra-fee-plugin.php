<?php
/*
Plugin Name: WooCommerce Extra Fee for Different Shipping Classes
Description: Adds an extra fee if a customer adds products with different shipping classes to the cart.
Version: 1.0
Author: Your Name
*/

// Include the ExtraFee class
require_once plugin_dir_path(__FILE__) . 'includes/class-extra-fee.php';

// Initialize the ExtraFee class with the Collection fee amount, name, and tax rate
$collection_fee_amount = 0.82; // Base fee amount
$collection_fee_name = __('Siuntimas iš skirtingų sandėlių', 'wp-woo-extra-fee-plugin'); // Fee name
$tax_rate = 0.21; // Tax rate (21%)
new ExtraFee($collection_fee_amount, $collection_fee_name, $tax_rate);