<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-EXVPRLE7TQ"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-EXVPRLE7TQ');
  </script>

  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-KHNCT6Z');</script>
  <!-- End Google Tag Manager -->

</head>

<body <?php body_class(); ?>>

  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KHNCT6Z"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <header id="header"
    class="header sticky mx-auto top-0 w-full z-[100] bg-green-pale rounded-lg overflow-hidden lg:overflow-visible min-h-[51px] translate-y-0 transition-all linear duration-200 delay-0 shadow-none [&.scrolled]:translate-y-2 [&.scrolled]:shadow-xl [&.scrolled]:transition-all [&.scrolled]:w-[94vw] [&.scrolled]:my-auto">
    <div class="header-bar rounded-lg py-2 grid-main items-center bg-green-pale relative z-50 min-h-[51px]">
      <!-- Header Logo -->
      <div
        class="flex col-start-1 col-end-4 sm:col-start-1 sm:col-end-3 md:col-start-1 md:col-end-3 lg:col-start-1 lg:col-end-4">
        <?php include('components/logo-orange-full.php'); ?>
      </div>

      <!-- Header Menu -->
      <div
        class="flex justify-center col-start-5 col-end-9 md:h-[41px] md:col-start-4 md:col-end-10 lg:col-start-4 lg:col-end-10">

        <!-- Start Mobile Menu Trigger -->
        <div class="mobile-menu-trigger cursor-pointer flex items-center lg:hidden">
          <i class="fa-sharp fa-regular fa-grid fa-xl"></i>
        </div>
        <!-- End Mobile Menu Trigger -->

        <!-- Start Desktop Menu -->
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'main',
            // Use 'main' for the "Main" menu location
            'menu_class' => 'nav flex content-center gap-2',
            // CSS class for the menu
            'container_class' => '
              menu-main-container 
              hidden bg-green-pale z-50 fixed left-0 top-[62px] w-screen h-screen 
              lg:flex lg:flex-row lg:items-start lg:relative lg:z-0 lg:left-auto lg:top-auto lg:w-auto lg:h-auto
            '
          )
        );
        ?>
        <!-- End Desktop Menu -->
      </div>
      <!-- Header Buttons -->
      <div
        class="flex gap-2 justify-end col-start-9 col-end-13 sm:col-start-10 sm:col-end-13 lg:col-start-11 lg:col-end-13">
        <a href="./shop/" class="button button-primary-b button-small hidden sm:flex">
          <span class="button-label"><span class="hidden md:inline-block">View</span> Turf</span>
          <span class="button-arrow">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
                fill="#242423" />
            </svg>
          </span>
        </a>
        <a href="./place-order/" class="button button-primary button-small">
          <span class="button-label"><span class="hidden md:inline-block">Place</span> Order</span>
          <span class="button-arrow">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M18.1978 8.94987L12.2806 15.029L11.3766 16L9.48636 14.058L10.4315 13.1293L14.0886 9.32981H2.17207H0.857143V6.62797H2.17207H14.0886L10.4315 2.87071L9.48636 1.89974L11.3766 0L12.2806 0.970976L18.1978 7.05013L19.1429 7.97889L18.1978 8.94987Z"
                fill="#242423" />
            </svg>
          </span>
        </a>
      </div>
    </div>
    <!-- Start Mobile Menu -->
    <div
      class="mobile-menu relative p-4 block lg:hidden z-0 overflow-hidden transition-all duration-500 ease-in-out mt-[-479px] md:mt-[-494px]">
      <?php
      wp_nav_menu(
        array(
          'theme_location' => 'main',
          // Use 'main' for the "Main" menu location
          'menu_class' => 'nav grid grid-cols-1 sm:grid-cols-1 gap-4 w-full',
          // CSS class for the menu
          'container_class' => 'w-full'
        )
      );
      ?>
    </div>
  </header>

  <main id="content" class="site-content transition-opacity">