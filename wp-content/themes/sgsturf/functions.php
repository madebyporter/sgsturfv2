<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage SGSTurf_v2
 * @since SGSTurf v2
 */

/* Stylesheets Directory */
function enqueue_theme_styles() {
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/main.css');
    wp_enqueue_style('tailwind-style', get_template_directory_uri() . '/assets/css/tailwind.css');
}
add_action('wp_enqueue_scripts', 'enqueue_theme_styles');


/* Images Directory */
define('SGSTURF_IMAGES_DIR', get_template_directory_uri() . '/assets/images');

// Register 'Main' menu location
function register_main_menu() {
    register_nav_menu('main', 'Main Menu');
}
add_action('after_setup_theme', 'register_main_menu');

// Declare WooCommerce Support
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

// Disable Woocommerce CSS
// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Activate Woocommerce Product Gallery
add_action( 'after_setup_theme', function() {
    add_theme_support( 'wc-product-gallery-slider' );
} );

// Disable Checkout
add_filter( 'woocommerce_is_purchasable', '__return_false' );


