<?php

/**
 * Template Name: Gallery Template
 */

defined('ABSPATH') || exit;

get_header('shop');

?>

<!--Archive Loop Start-->

<section class="grid-main hero hero-sub md:min-h-[250px] lg:min-h-[300px]">
	<div
		class="theme-white hero-content col-start-1 col-end-13 p-5 pt-10 md:p-10 xl:px-20 xl:py-10 flex flex-col justify-end">
		<div class="collection-overview grid-sub">
			<div class="collection-meta col-start-1 col-end-13">
				<div class="content-heading">
					<?php if (apply_filters('woocommerce_show_page_title', true)): ?>
						<h1 class="h1 woocommerce-products-header__title page-title">
							<?php the_title(); ?>
						</h1>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="grid-main content-full">
	<div class="theme-white content-full-container col-start-1 col-end-13 grid-sub flex flex-col md:gap-4 lg:gap-16 px-5 py-10 pb-5 md:p-10 xl:p-20">
		<div class="col-start-1 col-end-13">
			<!--gallery here-->
      <?php
        $images = get_field('gallery');
        if( $images ): ?>
          <div class="gallery grid grid-cols-2 lg:grid-cols-3 gap-2 lg:gap-4">
            <?php foreach( $images as $image ): ?>
              <div class="gallery-item border border-green-pale rounded-md overflow-hidden p-1 lg:p-4">
                <a href="<?php echo esc_url($image['url']); ?>" data-lightbox="gallery" data-title="<?php echo esc_attr($image['caption']); ?>">
                  <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="w-full h-full object-contain" />
                </a>
                <p><?php echo esc_html($image['caption']); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
			<?php endif; ?>
		</div>
	</div>
</section>

<!--Archive Loop Ends-->

<?php get_footer();