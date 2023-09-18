<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <header id="header" class="header py-2">
    <div class="header-bar grid-main items-center">
      <div class="flex col-start-1 col-end-7 sm:col-start-1 sm:col-end-4 md:col-start-1 md:col-end-4 lg:col-start-1 lg:col-end-3">
        <?php include('components/logo-gray.php'); ?>
      </div>
      <div class="hidden lg:flex lg:justify-center md:col-start-4 md:col-end-10 lg:col-start-3 lg:col-end-11">
        <!-- Header Menu -->
        <?php
          wp_nav_menu(array(
            'theme_location' => 'main', // Use 'main' for the "Main" menu location
            'menu_class' => 'nav flex gap-2', // CSS class for the menu
          ));
        ?>
      </div>
      <div class="flex gap-2 justify-end col-start-7 col-end-13 sm:col-start-10 sm:col-end-13 lg:col-start-11 lg:col-end-13">
        <a href="./shop/" class="button button-primary-b button-small">
          <span class="button-label"><span class="hidden md:inline-block">View</span> Turf</span>
          <span class="button-arrow">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z" fill="#242423"/>
            </svg>
          </span>
        </a>
        <a href="./place-order/" class="button button-primary button-small">
          <span class="button-label"><span class="hidden md:inline-block">Place</span> Order</span>
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