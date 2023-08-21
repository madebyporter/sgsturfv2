<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <header id="header" class="header mt-8">
    <div class="header-bar grid-main items-center">
      <div class="flex col-start-1 col-end-3">
        <?php include('components/logo-gray.php'); ?>
      </div>
      <div class="flex justify-center col-start-3 col-end-11">
        <!-- Header Menu -->
        <?php
          wp_nav_menu(array(
            'theme_location' => 'main', // Use 'main' for the "Main" menu location
            'menu_class' => 'nav flex gap-6', // CSS class for the menu
          ));
        ?>
      </div>
      <div class="flex justify-end col-start-11 col-end-13">
        <?php echo do_shortcode('[addify-mini-quote]'); ?>
      </div>
    </div>
  </header>

  <main id="content" class="site-content">