<?php
/**
 * Template Name: Home Template
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

get_header(); ?>

<section class="grid-main hero hero-home md:min-h-[60vh]">
  <div
    class="hero-content col-start-1 col-end-13 order-2 md:col-start-1 md:col-end-7 md:order-1 lg:col-start-1 lg:col-end-7 px-5 py-10 md:p-10 xl:p-20 grid-sub content-center">
    <div class="content-heading-cta col-start-1 col-end-13 flex flex-col gap-5">
      <?php
      // Debug: Check if 'home' field group is returning anything
      $home_hero_h1 = get_field('hero_h1');
      $home_hero_p = get_field('hero_p');
      ?>
      <h1 class="text-3xl lg:text-5xl font-bold">
        <?php echo $home_hero_h1 ?>
      </h1>
      <p class="text-2xl">
        <?php echo $home_hero_p ?>
      </p>
      <div class="text-2xl flex flex-row gap-2.5">
        <a href="tel:8442088873" class="button button-primary">
          <span class="button-label">(844) 208-8873</span>
          <span class="button-arrow">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
                fill="#242423" />
            </svg>
          </span>
        </a>
      </div>
    </div>
  </div>
  <div
    class="hero-masonry flex gap-4 col-start-1 col-end-13 order-1 md:col-start-7 md:col-end-13 md:order-2 lg:col-start-7 lg:col-end-13">
    <?php
    // Debug: Check if 'home' field group is returning anything
    $home_hero_image_1 = get_field('hero_image_1');
    $home_hero_image_2 = get_field('hero_image_2');
    $home_hero_image_3 = get_field('hero_image_3');
    $home_hero_image_4 = get_field('hero_image_4');
    $home_hero_image_5 = get_field('hero_image_5');
    ?>
    <div class="hero-masonry-col hidden lg:flex grow flex-col gap-4 justify-between">
      <img src="<?php echo $home_hero_image_1 ?>" alt="Turf" class="hidden lg:block lg:grow">
      <img src="<?php echo $home_hero_image_2 ?>" alt="Turf" class="hidden lg:block h-[283px]">
    </div>
    <div class="hero-masonry-col flex grow flex-col gap-4 justify-between w-full lg:w-auto">
      <img src="<?php echo $home_hero_image_3 ?>" alt="Turf"
        class="h-60 md:h-full lg:h-auto lg:grow xl:block object-right">
      <img src="<?php echo $home_hero_image_4 ?>" alt="Turf" class="hidden lg:block h-[339px]">
      <img src="<?php echo $home_hero_image_5 ?>" alt="Turf" class="hidden lg:block h-[112px]">
    </div>
  </div>
</section>

<section class="grid-main content-halves">
  <?php
  // Debug: Check if 'home' field group is returning anything
  $home_value_prop_left_content = get_field('value_prop_left_content');
  $home_value_prop_right_content = get_field('value_prop_right_content');
  ?>
  <div
    class="theme-orange content-halves-col col-start-1 col-end-13 md:col-start-1 md:col-end-7 px-5 py-10 md:p-10 xl:p-20 grid-sub">
    <div class="content-heading col-start-1 col-end-12">
      <h2 class="text-xl lg:text-3xl font-bold">
        <?php echo $home_value_prop_left_content ?>
      </h2>
    </div>
  </div>
  <div
    class="theme-green content-halves-col col-start-1 col-end-13 md:col-start-7 md:col-end-13 px-5 py-10 md:p-10 xl:p-20 grid-sub">
    <div class="content-paragraph col-start-1 col-end-13">
      <p class="text-xl lg:text-3xl font-bold">
        <?php echo $home_value_prop_right_content ?>
      </p>
    </div>
  </div>
</section>

<section id="products" class="grid-main content-full">
  <?php
  // Debug: Check if 'home' field group is returning anything
  $home_featured_lines_h1 = get_field('featured_lines_h1');
  $home_featured_lines_p = get_field('featured_lines_p');
  $home_featured_lines_lines = get_field('featured_lines_collection');
  ?>
  <div class="theme-black content-full-container col-start-1 col-end-13 flex flex-col gap-0">
    <div class="content-full-row grid-sub px-5 py-10 md:p-10 xl:p-20">
      <div class="content-heading col-start-1 col-end-13 mb-2 md:mb-0 md:col-start-1 md:col-end-6">
        <h2 class="h2">
          <?php echo $home_featured_lines_h1 ?>
        </h2>
      </div>
      <div class="col-start-1 col-end-13 md:col-start-7 md:col-end-13">
        <div class="content-paragraph md:pl-10 xl:pl-20">
          <p class="mb-4">
            <?php echo $home_featured_lines_p ?>
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
            $specific_grouped_product_id = $home_featured_lines_lines; // Replace with the ID of the specific grouped product you want to display
            $grouped_product = wc_get_product($specific_grouped_product_id);

            // Check if the product is a grouped product
            if ($grouped_product->is_type('grouped')):
              $children_ids = $grouped_product->get_children();

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
                      <span class="button-label">View Series</span>
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

            document.querySelector('.slider-container').style.minWidth = totalWidth + 'px';
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

<section id="locations" class="grid-main content-full">
  <div class="theme-pale-green content-full-container px-5 py-10 md:p-10 xl:p-20 col-start-1 col-end-13">
    <div class="content-full-row grid-sub mb-10">
      <div class="content-heading col-start-1 col-end-13 mb-2 md:mb-0 md:col-start-1 md:col-end-6">
        <h2 class="h2">
          <?php $locations_h2 = get_field('locations_h2'); ?>
          <?php echo $locations_h2 ?>
        </h2>
      </div>
    </div>
    <div class="card-pattern grid grid-cols-1 sm:grid-cols-2 gap-8 justify-start content-start">
      <!-- Start Markets Repeater -->
      <?php
      if (have_rows('locations')):
        while (have_rows('locations')):
          the_row();

          if (have_rows('location_card')):
            while (have_rows('location_card')):
              the_row();
              $location_card_name = get_sub_field('location_card_name');
              $location_card_page = get_sub_field('location_card_page');
              $location_card_image = get_sub_field('location_card_image');
              ?>
              <div class="card bg-white rounded-lg flex flex-col justify-start overflow-hidden w-full">
                <div class="card-top flex flex-col items-start gap-4 px-2.5 py-5 lg:px-5 lg:py-10">
                  <h3 class="h3">
                    <?php echo $location_card_name; ?>
                  </h3>
                  <a href="<?php echo $location_card_page; ?>" class="button button-secondary button-small">
                    <span class="button-label">View Location</span>
                    <span class="button-arrow">
                      <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                          d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
                          fill="#242423" />
                      </svg>
                    </span>
                  </a>
                </div>
                <div class="card-bottom bg-white rounded-lg h-full min-h-[256px] relative overflow-hidden">
                  <img src="<?php echo $location_card_image; ?>" alt="<?php echo $location_card_name; ?>"
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

<section class="grid-main content-full">
  <?php
  // Debug: Check if 'home' field group is returning anything
  $home_applications_h2 = get_field('applications_h2');
  $home_applications_p = get_field('applications_p');
  $home_applications_landscape_image = get_field('applications_landscape_image');
  $home_applications_golf_image = get_field('applications_golf_image');
  $home_applications_pet_image = get_field('applications_pet_image');
  $home_applications_sports_image = get_field('applications_sports_image');
  ?>
  <div class="theme-white content-full-container col-start-1 col-end-13 flex flex-wrap">
    <div class="content-full-col px-5 py-10 md:p-10 xl:p-20 md:w-1/2">
      <div class="content-heading">
        <h2 class="h2 mb-4">
          <?php echo $home_applications_h2 ?>
        </h2>
        <p>
          <?php echo $home_applications_p ?>
        </p>
      </div>
    </div>
    <div class="content-full-col relative w-1/2">
      <div class="content-image">
        <div class="pattern-tag flex gap-2 absolute bottom-5 right-5 md:bottom-10 md:right-10">
          <div class="tag">Landscape</div>
        </div>
        <img src="<?php echo $home_applications_landscape_image ?>" alt="Landscape">
      </div>
    </div>
    <div class="content-full-col relative w-1/2 md:w-1/3">
      <div class="pattern-tag flex gap-2 absolute bottom-5 right-5 md:bottom-10 md:right-10">
        <div class="tag">Golf</div>
      </div>
      <div class="content-image">
        <img src="<?php echo $home_applications_golf_image ?>" alt="Golf">
      </div>
    </div>
    <div class="content-full-col relative w-1/2 md:w-1/3">
      <div class="pattern-tag flex gap-2 absolute bottom-5 right-5 md:bottom-10 md:right-10">
        <div class="tag">Pet</div>
      </div>
      <div class="content-image">
        <img src="<?php echo $home_applications_pet_image ?>" alt="Pet">
      </div>
    </div>
    <div class="content-full-col relative w-1/2 md:w-1/3">
      <div class="pattern-tag flex gap-2 absolute bottom-5 right-5 md:bottom-10 md:right-10">
        <div class="tag">Sports</div>
      </div>
      <div class="content-image">
        <img src="<?php echo $home_applications_sports_image ?>" alt="Sports">
      </div>
    </div>
  </div>
</section>

<section class="grid-main content-halves lg:min-h-[700px]">
  <?php
  // Debug: Check if 'home' field group is returning anything
  $home_call_to_action_cta_left_h2 = get_field('call_to_action_cta_left_h2');
  $home_call_to_action_cta_left_p = get_field('call_to_action_cta_left_p');
  $home_call_to_action_cta_left_link = get_field('call_to_action_cta_left_link');
  $home_call_to_action_cta_left_link_label = get_field('call_to_action_cta_left_link_label');
  $home_call_to_action_cta_right_h2 = get_field('call_to_action_cta_right_h2');
  $home_call_to_action_cta_right_p = get_field('call_to_action_cta_right_p');
  $home_call_to_action_cta_right_link = get_field('call_to_action_cta_right_link');
  $home_call_to_action_cta_right_link_label = get_field('call_to_action_cta_right_link_label');
  ?>
  <div
    class="theme-green content-halves-col col-start-1 col-end-13 md:col-start-1 md:col-end-7 px-5 py-10 md:p-10 xl:p-20 grid-sub content-end">
    <div class="content-heading-cta col-start-1 col-end-13">
      <h2 class="h2 mb-4">
        <?php echo $home_call_to_action_cta_left_h2 ?>
      </h2>
      <p class="mb-8">
        <?php echo $home_call_to_action_cta_left_p ?>
      </p>
      <a href="<?php echo $home_call_to_action_cta_left_link ?>" class="button button-tertiary">
        <span class="button-label">
          <?php echo $home_call_to_action_cta_left_link_label ?>
        </span>
        <span class="button-arrow">
          <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
              fill="#242423" />
          </svg>
        </span>
      </a>
    </div>
  </div>
  <div
    class="theme-orange content-halves-col col-start-1 col-end-13 md:col-start-7 md:col-end-13 px-5 py-10 md:p-10 xl:p-20 grid-sub content-end">
    <div class="content-heading-cta col-start-1 col-end-13">
      <h2 class="h2 mb-4">
        <?php echo $home_call_to_action_cta_right_h2 ?>
      </h2>
      <p class="mb-8">
        <?php echo $home_call_to_action_cta_right_p ?>
      </p>
      <a href="<?php echo $home_call_to_action_cta_right_link ?>" class="button button-tertiary">
        <span class="button-label">
          <?php echo $home_call_to_action_cta_right_link_label ?>
        </span>
        <span class="button-arrow">
          <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
              fill="#242423" />
          </svg>
        </span>
      </a>
    </div>
  </div>
</section>

<?php get_footer(); ?>