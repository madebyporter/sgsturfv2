<?php 

/**
 * Template Name: Sample Shop Template
 */


get_header( 'shop' ); ?>

<!--Archive Loop Start-->

<section class="grid-main hero hero-sub min-h-[300px]">
  <div class="theme-white hero-content col-start-1 col-end-13 p-10 flex flex-col justify-end">
    <div class="product-overview grid-sub">
      <div class="product-meta col-start-1 col-end-13">
        <div class="content-heading">
          <h1 class="h1">Shop Our Turf</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="grid-main content-full">
  <div class="theme-black content-full-container col-start-1 col-end-13 grid-sub flex flex-col gap-16 p-20">
    <div class="col-start-1 col-end-4">
      ABC
    </div>
    <div class="col-start-4 col-end-13">
      DEF
    </div>
  </div>
</section>

<!--Archive Loop Ends-->

<?php get_footer(); ?>
