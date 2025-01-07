<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Uninstall logic for the WP Woo Extra Fee Plugin
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove options or data created by the plugin
delete_option( 'extra_fee_option_name' ); // Replace with actual option names used in your plugin
// Add any additional cleanup code as necessary
?>