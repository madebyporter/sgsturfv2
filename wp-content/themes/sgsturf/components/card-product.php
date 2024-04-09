<?php
function card($args)
{
  $defaults = array(
    'product_id' => 0,
    'product_name' => '',
    'product_category' => array(),
    'product_link' => '',
    'product_photo' => '',
    'product_specs' => array(),
    'view_label' => 'View Specs',
    'is_series' => false,
  );

  $args = wp_parse_args($args, $defaults);

  ?>

  <!-- Card -->
  <div
    class="<?php echo $args['is_series'] ? 'card-series' : 'card-product'; ?> bg-white rounded-lg flex flex-col justify-start overflow-hidden group-[.grid]:w-full w-[256px] lg:group-[.no-slide]:w-full group/specsButton">

    <!-- Card: Top -->
    <div class="relative flex flex-col items-start gap-5 p-5 pt-7">
      <div class="flex flex-row justify-between w-full">
        <div class="flex flex-col gap-2">

          <!-- Header: Product Name -->
          <h3 class="text-xl font-bold text-black">
            <?php echo esc_html($args['product_name']); ?>
          </h3>

          <!-- Tag: Product Category -->
          <div class="flex flex-row gap-2">
            <?php foreach ($args['product_category'] as $category_name): ?>
              <div class="bg-green-pale rounded-full inline-flex text-xs tracking-wide px-4 py-1 uppercase text-black">
                <?php echo esc_html(wp_strip_all_tags($category_name)); ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- Share -->
        <div class="text-black flex flex-col justify-start absolute top-2 right-2">
          <?php
            $shortcode = '[easy-social-share buttons="share,facebook,twitter,mail,copy" size="xs" align="center" template="16" native="no" sharebtn_func="3" url="' . esc_url($args['product_link']) . '" title="' . esc_attr($args['product_name']) . '" target="_blank"][/easy-social-share]';
            echo do_shortcode($shortcode);
          ?>
        </div>
      </div>

      <!-- Button -->
      <?php if ($args['is_series'] && $args['product_link']): ?>
        <a href="<?php echo esc_url($args['product_link']); ?>"
          class="group/button flex flex-wrap gap-4 items-center content-between justify-between w-auto p-2 rounded-md cursor-pointer bg-transparent hover:bg-black border border-black text-black hover:text-white before:hidden after:hidden">
          <?php
            // Get the current post object
            global $post;

            // Check if the post title contains the word 'accessories'
            if ( strpos( strtolower( $post->post_title ), 'accessories' ) !== false ) {
                echo '<span class="text-base font-bold tracking-tight leading-none whitespace-nowrap">View Product</span>';
            } else {
                echo '<span class="text-base font-bold tracking-tight leading-none whitespace-nowrap">View Series</span>';
            }
          ?>
          <span class="flex items-center bg-black justify-center rounded-full w-5 h-5 group-hover/button:bg-white">
            <svg class="w-2.5	h-2.5" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
                class="fill-white group-hover/button:fill-black" />
            </svg>
          </span>
        </a>
      <?php endif; ?>
    </div>
    <!-- Start Card Bottom -->
    <div class="card-bottom group bg-white rounded-md min-h-[256px] relative h-full">

      <!-- Start Card Specs -->
      <?php if (!empty($args['product_specs'])): ?>
        <div class="group product-specs rounded-lg px-3 md:px-5 pb-16 flex flex-col gap-2">
          <?php 
          // Assuming $is_accessory is a boolean indicating if the current product is an accessory
          $is_accessory = has_term('accessories', 'product_cat', $args['product_id']);

          if (!$is_accessory): // If the product is not an accessory, display all specs
            foreach ($args['product_specs'] as $spec_key => $spec_value): ?>
              <div class="border-t border-stone-500 flex justify-between text-sm text-black pt-1">
                <div>
                  <?php echo esc_html(ucwords(str_replace('_', ' ', $spec_key))); ?>:
                </div>
                <div class="font-bold">
                  <?php
                  if (is_array($spec_value) && $spec_key === 'Blade_Colors') {
                    echo '<ul>';
                    foreach ($spec_value as $color) {
                      echo '<li class="text-right">' . esc_html($color) . '</li>';
                    }
                    echo '</ul>';
                  } else {
                    echo esc_html($spec_value);
                  }
                  ?>
                </div>
              </div>
            <?php endforeach;
          elseif ($is_accessory): // If the product is an accessory, display only 'Spec Detail'
            ?>
            <div class="border-t border-stone-500 flex flex-col justify-start text-sm text-black pt-1">
              <div>
                Spec Detail:
              </div>
              <div class="font-normal">
                <?php 
                  // Access the 'Spec Detail' value directly
                  if (isset($args['product_specs']['Spec Detail'])) {
                    $spec_detail = $args['product_specs']['Spec Detail']; 
                    echo esc_html($spec_detail);
                  } else {
                    echo "No Specs Available";
                  }
                ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>


      <!-- Start Product Photo -->
      <?php if ($args['product_photo']): ?>
        <div
          class="product-photo group-[.specs-active]:translate-y-[82%] rounded-lg bg-white translate-y-0 top-0 bottom-0 overflow-hidden absolute transition w-full">
          <img src="<?php echo esc_url($args['product_photo']); ?>" alt="<?php echo esc_attr($args['product_name']); ?>"
            class="!h-full w-full object-cover object-center" />
        </div>
      <?php endif; ?>

      <!-- Start Specs Trigger Button -->
      <?php if (!$args['is_series']): ?>
        <div class="card-product-bottom-button absolute bottom-2 left-2 right-2 z-50">
          <button
            class="spec-trigger group flex flex-wrap gap-4 items-center content-between justify-center w-full p-2 rounded-md cursor-pointer bg-transparent hover:bg-black border border-black text-black hover:text-white before:hidden after:hidden group-[.specs-active]:bg-black group-[.specs-active]:text-white group-hover/specsButton:bg-black group-hover/specsButton:text-white">
            <span class="spec-label text-base font-bold tracking-tight leading-none whitespace-nowrap">View Specs</span>
          </button>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <?php
}
