<?php
/**
 * Template Name: Home Template
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

get_header(); ?>

<section class="grid-main hero hero-home md:min-h-[60vh] grid-rows-1">
  <div class="hero-content row-start-1 col-start-1 col-end-13 order-2 md:col-start-1 md:col-span-6 px-5 py-10 md:p-10 xl:p-20 grid-sub content-center">
    <div class="content-heading-cta col-start-1 col-end-13 flex flex-col gap-5">
      <?php
      // Debug: Check if 'home' field group is returning anything
      $home_hero_h1 = get_field('hero_h1');
      $home_hero_p = get_field('hero_p');
      ?>
      <h1 class="text-2xl lg:text-4xl">
        <?php echo $home_hero_h1 ?>
      </h1>
      <p class="text-2xl">
        <?php echo $home_hero_p ?>
      </p>
      <div class="text-2xl flex flex-row gap-2.5">
        <a href="tel:8442088873" class="button button-primary-b">
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
  <?php
    // Debug: Check if 'home' field group is returning anything
    $home_hero_image_1 = get_field('hero_image_1');
    $home_hero_image_2 = get_field('hero_image_2');
    $home_hero_image_3 = get_field('hero_image_3');
    $home_hero_image_4 = get_field('hero_image_4');
    $home_hero_image_5 = get_field('hero_image_5');
    ?>
  <div class="hero-masonry gap-4 hidden md:hidden row-start-1 lg:flex lg:col-start-7 lg:col-span-3">
    <div class="hero-masonry-col lg:flex flex-col gap-4 justify-between w-full">
      <img src="<?php echo $home_hero_image_1 ?>" alt="Turf" class="hidden lg:block w-full h-full grow">
      <img src="<?php echo $home_hero_image_2 ?>" alt="Turf" class="hidden lg:block h-[283px] w-full">
    </div>
  </div>
  <div class="hero-masonry flex gap-4 row-start-1 col-start-1 col-end-13 order-1 md:col-start-7 md:col-end-13 md:order-2 lg:col-start-10 lg:col-end-13">
    <div class="hero-masonry-col flex grow flex-col gap-4 justify-between w-full">
      <img src="<?php echo $home_hero_image_3 ?>" alt="Turf" class="h-60 md:h-full lg:h-auto xl:block object-right w-full">
      <img src="<?php echo $home_hero_image_4 ?>" alt="Turf" class="hidden lg:block h-[339px] w-full">
      <img src="<?php echo $home_hero_image_5 ?>" alt="Turf" class="hidden lg:block h-[112px] w-full">
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
    <div class="grid-sub px-5 py-10 pb-5 md:p-10 xl:p-20">
      <div class="content-heading col-start-1 col-end-13 mb-2 md:mb-0 md:col-start-1 md:col-end-6">
        <h2 class="h2">
          <?php echo $home_featured_lines_h1 ?>
        </h2>
      </div>
      <div class="col-start-1 col-end-13 md:col-start-7 md:col-end-13">
        <div class="content-paragraph md:pl-10 xl:pl-20">
          <p>
            <?php echo $home_featured_lines_p ?>
          </p>
        </div>
      </div>
    </div>
    <div class="grid-sub pb-5 md:pb-10 xl:pb-20">

      <!-- Start Slider -->
      <div class="slider col-start-1 col-end-13">
        <div class="slider-container mx-5 md:mx-10 xl:mx-20">

          <!-- Start displaying a specific grouped product -->
          <?php
          $specific_grouped_product_id = $home_featured_lines_lines; // Replace with the ID of the specific grouped product you want to display
          $grouped_product = wc_get_product($specific_grouped_product_id);

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

<section class="grid-main">
  <?php
    // Debug: Check if 'home' field group is returning anything
    $home_value_prop_left_content = get_field('value_prop_left_content');
    $home_value_prop_right_content = get_field('value_prop_right_content');
  ?>

  <div class="col-start-1 col-span-full grid grid-cols-subgrid gap-x-4 gap-y-0 bg-[#F3F5F2] rounded-lg lg:items-center">
    <div class="col-start-1 col-span-12 lg:col-start-1 lg:col-span-6 px-5 py-10 md:p-10 xl:p-20">
      <h2 class="text-xl lg:text-3xl font-bold"><?php echo $home_value_prop_left_content ?></h2>
    </div>
    <div class="col-start-1 col-span-12 lg:col-start-7 lg:col-span-6 px-5 py-10 pt-0 md:p-10 md:pt-0 lg:p-10 lg:pl-0 xl:p-20 xl:pl-0">
      <ul class="list-none grid grid-cols-3 sm:grid-cols-6 lg:grid-cols-4 gap-2.5 justify-between">
        <?php
        $images = array(
          array('path' => 'badge-sgslogo.png', 'alt' => 'PFAS Free', 'class' => ' lg:col-span-2'),
          array('path' => 'badge-rohs.svg', 'alt' => 'ROHS', 'class' => ' lg:col-span-2'),
          array('path' => 'badge-metalfree.png', 'alt' => 'Metal Free'),
          array('path' => 'badge-uvtested.png', 'alt' => 'UV Tested'),
          array('path' => 'badge-alphapet.png', 'alt' => 'Alpha Pet'),
          array('path' => 'badge-ipema.png', 'alt' => 'IPEMA'),
          array('path' => 'badge-leadfree.png', 'alt' => 'Lab Tested'),
          array('path' => 'badge-labtested.png', 'alt' => 'Lead Free'),
          array('path' => 'badge-verified.png', 'alt' => 'Verified'),
          array('path' => 'badge-pfasfree.svg', 'alt' => 'Verified'),
        );

        foreach ($images as $image) {
          $extra_class = isset($image['class']) ? $image['class'] : '';
          echo '<li class="rounded-lg overflow-hidden' . $extra_class . '">';
          echo '<img src="' . SGSTURF_IMAGES_DIR . '/' . $image['path'] . '" alt="' . $image['alt'] . '" class="w-full h-full">';
          echo '</li>';
        }
        ?>
      </ul>
    </div>
  </div>
</section>

<section class="grid-main">
  <div class="col-start-1 col-span-full grid grid-cols-subgrid gap-x-4 gap-y-0 bg-[#E9EBE8] rounded-lg lg:items-center relative overflow-hidden">
    <div class="col-start-1 col-span-12 lg:col-start-1 lg:col-span-7 z-10 p-5 md:p-10 xl:p-20">
      <h2 class="text-2xl lg:text-3xl font-bold">
        With over 2 Million Square Feet<br />
        of Turf in Stock, We Promptly Ship<br />
        Nationwide to your project site.<br />
        Contractors and Homeowners alike.
      </h2>
    </div>
    <!-- <div class="
      absolute z-0 
      w-[600px] h-[600px] top-[-10%] left-[50%] translate-x-[-50%] 
      sm:w-[800px] sm:h-[800px] sm:top-[-20%]
      md:translate-x-0 md:left-[20%]
      lg:left-[45%] lg:top-[-30%]
      xl:w-[1000px] xl:h-[1000px] xl:top-[-45%] xl:left-[45%]">
      <script type="module" src="https://unpkg.com/@splinetool/viewer@1.0.18/build/spline-viewer.js"></script>
      <spline-viewer class="w-full h-full" loading-anim-type="spinner-small-light" url="https://prod.spline.design/BaBpvMZp1txHU3FU/scene.splinecode"></spline-viewer>
    </div> -->
    <div class="col-start-1 col-span-12 z-10 p-5 pt-0 md:p-10 md:pt-0 xl:p-20 xl:pt-0">
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
                <div class="card-top flex flex-col items-start gap-4 px-5 py-5 lg:px-5 lg:py-10">
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

<section class="grid-main hero hero-home md:min-h-[60vh] !hidden">
  <div
    class="hero-content col-start-1 col-end-13 md:col-start-1 md:col-end-7 lg:col-start-1 lg:col-end-7 px-5 py-10 md:p-10 xl:p-20 grid-sub content-center">
    <div class="content-heading-cta col-start-1 col-end-13 flex flex-col gap-5">
      <?php
      // Debug: Check if 'home' field group is returning anything
      $home_applications_h2 = get_field('applications_h2');
      $home_applications_p = get_field('applications_p');
      ?>
      <h2 class="text-3xl lg:text-5xl font-bold">
        <?php echo $home_applications_h2 ?>
        </h1>
        <p class="text-2xl">
          <?php echo $home_applications_p ?>
        </p>
    </div>
  </div>
  <div class="hero-masonry flex gap-4 col-start-1 col-end-13 md:col-start-7 md:col-end-13 lg:col-start-7 lg:col-end-13">
    <?php
    // Debug: Check if 'home' field group is returning anything
    $home_applications_landscape_image = get_field('applications_landscape_image');
    $home_applications_golf_image = get_field('applications_golf_image');
    $home_applications_pet_image = get_field('applications_pet_image');
    $home_applications_sports_image = get_field('applications_sports_image');
    ?>
    <div class="hero-masonry-col hidden lg:flex grow flex-col gap-4 justify-between">
      <img src="<?php echo $home_applications_landscape_image ?>" alt="Landscape" class="hidden lg:block lg:grow">
      <img src="<?php echo $home_applications_golf_image ?>" alt="Golf" class="hidden lg:block h-[283px]">
    </div>
    <div class="hero-masonry-col flex grow flex-col gap-4 justify-between w-full lg:w-auto">
      <img src="<?php echo $$home_applications_pet_image = get_field('applications_pet_image');
      ?>" alt="Pet" class="h-60 md:h-full lg:h-auto lg:grow xl:block object-right">
      <img src="<?php echo $home_applications_sports_image ?>" alt="Sports" class="hidden lg:block h-[339px]">
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