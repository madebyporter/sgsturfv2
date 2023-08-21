<?php 

/**
 * Template Name: Product Detail Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<!--Group Product Loop Start-->

<?php
// Query WooCommerce group products
$args = array(
  'post_type' => 'product',
  'post_status' => 'publish',
  'posts_per_page' => -1, // Show all products
  'product_type' => 'grouped' // Only show grouped products
);
$query = new WP_Query($args);

if ($query->have_posts()) {
  while ($query->have_posts()) {
    $query->the_post();

    // Get product data
    $group_product = wc_get_product(get_the_ID());

    if ($group_product->is_type('grouped')) {
      ?>

      <section class="grid-main hero hero-product">
        <div class="hero-masonry flex gap-4 col-start-1 col-end-7">
          <div class="hero-masonry-col flex flex-col gap-4 justify-around">
            <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-product-detail-main.jpg" alt="Turf">
          </div>
        </div>
        <div class="hero-content col-start-7 col-end-13 p-10 flex flex-col justify-between">
          <div class="product-overview grid-sub">
            <div class="product-meta col-start-1 col-end-13">
              <div class="content-heading-bordered mb-4">
                <h1 class="h1 mb-4"><?php echo esc_html($group_product->get_name()); ?></h1>
              </div>
              <div class="pattern-tag flex gap-2 mb-8">
                <div class="tag">Landscape</div>
                <div class="tag">Pet</div>
              </div>
            </div>
            <div class="product-desription col-start-1 col-end-12 mb-8">
              <div class="content-paragraph-small">
                <p>The Sage line of turf takes a new approach at creating a “not-so-perfect” perfect lawn.</p>
                <p>Using elements and components from various iterations of well-received synthetic turf, applying some not so conventional manufacturing process, and stitching together a new look that exemplifies the most natural color & blade structure we’ve constructed to date.</p>
              </div>
            </div>
          </div>
          <div class="product-cta">
            <a href="#" class="button button-primary">
              <span class="button-label">View Products</span>
              <span class="button-arrow">
                <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z" fill="#242423"/>
                </svg>
              </span>
            </a>
          </div>
        </div>
      </section>

      <section class="grid-main content-full">
        <div class="theme-black content-full-container col-start-1 col-end-13 flex flex-col gap-16 pb-20">
          <div class="content-full-row grid-sub p-20 pb-0">
            <div class="content-heading col-start-1 col-end-6">
              <h2 class="h2">Products</h2>
            </div>
            <div class="content-paragraph col-start-8 col-end-13">
              <p class="mb-4">
                Explore our <?php echo esc_html($group_product->get_name()); ?>:
              </p>
              <p class="mb-8">
                Beautiful, realistic turf for the Southern California landscape.
              </p>
            </div>
          </div>
          <div class="content-full-row grid-sub">
            <div class="slider col-start-1 col-end-13">
              <div class="slider-container pl-20">
                <div class="pattern-card mr-20 flex gap-8 justify-start content-start">
                  <!--Group Sub Product Loop Start-->
                  <?php
                  $subproducts = $group_product->get_children();
                  foreach ($subproducts as $subproduct_id) {
                    $subproduct = wc_get_product($subproduct_id);
                    $subproduct_image = wp_get_attachment_image_src(get_post_thumbnail_id($subproduct_id), 'thumbnail');
                    ?>
                    <div class="card card-product">
                      <div class="card-product-top">
                        <h3 class="h3 mb-2"><?php echo esc_html($subproduct->get_name()); ?></h3>
                        <div class="pattern-tag flex gap-2 mb-8">
                          <div class="tag">Landscape</div>
                        </div>
                        <!--Group Sub Product Add to Cart-->
                        <?php
                          // WooCommerce Add to Cart Form
                          echo '<div class="woocommerce">';
                          echo apply_filters('woocommerce_loop_add_to_cart_link',
                              sprintf('<form class="cart" method="post" enctype="multipart/form-data">
                                          <input type="hidden" name="add-to-cart" value="%s">
                                          <input type="hidden" name="product_id" value="%s">
                                          <input type="hidden" name="quantity" value="%s">
                                          <button type="submit" class="%s" %s>%s</button>
                                      </form>',
                                  esc_attr($subproduct->get_id()), // Product ID
                                  esc_attr($subproduct->get_id()), // Product ID
                                  esc_attr(isset($quantity) ? $quantity : 1),
                                  esc_attr(isset($class) ? $class : 'button product_type_' . $subproduct->get_type()),
                                  isset($attributes) ? wc_implode_html_attributes($attributes) : '',
                                  esc_html($subproduct->add_to_cart_text())
                              ),
                              $subproduct,
                              $args
                          );
                          echo '</div>';
                          ?>

                      </div>
                      <div class="card-product-bottom">
                        <div class="card-product-bottom-container">
                          <img src="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id($subproduct_id), 'full')[0]; ?>" alt="Product" />
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                  ?>
                  <!--Group Sub Product Loop End-->
                </div>
              </div>
              <!-- ... Your existing JavaScript for slider initialization ... -->
            </div>
          </div>
        </div>
      </section>

      <section class="grid-main photo-gallery content-full">
        <div class="col-start-1 col-end-7">
          <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-product-gallery-p1.jpg" alt="Turf">
        </div>
        <div class="col-start-7 col-end-13">
          <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-product-gallery-p2.jpg" alt="Turf">
        </div>
      </section>

      <?php
    }
  }

  // Restore the global $post variable
  wp_reset_postdata();
}

?>

<!--Group Product Loop Ends-->

<?php get_footer(); ?>
