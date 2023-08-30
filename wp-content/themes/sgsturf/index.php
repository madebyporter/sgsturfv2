<?php
/**
 * Template Name: Home Template
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

get_header(); ?>

<section class="grid-main hero hero-home md:min-h-[50vh]">
  <div class="hero-content md:col-start-1 md:col-end-8 lg:col-start-1 lg:col-end-7 md:p-10 lg:p-20 grid-sub content-end">
    <div class="content-heading-cta col-start-1 col-end-12">
      <h1 class="h1 md:mb-4 lg:mb-8">Get beautiful, artificial turf within days &amp; not break the bank.</h1>
      <div class="pattern-button">
        <a href="#" class="button button-primary">
          <span class="button-label">Shop our Turf</span>
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
  <div class="hero-masonry flex gap-4 md:col-start-8 md:col-end-13 lg:col-start-7 lg:col-end-13">
    <div class="hero-masonry-col flex flex-col gap-4 justify-between w-full xl:w-auto">
      <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-hero-2.jpg" alt="Turf" class="md:h-full lg:h-auto xl:block">
      <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-hero-3.jpg" alt="Turf" class="hidden xl:block">
    </div>
    <div class="hero-masonry-col hidden xl:flex flex-col gap-4 justify-between">
      <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-hero-4.jpg" alt="Turf" class="hidden xl:block">
      <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-hero-5.jpg" alt="Turf" class="hidden xl:block">
      <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-hero-6.jpg" alt="Turf" class="hidden xl:block">
    </div>
  </div>
</section>

<section class="grid-main content-halves">
  <div class="theme-orange content-halves-col col-start-1 col-end-7 md:p-10 lg:p-20 grid-sub">
    <div class="content-heading col-start-1 col-end-12">
      <h2 class="h2">Choose from our Ready Inventory of High-Quality, Test-Proven Turf at Competitive Pricing.</h2>
    </div>
  </div>
  <div class="theme-green content-halves-col col-start-7 col-end-13 md:p-10 lg:p-20 grid-sub">
    <div class="content-paragraph col-start-1 col-end-13">
      <p class="mb-8">
        We’re located in Southern California and Northern Texas but ship right to your door. Contactors and homeowners alike.
      </p>
    </div>
  </div>
</section>

<section id="products" class="grid-main content-full">
  <div class="theme-black content-full-container col-start-1 col-end-13 flex flex-col gap-16 pb-20">
    <div class="content-full-row grid-sub p-20 pb-0">
      <div class="content-heading col-start-1 col-end-6">
        <h2 class="h2">Featured Series Lines</h2>
      </div>
      <div class="content-paragraph col-start-8 col-end-13">
        <p class="mb-4">
          Experience the Perfect Green:
        </p>
        <p class="mb-8">
          Premium Synthetic Grass for Landscapes, Pets, Sports and Golf by SGSTurf.
        </p>
      </div>
    </div>
    <div class="content-full-row grid-sub">
      <div class="slider col-start-1 col-end-13">
        <div class="slider-container mx-20">
          <div class="pattern-card flex gap-8 justify-start content-start">
            <!-- Start grouped products loop -->
            <?php
            $grouped_products = wc_get_products(
              array(
                'status' => 'publish',
                'limit' => 4,
                'type' => 'grouped',
              )
            );

            foreach ($grouped_products as $product):
              $category_list = wc_get_product_category_list($product->get_id(), ', ');
              $categories = explode(', ', $category_list);
              ?>
              <div class="card card-product">
                <div class="card-product-top">
                  <h3 class="h3 mb-2">
                    <?php echo esc_html($product->get_name()); ?>
                  </h3>
                  <div class="pattern-tag flex gap-2 mb-8">
                    <?php foreach ($categories as $category_name): ?>
                        <div class="tag">
                            <?php echo esc_html(wp_strip_all_tags($category_name)); ?>
                        </div>
                    <?php endforeach; ?>
                  </div>
                  <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
                    class="button button-secondary button-small">
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
                <div class="card-product-bottom">
                  <div class="card-product-bottom-container">
                    <img
                      src="<?php echo esc_url($product->get_image_id() ? wp_get_attachment_url($product->get_image_id()) : wc_placeholder_img_src()); ?>"
                      alt="Product" />
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            <!-- End grouped products loop -->
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

<section class="grid-main content-full">
  <div class="theme-white content-full-container col-start-1 col-end-13 flex flex-wrap">
    <div class="content-full-col md:p-10 lg:p-20 w-1/2">
      <div class="content-heading">
        <h2 class="h2 mb-4">Applications</h2>
        <p>
          Experience the Perfect Green: Premium Synthetic Grass for Landscapes, Pets, Sports and Golf by SGSTurf.
        </p>
      </div>
    </div>
    <div class="content-full-col relative w-1/2">
      <div class="content-image">
        <div class="pattern-tag flex gap-2 absolute bottom-10 right-10">
          <div class="tag">Landscape</div>
        </div>
        <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-application-landscape.jpg" alt="Landscape">
      </div>
    </div>
    <div class="content-full-col relative w-1/3">
      <div class="pattern-tag flex gap-2 absolute bottom-10 right-10">
        <div class="tag">Golf</div>
      </div>
      <div class="content-image">
        <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-application-golf.jpg" alt="Golf">
      </div>
    </div>
    <div class="content-full-col relative w-1/3">
      <div class="pattern-tag flex gap-2 absolute bottom-10 right-10">
        <div class="tag">Pet</div>
      </div>
      <div class="content-image">
        <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-application-pet.jpg" alt="Pet">
      </div>
    </div>
    <div class="content-full-col relative w-1/3">
      <div class="pattern-tag flex gap-2 absolute bottom-10 right-10">
        <div class="tag">Sports</div>
      </div>
      <div class="content-image">
        <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-home-application-sports.jpg" alt="Sports">
      </div>
    </div>
  </div>
</section>

<section class="grid-main content-halves lg:min-h-[700px]">
  <div class="theme-green content-halves-col col-start-1 col-end-7 md:p-10 lg:p-20 grid-sub content-end">
    <div class="content-heading-cta col-start-1 col-end-13">
      <h2 class="h2 mb-4">New Customers</h2>
      <p class="mb-8">First time here? Request some sample turf to get a feel for our line.</p>
      <a href="#" class="button button-tertiary">
        <span class="button-label">Shop All Turf</span>
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
  <div class="theme-orange content-halves-col col-start-7 col-end-13 md:p-10 lg:p-20 grid-sub content-end">
    <div class="content-heading-cta col-start-1 col-end-13">
      <h2 class="h2 mb-4">Existing Customers</h2>
      <p class="mb-8">Ready to purchase more turf for your next project? Let’s get you going.</p>
      <a href="#" class="button button-tertiary">
        <span class="button-label">Place an Order</span>
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