<?php

/**
 * Template Name: Locations Template
 */

get_header(); ?>

<section class="grid-main hero hero-locations md:min-h-[60vh]">
  <div
    class="hero-content col-start-1 col-end-13 order-2 md:col-start-1 md:col-end-7 md:order-1 lg:col-start-1 lg:col-end-7 px-5 py-10 md:p-10 xl:p-20 grid-sub content-end">
    <div class="content-heading-cta col-start-1 col-end-13">
      <?php
      // Debug: Check if 'locations' field group is returning anything
      $locations_hero_h1 = get_field('hero_h1');
      $locations_hero_p = get_field('hero_p');
      ?>
      <h1 class="h1 mb-2 lg:mb-4">
        <?php echo $locations_hero_h1 ?>
      </h1>
      <p>
        <?php echo $locations_hero_p ?>
      </p>
    </div>
  </div>
  <div
    class="hero-masonry flex gap-4 col-start-1 col-end-13 order-1 md:col-start-7 md:col-end-13 md:order-2 lg:col-start-7 lg:col-end-13">
    <?php
    // Debug: Check if 'locations' field group is returning anything
    $locations_hero_image_1 = get_field('hero_image_1');
    ?>
    <div class="hero-masonry-col grow flex-col gap-4 justify-between h-[30vh] md:h-auto">
      <img src="<?php echo $locations_hero_image_1 ?>" alt="Turf" class="h-full w-full">
    </div>
  </div>
</section>

<section id="products" class="grid-main content-full">
  <?php
  // Debug: Check if 'locations' field group is returning anything
  $locations_featured_lines_h1 = get_field('featured_lines_h1');
  $locations_featured_lines_p = get_field('featured_lines_p');
  $locations_featured_lines_lines = get_field('featured_lines_collection');
  ?>
  <div class="theme-black content-full-container col-start-1 col-end-13 flex flex-col gap-0">
    <div class="content-full-row grid-sub px-5 py-10 md:p-10 xl:p-20">
      <div class="content-heading col-start-1 col-end-13 mb-2 md:mb-0 md:col-start-1 md:col-end-6">
        <h2 class="h2">
          <?php echo $locations_featured_lines_h1 ?>
        </h2>
      </div>
      <div class="col-start-1 col-end-13 md:col-start-7 md:col-end-13">
        <div class="content-paragraph md:pl-10 xl:pl-20">
          <p class="mb-4">
            <?php echo $locations_featured_lines_p ?>
          </p>
        </div>
      </div>
    </div>
    <div class="content-full-row grid-sub pb-5 md:pb-10 xl:pb-20">
      <div class="slider col-start-1 col-end-13">
        <div class="slider-container ml-5 md:mx-10 xl:mx-20">
          <div class="pattern-card flex gap-5 justify-start content-start">
            <!-- Start displaying a specific grouped product -->
            <?php
            $specific_grouped_product_id = $locations_featured_lines_lines; // Replace with the ID of your grouped product
            $grouped_product = wc_get_product($specific_grouped_product_id);

            $subproduct_count = 0; // Initialize counter for child products
            
            // Check if the product is a grouped product
            if ($grouped_product && $grouped_product->is_type('grouped')):
              $children_ids = $grouped_product->get_children();

              if (!empty($children_ids)):
                $subproduct_count = count($children_ids); // Count the number of child products
            
                // Loop through the sub-products (children) of the grouped product
                foreach ($children_ids as $child_id):
                  $child_product = wc_get_product($child_id);
                  $child_categories = wp_get_post_terms($child_id, 'product_cat', array('fields' => 'names'));
                  ?>
                  <div
                    class="card-product bg-white rounded-lg flex flex-col justify-start overflow-hidden grow min-w-[246px]">
                    <div class="px-5 py-10 lg:px-5 lg:py-10">
                      <h3 class="h3 mb-2 text-black">
                        <?php echo esc_html($child_product->get_name()); ?>
                      </h3>
                      <div class="pattern-tag flex gap-2 mb-4 md:mb-8">
                        <?php foreach ($child_categories as $child_category): ?>
                          <div class="tag text-black">
                            <?php echo esc_html(wp_strip_all_tags($child_category)); ?>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      <a href="<?php echo esc_url(get_permalink($child_id)); ?>" class="button button-secondary button-small">
                        <span class="button-label">View Product</span>
                        <span class="button-arrow">
                          <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
                              fill="#242423" />
                          </svg>
                        </span>
                      </a>
                    </div>
                    <div class="bg-white rounded-lg h-full min-h-[256px] relative overflow-hidden">
                      <div class="top-0 bottom-0 overflow-hidden absolute transition-all w-full translate-y-0">
                        <?php
                        $image_id = $child_product->get_image_id();
                        $image_url = $image_id ? wp_get_attachment_image_src($image_id, 'full')[0] : SGSTURF_IMAGES_DIR . '/ui-state-zero-simpleproduct.jpg';
                        ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="Product"
                          class="h-full w-full object-cover object-center" />
                      </div>
                    </div>
                  </div>
                <?php endforeach;
              endif;
            endif;

            // Define the number of blank cards to show when there are fewer than 2 products
            $blank_card_count = max(0, 2 - $subproduct_count);

            // Show blank cards for the remaining slots if needed
            for ($i = 0; $i < $blank_card_count; $i++) {
              echo '<div class="card card-blank w-full min-h-[420px] rounded-lg"></div>';
            }
            ?>
            <!-- End displaying a specific grouped product -->
          </div>
        </div>
        <script>
          // Function to calculate and update slider width
          function updateSliderWidth() {
            const cardWidth = document.querySelector('.card-product').offsetWidth;
            const gap = parseFloat(getComputedStyle(document.querySelector('.pattern-card')).gap);
            const cardCount = document.querySelectorAll('.card-product').length;
            const totalWidth = (cardWidth * cardCount) + (gap * (cardCount));

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

<section id="markets" class="grid-main content-full">
  <?php
  $locations_markets_title = get_field('our_locations_title');
  ?>
  <div class="theme-pale-green content-full-container px-5 py-10 md:p-10 xl:p-20 col-start-1 col-end-13">
    <div class="content-full-row grid-sub mb-10">
      <div class="content-heading col-start-1 col-end-13 mb-2 md:mb-0 md:col-start-1 md:col-end-6">

        <h2 class="h2">
          <?php echo $locations_markets_title ?>
        </h2>
      </div>
    </div>
    <div class="card-pattern grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-start content-start">
      <!-- Start Markets Repeater -->
      <?php
      if (have_rows('our_locations')):
        while (have_rows('our_locations')):
          the_row();

          if (have_rows('markets')):
            while (have_rows('markets')):
              the_row();
              $market_name = get_sub_field('market_name');
              $market_image = get_sub_field('market_image');
              ?>
              <div class="card bg-white rounded-lg flex flex-col justify-start overflow-hidden w-full">
                <div class="card-top px-2.5 py-5 lg:px-5 lg:py-10">
                  <h3 class="h3 text-center">
                    <?php echo $market_name; ?>
                  </h3>
                </div>
                <div class="card-bottom bg-white rounded-lg h-full min-h-[256px] relative overflow-hidden">
                  <img src="<?php echo esc_url($market_image); ?>" alt="<?php echo esc_attr($market_name); ?>"
                    class="absolute bottom-0 h-full w-full object-cover object-center" />
                </div>
              </div>
              <?php
            endwhile;
          endif;

        endwhile;
      endif;
      ?>
      <!-- End Markets Repeater -->
    </div>
  </div>
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

<?php get_footer(); ?>