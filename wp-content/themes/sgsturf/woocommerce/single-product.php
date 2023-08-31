<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'Product' );

$group_product = wc_get_product( get_the_ID() );

if ( $group_product->is_type( 'grouped' ) ) :
?>

<section class="grid-main hero hero-product">
  <div class="hero-masonry flex gap-4 col-start-1 col-end-7">
    <div class="hero-masonry-col flex flex-col gap-4 justify-around">
      <img src="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id($group_product->get_id()), 'full')[0]; ?>" alt="<?php echo esc_attr($group_product->get_name()); ?>">
    </div>
  </div>
  <div class="hero-content col-start-7 col-end-13 p-10 flex flex-col justify-between">
    <div class="product-overview grid-sub">
      <div class="product-meta col-start-1 col-end-13">
        <div class="content-heading-bordered mb-4">
          <h1 class="h1 mb-4"><?php echo esc_html($group_product->get_name()); ?></h1>
        </div>
        <div class="pattern-tag flex gap-2 mb-8">
          <?php
            $product_categories = get_the_terms($group_product->get_id(), 'product_cat');
            if ($product_categories && !is_wp_error($product_categories)) {
              foreach ($product_categories as $category) {
                echo '<div class="tag">' . esc_html($category->name) . '</div>';
              }
            }
          ?>
        </div>
      </div>
      <div class="product-desription col-start-1 col-end-12 mb-8">
        <div class="content-paragraph-small">
          <?php echo wpautop( wp_kses_post( $group_product->get_description() ) ); ?>
        </div>
      </div>
    </div>
    <div class="product-cta">
      <a href="#products" class="button button-primary">
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

<section id="products" class="grid-main content-full">
  <div class="theme-black content-full-container col-start-1 col-end-13 flex flex-col gap-0">
    <div class="content-full-row grid-sub md:p-10 lg:p-20">
      <div class="content-heading col-start-1 col-end-6">
        <h2 class="h2">Products</h2>
      </div>
      <div class="content-paragraph col-start-8 col-end-13">
        <p class="mb-4">
          Explore our <?php echo esc_html($group_product->get_name()); ?>:
        </p>
        <p>
          <?php echo wp_strip_all_tags( $group_product->get_short_description() ); ?>
        </p>
      </div>
    </div>
    <div class="content-full-row grid-sub md:py-10 lg:py-20">
      <div class="slider col-start-1 col-end-13">
        <div class="slider-container md:mx-10 lg:mx-20">
          <div class="pattern-card flex gap-8 justify-start content-start">
            <?php
            $subproducts = $group_product->get_children();
            foreach ($subproducts as $subproduct_id) {
              $subproduct = wc_get_product($subproduct_id);
              $subproduct_image = wp_get_attachment_image_src(get_post_thumbnail_id($subproduct_id), 'thumbnail');
              $subproduct_categories = wp_get_post_terms($subproduct_id, 'product_cat', array('fields' => 'names'));

              if ($subproduct_categories && !is_wp_error($subproduct_categories)) {
                ?>
                <div class="card card-product">
                  <div class="card-product-top">
                    <h3 class="h3 mb-2"><?php echo esc_html($subproduct->get_name()); ?></h3>
                    <div class="pattern-tag flex gap-2">
                      <?php foreach ($subproduct_categories as $category_name) : ?>
                        <div class="tag"><?php echo esc_html($category_name); ?></div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <div class="card-product-bottom">
                    <div class="card-product-bottom-container">
                      <img src="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id($subproduct_id), 'full')[0]; ?>" alt="Product" />
                    </div>
                  </div>
                </div>
                <?php
              }
            }
            ?>
          </div>
        </div>
        <script>
          // Function to calculate and update slider width
          function updateSliderWidth() {
            const cardWidth = document.querySelector('.card-product').offsetWidth;
            const gap = parseFloat(getComputedStyle(document.querySelector('.pattern-card')).gap);
            const cardCount = document.querySelectorAll('.card-product').length;
            const totalWidth = (cardWidth * cardCount) + (gap * (cardCount - 1));

            document.querySelector('.slider-container').style.width = totalWidth + 'px';
          }

          // Initial call to update slider width
          updateSliderWidth();

          // Event listener for window resize
          window.addEventListener('resize', updateSliderWidth);
        </script>
      </div>
    </div>
  </div>
</section>

<section class="grid-main photo-gallery content-full">
  <?php
  $product_gallery_images = $group_product->get_gallery_image_ids();

  if ($product_gallery_images) {
    if (count($product_gallery_images) === 1) {
      $image_url = wp_get_attachment_image_src($product_gallery_images[0], 'full')[0];
      ?>
      <div class="col-span-full">
        <img src="<?php echo esc_url($image_url); ?>" alt="Turf">
      </div>
      <?php
    } elseif (count($product_gallery_images) === 2) {
      foreach ($product_gallery_images as $image_id) {
        $image_url = wp_get_attachment_image_src($image_id, 'full')[0];
        ?>
        <div class="col-span-6">
          <img src="<?php echo esc_url($image_url); ?>" alt="Turf">
        </div>
        <?php
      }
    }
  }
  ?>
</section>

<?php endif; ?>

<?php get_footer( 'Product' ); ?>
