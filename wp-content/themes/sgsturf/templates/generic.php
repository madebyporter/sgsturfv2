<?php 

/**
 * Template Name: Generic Template
 */

get_header(); ?>

  <section class="grid-main hero hero-sub md:min-h-[250px] lg:min-h-[300px]">
    <div class="theme-white hero-content col-start-1 col-end-13 p-5 pt-10 md:p-10 xl:px-20 xl:py-10 flex flex-col justify-end">
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
      
      <div class="content-cms col-start-1 col-end-13 lg:col-start-1 lg:col-end-9">
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
