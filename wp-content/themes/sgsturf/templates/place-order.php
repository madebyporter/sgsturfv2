<?php 

/**
 * Template Name: Place Order Template
 */

get_header(); ?>

  <section class="grid-main hero hero-sub md:min-h-[250px] lg:min-h-[300px]">
    <div class="theme-white hero-content col-start-1 col-end-13 md:px-10 p-5 md:py-10 lg:px-20 lg:py-10 flex flex-col justify-end">
      <div class="product-overview grid-sub">
        <div class="product-meta col-start-1 col-end-13">
          <div class="content-heading">
            <h1 class="h1"><?php echo esc_html( get_the_title() ); ?></h1>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="grid-main content-full">
    <div class="theme-white content-full-container p-5 md:p-10 xl:p-20 col-start-1 col-end-13 grid-sub gap-4 md:gap-8 lg:gap-4">
      <div class="col-start-1 col-end-13 lg:col-start-1 lg:col-end-5">
        <p class="small md:mb-5 lg:mb-10">Note: All orders for next day delivery will need to be received & confirmed before 10:00 am. Orders & delivery are placed in the order received.  All onsite deliveries are curb-side only and require offload assistance.</p>
        <p class="small md:m-0 lg:mb-10">SPECIFIED DELIVERY TIMES ARE NOT GUARANTEED</p>
        <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-orderform-sidebar.jpg" alt="Warehouse" class="hidden lg:block">
      </div>
      <div class="col-start-1 col-end-13 lg:col-start-6 lg:col-end-13">
        <?php
          // Display WordPress page content
          while (have_posts()) {
            the_post();
            the_content();
          }
        ?>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
