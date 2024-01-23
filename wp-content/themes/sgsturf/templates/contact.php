<?php

/**
 * Template Name: Contact Template
 */

get_header(); ?>

<section class="grid-main hero hero-sub md:min-h-[250px] lg:min-h-[300px]">
  <div
    class="theme-white hero-content col-start-1 col-end-13 p-5 pt-10 md:p-10 xl:px-20 xl:py-10 flex flex-col justify-end">
    <div class="product-overview grid-sub">
      <div class="product-meta col-start-1 col-end-13">
        <div class="content-heading">
          <h1 class="h1">
            <?php echo esc_html(get_the_title()); ?>
          </h1>
        </div>
      </div>
    </div>
  </div>
</section>


<section id="requestquote" class="grid-main content-full">
  <?php
  $locations_leadgen_form = get_field('leadgen_form');
  ?>
  <div
    class="theme-white content-full-container px-5 py-10 md:p-10 xl:p-20 col-start-1 col-end-13 grid-sub gap-4 md:gap-8 lg:gap-4">
    <div class="col-start-1 col-end-13 lg:col-start-1 lg:col-end-6">
      <h2 class="h2 mb-10">Get in touch</h2>

      <h3 class="h3">California</h3>
      <p class="mb-10">690 Ridgeway St.<br />
      Pomona, CA 91768<br />
      Phone: <a class="text-orange" href="tel:(909)-629-8400">(909)-629-8400</a></p>

      <h3 class="h3">Texas</h3>
      <p>2001 E Randol Mill Rd Ste 107<br />
      Arlington TX, 76011<br />
      Phone: <a class="text-orange" href="tel:(817)-583-6880">(817)-583-6880</a></p>
    </div>
    <div class="col-start-1 col-end-13 lg:col-start-7 lg:col-end-13">
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