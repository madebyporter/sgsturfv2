<?php
defined('ABSPATH') || exit;

get_header('shop');

// Exclude specific category slugs from the filter options
$excluded_category_slugs = array('uncategorized', 'collection', 'infills');

// Get term IDs for the excluded categories
$excluded_category_ids = array();
foreach ($excluded_category_slugs as $slug) {
	$term = get_term_by('slug', $slug, 'product_cat');
	if ($term) {
		$excluded_category_ids[] = $term->term_id;
	}
}

// Get all product categories excluding the excluded ones
$product_categories = get_terms(
	array(
		'taxonomy' => 'product_cat',
		'exclude' => $excluded_category_ids,
	)
);

// Get selected category filters (if any)
$category_filters = isset($_GET['category']) ? (array) $_GET['category'] : array();


// Set up the query arguments
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
			'terms' => $excluded_category_ids,
			'operator' => 'NOT IN'
		),
	),
);

// Apply category filter if selected
if (!empty($category_filters)) {
	$query_args['category'] = $category_filters;
}

?>

<!--Archive Loop Start-->

<section class="grid-main hero hero-sub md:min-h-[250px] lg:min-h-[300px]">
	<div
		class="theme-white hero-content col-start-1 col-end-13 p-5 pt-10 md:p-10 xl:px-20 xl:py-10 flex flex-col justify-end">
		<div class="collection-overview grid-sub">
			<div class="collection-breadcrumbs">
				<?php
				do_action('woocommerce_before_main_content');
				?>
			</div>
			<div class="collection-meta col-start-1 col-end-13">
				<div class="content-heading">
					<?php if (apply_filters('woocommerce_show_page_title', true)): ?>
						<h1 class="h1 woocommerce-products-header__title page-title">
							<?php woocommerce_page_title(); ?>
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
		<div class="col-start-1 col-end-13 lg:col-start-1 lg:col-end-4 mb-4 md:mb-0">
			<form action="<?php echo esc_url(get_permalink(get_option('woocommerce_shop_page_id'))); ?>" method="get">
				<label class="uppercase border-b border-[#666666] pb-1 w-full block mb-4">Filter by Category</label>
				<fieldset
					class="group-checkbox flex flex-col gap-2 md:justify-stretch md:items-center md:flex-wrap md:flex-row md:mb-4 md:gap-4 lg:flex-col lg:items-start lg:justify-start lg:items-stretch lg:gap-2 lg:my-4">
					<?php foreach ($product_categories as $category) {
						if (!in_array($category->slug, $excluded_category_slugs)) { ?>
							<label class="px-2.5 py-2 flex gap-2 items-center flex-1">
								<input type="checkbox" name="category[]" value="<?php echo esc_attr($category->slug); ?>" <?php if (in_array($category->slug, $category_filters))
										 echo 'checked'; ?>>
								<span class="checkmark"></span>
								<?php echo esc_html($category->name); ?>
							</label>
						<?php }
					} ?>
					<button type="submit" class="button button-tertiary mt-4 md:mt-0 lg:mt-4">Apply Filter</button>
				</fieldset>
			</form>
		</div>



		<div class="col-start-1 col-end-13 lg:col-start-4 lg:col-end-13">
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