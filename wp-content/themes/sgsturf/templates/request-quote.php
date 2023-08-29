<?php 

/**
 * Template Name: Request a Quote Template
 */

get_header(); ?>

  <section class="grid-main hero hero-sub min-h-[300px]">
    <div class="theme-white hero-content col-start-1 col-end-13 p-10 flex flex-col justify-end">
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
    <div class="theme-white content-full-container p-10 col-start-1 col-end-13 grid-sub gap-4">
      <div class="col-start-1 col-end-5">
        <p class="small mb-10">Note: All orders for next day delivery will need to be received & confirmed before 10:00 am. Orders & delivery are placed in the order received.  All onsite deliveries are curb-side only and require offload assistance.</p>
        <p class="small mb-10">SPECIFIED DELIVERY TIMES ARE NOT GUARANTEED</p>
        <img src="<?php echo SGSTURF_IMAGES_DIR; ?>/fpo-orderform-sidebar.jpg" alt="Warehouse">
      </div>
      <div class="col-start-6 col-end-11">
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
