<?php

/**
 * Template Name: Accessories Template
 */

defined('ABSPATH') || exit;

get_header('shop');

// Include only the 'accessories' category slug
$included_category_slug = 'accessories';

// Get term ID for the 'accessories' category
$included_category_id = null;
$term = get_term_by('slug', $included_category_slug, 'product_cat');
if ($term) {
	$included_category_id = $term->term_id;
}

// Set up the query arguments to only include products from the 'accessories' category
$query_args = array(
	'status' => 'publish',
	'limit' => -1,
	'type' => 'grouped',
	'orderby' => 'title',
	'order' => 'ASC',
	'tax_query' => array(
		array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
			'terms' => $included_category_id,
		),
	),
);

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
	<div
		class="theme-black content-full-container col-start-1 col-end-13 grid-sub flex flex-col md:gap-4 lg:gap-16 px-5 py-10 pb-5 md:p-10 xl:p-20">
		<div class="col-start-1 col-end-13">
			<div class="group before:hidden after:hidden grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

				<!-- Start grouped products loop -->
				<?php
				$grouped_products = wc_get_products($query_args);

				foreach ($grouped_products as $product):
					$category_list = wc_get_product_category_list($product->get_id(), ', ');
					$categories = explode(', ', $category_list);

					// Skip products with the "collection" category
					if (in_array('Collection', $categories, true)) {
						continue;
					}

					// Prepare arguments for the card component
					$card_args = array(
						'product_name' => $product->get_name(),
						'product_category' => $categories,
						'product_link' => get_permalink($product->get_id()),
						'product_photo' => $product->get_image_id() ? wp_get_attachment_image_src($product->get_image_id(), 'full')[0] : SGSTURF_IMAGES_DIR . '/ui-state-zero-simpleproduct.jpg',
						'is_series' => true,
					);

					// Render the card component
					card($card_args);

				endforeach; ?>
				<!-- End grouped products loop -->

			</div>
		</div>
	</div>
</section>

<!--Archive Loop Ends-->

<?php get_footer();