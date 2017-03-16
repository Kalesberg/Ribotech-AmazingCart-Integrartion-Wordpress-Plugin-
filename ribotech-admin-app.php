<?php
/*
Plugin Name: Ribotech Admin App
Description: Make your app using Wordpress to iOS.
Version: 1.1
Author: Ribotech.net
*/

define( 'AMAZING_CART_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('PLUGIN_DIR', plugin_dir_url(__FILE__));

include "class/amazingcart.class.php"; //Main Class
include "class/amazingcart-sqltable-activation.php";
include "class/amazingcart-woo-json-api.php"; // Plugin Class
include "class/amazingcart-qrcodegenerator.class.php"; // Plugin Class
include "class/amazingcart-menu-api.php";
include "class/amazingcart-wp-json-api.php";
include "class/amazingcart-admin-meta-box.php";
include "class/amazingcart-setting.php";
include "class/amazingcart-status.php";
include "class/amazingcart-analytics.php";
include "class/amazingcart-pushnotification.class.php";
include "class/amazingcart-admin-home-appearance.php";
include "class/amazingcart-categories-appearance.php";
include "class/amazingcart-payment-gateway-meta.php";
include "class/amazingcart-shortcode.php";
$amazingcart = new AmazingCart();

register_activation_hook( __FILE__, array( $amazingcart, 'activation' ) );

$amazingcart->load();

?>