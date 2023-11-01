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

/* Title */
function theme_slug_setup()
{
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'theme_slug_setup');

/* Stylesheets Directory */
function enqueue_theme_styles()
{
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/main.css');
    wp_enqueue_style('tailwind-style', get_template_directory_uri() . '/assets/css/tailwind.css');
}
add_action('wp_enqueue_scripts', 'enqueue_theme_styles');

/* Font Awesome */
function enqueue_font_awesome()
{
    wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/e03c37678b.js', array(), null);
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');


/* Images Directory */
define('SGSTURF_IMAGES_DIR', get_template_directory_uri() . '/assets/images');

// Register 'Main' menu location
function register_main_menu()
{
    register_nav_menu('main', 'Main Menu');
}
add_action('after_setup_theme', 'register_main_menu');

// Declare WooCommerce Support
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

// Disable Woocommerce CSS
// Remove each style one by one
add_filter('woocommerce_enqueue_styles', 'jk_dequeue_styles');
function jk_dequeue_styles($enqueue_styles)
{
    unset($enqueue_styles['woocommerce-general']); // Remove the gloss
    // unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
    // unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
    return $enqueue_styles;
}
// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Activate Woocommerce Product Gallery
add_action('after_setup_theme', function () {
    add_theme_support('wc-product-gallery-slider');
});

// Disable Checkout
add_filter('woocommerce_is_purchasable', '__return_false');

// Disable Prices
add_filter('woocommerce_get_price_html', 'remove_price');
function remove_price($price)
{
    return;
}

// Disable Product Sidebar
function disable_woocommerce_sidebar()
{
    if (is_product()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}
add_action('template_redirect', 'disable_woocommerce_sidebar');

// Sort Series Products ABC Order
add_filter('woocommerce_grouped_children_args', 'kia_grouped_children_args');
function kia_grouped_children_args($args)
{
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
    return $args;
}

class My_Custom_Walker extends Walker_Nav_Menu
{

    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $classes = array(
            'sub-menu',
            'lg:group-hover:flex',
        );
        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $output .= "\n$indent<ul$class_names>\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $output .= $indent . '<li' . $class_names . '>';

        // Add the link and menu item text
        $attributes = ' href="' . esc_attr($item->url) . '"';
        $title = apply_filters('the_title', $item->title, $item->ID);
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}

// Add to wp_nav_menu in your theme
add_action('wp_nav_menu_args', function ($args) {
    $args['walker'] = new My_Custom_Walker();
    return $args;
});