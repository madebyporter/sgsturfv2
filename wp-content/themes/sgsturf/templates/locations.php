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
        <div class="slider-container mx-5 md:mx-10 xl:mx-20">

          <!-- Start displaying a specific grouped product -->
          <?php
          $specific_grouped_product_id = $locations_featured_lines_lines; // Replace with the ID of your grouped product
          $grouped_product = wc_get_product($specific_grouped_product_id);

          $subproduct_count = 0; // Initialize counter for child products
          
          // Check if the product is a grouped product
          if ($grouped_product->is_type('grouped')):
            $children_ids = $grouped_product->get_children();

            $subproduct_count = count($children_ids);
            $slider_noslide_class = $subproduct_count < 5 ? 'no-slide' : '';
            ?>
            <!-- Start Card Pattern -->
            <div
              class="<?php echo $slider_noslide_class; ?> pattern-card group before:hidden after:hidden flex flex-nowrap relative gap-5 justify-between content-start w-fit lg:w-full pr-5 md:pr-10 xl:pr-20 lg:[&.no-slide]:pr-0">

              <?php
              // Loop through the sub-products (children) of the grouped product
              foreach ($children_ids as $child_id):
                $child_product = wc_get_product($child_id);

                if ($child_product->get_status() === 'publish') {
                  $child_categories = wp_get_post_terms($child_id, 'product_cat', array('fields' => 'names'));

                  // Prepare arguments for the card component
                  $card_args = array(
                    'product_name' => $child_product->get_name(),
                    'product_category' => $child_categories,
                    'product_link' => get_permalink($child_id),
                    'product_photo' => $child_product->get_image_id() ? wp_get_attachment_image_src($child_product->get_image_id(), 'full')[0] : SGSTURF_IMAGES_DIR . '/ui-state-zero-simpleproduct.jpg',
                    'is_series' => true,
                  );

                  // Render the card component
                  card($card_args);
                }

              endforeach; ?>
            </div>

            <?php
          endif;
          ?>
          <!-- End displaying a specific grouped product -->

        </div>
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