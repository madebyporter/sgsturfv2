<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <header id="header" class="header mt-4">
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
        <a href="./request-a-quote/" class="button button-secondary button-small">
          <span class="button-label">Request Quote</span>
          <span class="button-arrow">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z" fill="#242423"/>
            </svg>
          </span>
        </a>
      </div>
    </div>
  </header>

  <main id="content" class="site-content">