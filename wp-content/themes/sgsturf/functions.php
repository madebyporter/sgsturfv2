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

@ini_set('upload_max_size', '130M');
@ini_set('post_max_size', '200M');
@ini_set('max_execution_time', '300');

/** Component Imports */
include get_template_directory() . '/components/card-product.php';

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

/** Register 'Main' menu location **/
function register_main_menu()
{
  register_nav_menu('main', 'Main Menu');
}
add_action('after_setup_theme', 'register_main_menu');

/** Register Footer Navigation location **/
function register_my_menus()
{
  register_nav_menus(
    array(
      'footer-menu' => __('Footer Navigation'),
      'social-media-menu' => __('Social Media Navigation')
    )
  );
}
add_action('init', 'register_my_menus');

function custom_nav_menu_item_output($item_output, $item, $depth, $args)
{
  if ($args->theme_location == 'social-media-menu') {
    return '<a href="' . $item->url . '" target="_blank" class="font-sans">' . $item->title . '</a>';
  }
  return $item_output;
}
add_filter('walker_nav_menu_start_el', 'custom_nav_menu_item_output', 10, 4);

function add_additional_class_on_li($classes, $item, $args)
{
  if ($args->theme_location == 'social-media-menu') {
    $classes[] = '!flex flex-row gap-2 before:w-[16px] before:flex before:items-center before:justify-center !leading-6';
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);



// Add Body Class
function add_custom_body_class($classes)
{
  // Add 'bg-green-pale' to the $classes array
  $classes[] = 'bg-green-pale transition-colors';
  // Return the array
  return $classes;
}
add_filter('body_class', 'add_custom_body_class');


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

// Main Menu
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

// Mobile Menu
function theme_enqueue_scripts()
{
  wp_enqueue_script('mobile-menu', get_template_directory_uri() . '/assets/js/mobile-menu.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');


// Register Widgets
function my_widgets_init()
{
  register_sidebar(
    array(
      'name' => 'Footer Contact Us',
      'id' => 'footer_contact_us',
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h4 class="widget-title mb-4">',
      'after_title' => '</h4>',
    )
  );

  register_sidebar(
    array(
      'name' => 'Footer Location 1',
      'id' => 'footer_location_1',
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h4 class="widget-title mb-4">',
      'after_title' => '</h2>',
    )
  );

  register_sidebar(
    array(
      'name' => 'Footer Location 2',
      'id' => 'footer_location_2',
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h4 class="widget-titl mb-4">',
      'after_title' => '</h4>',
    )
  );
}
add_action('widgets_init', 'my_widgets_init');

//** Order Form Dynamic Products */
// Function to fetch and sort products
function get_products_with_exclusion($exclude_category_slug = '') {
    $args = array(
        'status' => 'publish',
        'type'   => 'simple',
        'limit'  => -1, // Fetch all products
    );

    $products = wc_get_products($args);

    // Manually exclude products from a specific category if provided
    if (!empty($exclude_category_slug)) {
        $products = array_filter($products, function($product) use ($exclude_category_slug) {
            $product_categories = $product->get_category_ids();
            $excluded_category = get_term_by('slug', $exclude_category_slug, 'product_cat');
            return !in_array($excluded_category->term_id, $product_categories);
        });
    }

    usort($products, function($a, $b) {
        return strcmp($a->get_name(), $b->get_name());
    });

    $choices = array();
    foreach ($products as $product) {
        $choices[] = array('text' => $product->get_name(), 'value' => $product->get_id());
    }

    return $choices;
}

function get_products_by_category($category_slug) {
    $args = array(
        'status' => 'publish',
        'type'   => 'simple',
        'limit'  => -1, // Fetch all products
        'category' => array($category_slug), // Filter by category
    );

    $products = wc_get_products($args);

    usort($products, function($a, $b) {
        return strcmp($a->get_name(), $b->get_name());
    });

    $choices = array();
    foreach ($products as $product) {
        $choices[] = array('text' => $product->get_name(), 'value' => $product->get_id());
    }

    return $choices;
}

// Modify the populate_products_dropdown function
add_filter('gform_pre_render', 'populate_products_dropdown');
add_filter('gform_pre_validation', 'populate_products_dropdown');
add_filter('gform_pre_submission_filter', 'populate_products_dropdown');
add_filter('gform_admin_pre_render', 'populate_products_dropdown');
function populate_products_dropdown($form) {

    // Only populate for your specific form ID
    if ($form['id'] != 1) { // Replace 1 with your form ID
        return $form;
    }

    // Fetch choices for all simple products excluding infill and for infill category products
    $simple_choices = get_products_with_exclusion('infills'); // Fetch all simple products excluding infill
    $infill_choices = get_products_by_category('infills'); // Fetch only infill category products

    foreach ($form['fields'] as &$field) {
        // Populate fields for simple products
        if (in_array($field->id, array(6, 8, 10))) {
            $field->choices = $simple_choices;
        }
        // Populate fields for infill products
        elseif (in_array($field->id, array(16, 19, 21))) {
            $field->choices = $infill_choices;
        }
    }

    return $form;
}
