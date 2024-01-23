<?php

/**
 * Template Name: 404 Template
 */

get_header(); ?>

<section class="grid-main hero hero-sub md:min-h-[250px] lg:min-h-[300px]">
  <div
    class="theme-white hero-content col-start-1 col-end-13 p-5 pt-10 md:p-10 xl:px-20 xl:py-10 flex flex-col justify-end">
    <div class="product-overview grid-sub">
      <div class="product-meta col-start-1 col-end-13">
        <div class="content-heading">
          <h1 class="h1">
            404 Error: File or page not found
          </h1>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="grid-main content-full">
  <div
    class="theme-white content-full-container p-5 md:p-10 xl:p-20 col-start-1 col-end-13 grid-sub gap-4 md:gap-8 lg:gap-4">

    <div
      class="content-cms col-start-1 col-end-13 lg:col-start-1 lg:col-end-9 prose prose-ol:flex prose-ol:flex-col prose-ol:gap-1">
      Looks like that piece content doesn't exist or removed. Instead, go here:<br />
      <a href="/">SGSTurf Home</a><br />
      <a href="/shop">Shop</a><br />
      <a href="/place-order">Order</a><br />
    </div>
  </div>
</section>

<?php get_footer(); ?>