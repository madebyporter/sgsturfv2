<?php 

/**
 * Template Name: Cart Template
 */

get_header(); ?>


      <section class="grid-main content-full">
        <div class="theme-white content-full-container col-start-1 col-end-13 flex flex-col gap-16 pb-20">
          <?php
            // Display WordPress page content
            while (have_posts()) {
              the_post();
              the_content();
            }
            ?>
        </div>
      </section>

<?php get_footer(); ?>
