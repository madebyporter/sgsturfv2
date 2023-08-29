</main>

<footer id="footer" class="footer theme-black mt-4 px-28 !py-12">
  <div class="footer-row grid-main !px-0">
    <div class="col-start-1 col-end-4">
      <?php include('components/logo-orange.php'); ?>
    </div>
    <div class="md:col-start-4 md:col-end-8 xl:col-start-4 xl:col-end-6">
      <div class="nav-footer">
        <h4>Navigation</h4>
        <nav class="site-nav flex flex-col gap-2 mt-4">
          <a href="#">Our Turf</a>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
        </nav>
      </div>
    </div>
    <div class="md:col-start-8 md:col-end-13 xl:col-start-6 xl:col-end-8">
      <div class="nav-footer">
        <h4>Social Media</h4>
        <nav class="site-nav flex flex-col gap-2 mt-4">
          <a href="#" class="flex gap-2 content-center"><img src="<?php echo SGSTURF_IMAGES_DIR; ?>/icons/social-facebook.svg" alt="Turf">Facebook</a>
          <a href="#" class="flex gap-2 content-center"><img src="<?php echo SGSTURF_IMAGES_DIR; ?>/icons/social-instagram.svg" alt="Turf">Instagram</a>
          <a href="#" class="flex gap-2 content-center"><img src="<?php echo SGSTURF_IMAGES_DIR; ?>/icons/social-youtube.svg" alt="Turf">Youtube</a>
        </nav>
      </div>
    </div>
    <div class="md:col-start-4 md:col-end-8 xl:col-start-8 xl:col-end-10">
      <h4>Contact Us</h4>
      <div class="footer-content flex flex-col gap-2 mt-4">
        <a href="#">info@sgsturf.com</a>
        <p>Mon to Fri<br />7:00am to 4:00pm</p>
      </div>
    </div>
    <div class="md:col-start-8 md:col-end-13 xl:col-start-10 xl:col-end-13">
      <h4>California</h4>
      <div class="footer-content flex flex-col gap-2 mt-4 mb-8">
        <p>690 Ridgeway St.<br />Pomona, CA 91768<br />Phone: (909) 629-8400</p>
      </div>

      <h4>Texas</h4>
      <div class="footer-content flex flex-col gap-2 my-4">
        <p>2001 E Randol Mill Rd Ste 107<br />Arlington TX, 76011<br />Phone: (817) 583-6880</p>
      </div>
    </div>
  </div>
  <div class="footer-row grid-main !px-0">
    <div class="col-start-4 col-end-12 footer-copyright">
      <p class="mb-8">&copy; 2022 SGS TURF. ALL RIGHTS RESERVED. All website design, text, graphics, the selection and arrangement thereof, and all software are copyright by SGS TURF. Any use of materials on this website, including reproduction, modification, distribution or republication, without the prior written consent of SGS TURF, is strictly prohibited.</p>
      <p>SGS TURF is a Belle Luna™️ Company</p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
