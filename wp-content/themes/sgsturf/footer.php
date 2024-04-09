</main>

<footer id="footer" class="footer theme-black mt-4 p-5 md:p-10 xl:px-24 xl:!py-12">
  <div class="footer-row grid-main !px-0">
    <div class="col-start-1 col-end-13 md:col-start-1 md:col-end-4">
      <?php include('components/logo-orange.php'); ?>
    </div>
    <div class="col-start-1 col-end-13 md:col-start-4 md:col-end-8 xl:col-start-4 xl:col-end-6">
      <div class="nav-footer">
        <h4>Navigation</h4>
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'footer-menu',
            'menu_id' => 'footer-menu',
            'menu_class' => 'site-nav flex flex-col gap-4 md:gap-2 mt-4',
            'container_class' => ''
          )
        );
        ?>
      </div>
    </div>
    <div class="col-start-1 col-end-13 md:col-start-8 md:col-end-13 xl:col-start-6 xl:col-end-8">
      <div class="nav-footer">
        <h4>Social Media</h4>
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'social-media-menu',
            'menu_class' => 'site-nav flex gap-4 flex-col md:gap-2 mt-4',
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'container' => false,
          )
        );
        ?>
      </div>
    </div>
    <div class="col-start-1 col-end-13 md:col-start-4 md:col-end-8 xl:col-start-8 xl:col-end-10">

      <?php if (is_active_sidebar('footer_contact_us')): ?>
        <div id="footer-contact-us" class="widget-area">
          <?php dynamic_sidebar('footer_contact_us'); ?>
        </div>
      <?php endif; ?>

    </div>
    <div class="col-start-1 col-end-13 md:col-start-8 md:col-end-13 xl:col-start-10 xl:col-end-13">

      <div class="flex flex-col gap-5">
        <?php if (is_active_sidebar('footer_location_1')): ?>
          <div id="footer-location-1" class="widget-area">
            <?php dynamic_sidebar('footer_location_1'); ?>
          </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer_location_2')): ?>
          <div id="footer-location-2" class="widget-area">
            <?php dynamic_sidebar('footer_location_2'); ?>
          </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer_location_3')): ?>
          <div id="footer-location-3" class="widget-area">
            <?php dynamic_sidebar('footer_location_3'); ?>
          </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer_location_4')): ?>
          <div id="footer-location-3" class="widget-area">
            <?php dynamic_sidebar('footer_location_4'); ?>
          </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer_location_5')): ?>
          <div id="footer-location-3" class="widget-area">
            <?php dynamic_sidebar('footer_location_5'); ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
  <div class="footer-row grid-main !px-0">
    <div class="col-start-1 col-end-13 md:col-start-4 md:col-end-12 footer-copyright flex flex-col gap-2">
      <p>&copy;<span id="copyright"></span> SGSTURF. ALL RIGHTS RESERVED.</p>
      <p>All website design, text, graphics, the selection and arrangement thereof, and all software are copyright by
        SGS TURF. Any use of materials on this website, including reproduction, modification, distribution or
        republication, without the prior written consent of SGS TURF, is strictly prohibited.</p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
<script>
  const currentYear = new Date().getFullYear();
  const copyrightString = `2015 - ${currentYear}`;
  document.getElementById('copyright').textContent = copyrightString;
</script>
<script>
  window.addEventListener("scroll", function () {
    let header = document.getElementById("header");
    if (window.pageYOffset > 10) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });
</script>
</body>

</html>