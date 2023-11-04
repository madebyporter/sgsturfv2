<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

get_header('Product');

$group_product = wc_get_product(get_the_ID());

if ($group_product->is_type('grouped')):
  ?>

  <section class="grid-main hero hero-product">
    <div class="hero-masonry flex gap-4 col-start-1 col-end-13 md:col-start-1 md:col-end-7">
      <div class="hero-masonry-col flex flex-col gap-4 justify-around">
        <?php
        $image_id = $product->get_image_id();
        $image_url = $image_id ? wp_get_attachment_image_src(get_post_thumbnail_id($group_product->get_id()), 'full')[0] : get_template_directory_uri() . '/assets/images/ui-state-zero-series.jpg';
        ?>
        <img src="<?php echo esc_url($image_url); ?>" alt="Product" />
      </div>
    </div>
    <div
      class="hero-content col-start-1 col-end-13 md:col-start-7 md:col-end-13 px-5 py-10 md:p-10 flex flex-col justify-between">
      <div class="product-overview grid-sub">
        <div class="product-meta col-start-1 col-end-13">
          <div class="content-heading-bordered mb-4">
            <h1 class="h1 mb-4">
              <?php echo esc_html($group_product->get_name()); ?>
            </h1>
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
            <?php echo wpautop(wp_kses_post($group_product->get_description())); ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="products" class="grid-main content-full">
    <div class="theme-black content-full-container col-start-1 col-end-13 flex flex-col gap-0">
      <div class="content-full-row grid-sub px-5 py-10 md:p-10 xl:p-20">
        <div class="content-heading col-start-1 col-end-13 mb-4 md:col-start-1 md:col-end-6 md:mb-0">
          <h2 class="h2">Products</h2>
        </div>
        <div class="col-start-1 col-end-13 md:col-start-7 md:col-end-13">
          <div class="content-paragraph md:pl-10 lg:pl-12">
            <p class="mb-4">
              Explore our
              <?php echo esc_html($group_product->get_name()); ?>:
            </p>
            <p>
              <?php echo wp_strip_all_tags($group_product->get_short_description()); ?>
            </p>
          </div>
        </div>
      </div>
      <div class="grid-sub pb-5 md:pb-10 lg:pb-20">
        <div class="slider col-start-1 col-end-13">
          <div class="slider-container mx-5 md:mx-10 xl:mx-20">
            <!-- Start Simple Product Loop inside the Group Product Loop -->
            <?php
            $subproducts = $group_product->get_children();
            if (is_array($subproducts)) {
              $subproduct_count = count($subproducts);
              $slider_noslide_class = $subproduct_count < 5 ? 'no-slide' : '';
              $blank_card_count = max(0, 4 - $subproduct_count);
            } else {
              $slider_noslide_class = '';
            }

            ?>

            <div
              class="<?php echo $slider_noslide_class; ?> pattern-card group before:hidden after:hidden flex flex-nowrap relative gap-5 justify-between content-start w-fit lg:[&.no-slide]:w-full pr-5 md:pr-10 xl:pr-20 lg:[&.no-slide]:pr-0">

              <?php
              foreach ($subproducts as $subproduct_id) {
                $subproduct = wc_get_product($subproduct_id);
                $subproduct_image = wp_get_attachment_image_src(get_post_thumbnail_id($subproduct_id), 'full');
                $subproduct_categories = wp_get_post_terms($subproduct_id, 'product_cat', array('fields' => 'names'));

                if ($subproduct) {

                  // Prepare arguments for the card component
                  $card_args = array(
                    'product_name' => $subproduct->get_name(),
                    'product_category' => $subproduct_categories,
                    'product_specs' => array(
                      'Pile Height' => get_field('specs_pile_height', $subproduct_id) ? get_field('specs_pile_height', $subproduct_id) : 'n/a',
                      'Total Weight' => get_field('specs_total_weight', $subproduct_id) ? get_field('specs_total_weight', $subproduct_id) : 'n/a',
                      'Blade' => get_field('specs_blade', $subproduct_id) ? get_field('specs_blade', $subproduct_id) : 'n/a',
                      'Turf Gauge' => get_field('specs_turf_gauge', $subproduct_id) ? get_field('specs_turf_gauge', $subproduct_id) : 'n/a',
                      'Blade_Colors' => get_field('specs_blade_colors', $subproduct_id) ? get_field('specs_blade_colors', $subproduct_id) : 'n/a',
                    ),
                    'product_photo' => $subproduct_image ? $subproduct_image[0] : SGSTURF_IMAGES_DIR . '/ui-state-zero-simpleproduct.jpg',
                    'product_link' => get_permalink($subproduct_id),
                  );


                  // Render the card component
                  card($card_args);
                } else {
                  error_log("Failed to get product for ID $subproduct_id");
                }
              }

              // Show blank cards for the remaining slots if needed
              for ($i = 0; $i < $blank_card_count; $i++) {
                echo '<div class="hidden sm:flex rounded-lg bg-graphite w-[256px] lg:group-[.no-slide]:w-full"></div>';
              }
              ?>
            </div>
          </div>
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const productCards = document.querySelectorAll('.card-product-bottom');

              productCards.forEach(function (productCard) {
                const specsButton = productCard.querySelector('.button');

                specsButton.addEventListener('click', function () {
                  productCard.classList.toggle('specs-active');
                  const buttonText = specsButton.querySelector('.button-label');

                  if (productCard.classList.contains('specs-active')) {
                    buttonText.textContent = 'Hide Specs';
                  } else {
                    buttonText.textContent = 'View Specs';
                  }
                });
              });
            });
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
          <img src="<?php echo esc_url($image_url); ?>" alt="Turf" class="w-full">
        </div>
        <?php
      } elseif (count($product_gallery_images) === 2) {
        foreach ($product_gallery_images as $image_id) {
          $image_url = wp_get_attachment_image_src($image_id, 'full')[0];
          ?>
          <div class="col-span-full md:col-span-6">
            <img src="<?php echo esc_url($image_url); ?>" alt="Turf" class="w-full">
          </div>
          <?php
        }
      }
    }
    ?>
  </section>

  <section id="requestquote" class="grid-main content-full">
    <div
      class="theme-white content-full-container px-5 py-10 md:p-10 xl:p-20 col-start-1 col-end-13 grid-sub gap-4 md:gap-8 lg:gap-4">
      <div class="col-start-1 col-end-13 lg:col-start-1 lg:col-end-6">
        <h2 class="h2">Request a Quote</h2>
      </div>
      <div class="col-start-1 col-end-13 lg:col-start-7 lg:col-end-13">
        <?php echo do_shortcode('[gravityform id="2" title="true" ajax=“true”]'); ?>
      </div>
    </div>
  </section>

<?php endif; ?>

<?php get_footer('Product'); ?>