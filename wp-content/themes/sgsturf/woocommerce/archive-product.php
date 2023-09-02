<?php
defined('ABSPATH') || exit;

get_header('shop');

// Get all product categories
$product_categories = get_terms('product_cat');

// Get selected category filters (if any)
$category_filters = isset($_GET['category']) ? (array) $_GET['category'] : array();

// Set up the query arguments
$query_args = array(
	'status' => 'publish',
	'limit' => -1,
	'type' => 'grouped',
);

// Apply category filter if selected
if (!empty($category_filters)) {
	$query_args['category'] = $category_filters;
}

?>

<!--Archive Loop Start-->

<section class="grid-main hero hero-sub md:min-h-[250px] lg:min-h-[300px]">
	<div class="theme-white hero-content col-start-1 col-end-13 p-5 md:p-10 lg:px-20 lg:py-10 flex flex-col justify-end">
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
	<div class="theme-black content-full-container col-start-1 col-end-13 grid-sub flex flex-col md:gap-4 lg:gap-16 p-5 md:p-10 lg:p-20">
		<div class="col-start-1 col-end-13 lg:col-start-1 lg:col-end-4 mb-4 md:mb-0">
			<form action="<?php echo esc_url(get_permalink(get_option('woocommerce_shop_page_id'))); ?>" method="get">
				<label>Filter by Category:</label><br>
				<fieldset class="group-checkbox flex flex-col md:justify-between md:items-center md:flex-wrap md:flex-row md:mb-4 md:gap-4 lg:flex-col lg:items-start lg:justify-start lg:gap-2 my-4">
					<?php foreach ($product_categories as $category): ?>
						<label>
							<input type="checkbox" name="category[]" value="<?php echo esc_attr($category->slug); ?>" <?php if (in_array($category->slug, $category_filters))
									echo 'checked'; ?>>
							<?php echo esc_html($category->name); ?>
						</label>
					<?php endforeach; ?>
					<button type="submit" class="button button-tertiary mt-4">Apply Filter</button>
				</fieldset>
			</form>
		</div>
		<div class="col-start-1 col-end-13 lg:col-start-4 lg:col-end-13">
			<div class="pattern-card flex gap-8 md:justify-between lg:justify-start flex-wrap">
				<!-- Start grouped products loop -->
				<?php
				$grouped_products = wc_get_products($query_args);

				foreach ($grouped_products as $product):
					$category_list = wc_get_product_category_list($product->get_id(), ', ');
					$categories = explode(', ', $category_list);
					?>
					<div class="card card-product">
						<div class="card-product-top">
							<h3 class="h3 mb-2">
								<?php echo esc_html($product->get_name()); ?>
							</h3>
							<div class="pattern-tag flex gap-2 mb-4 md:mb-8">
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
	</div>
</section>

<!--Archive Loop Ends-->

<?php get_footer();