  <footer style="background-image: url('<?php echo get_template_directory_uri();?>/images/footer.jpg');">
    <div class="container">
      <figure>
        <img src="<?php echo get_template_directory_uri();?>/images/logo.png">
      </figure>
      <div class="footer-menus">
        <ul>
          <?php
                  $defaults = array(
//                    'theme_location'  => 'primary',
                  'menu'            => 'Footer menu',
                  'container'       => '',
                  'container_class' => '',
                  'container_id'    => '',
                  'menu_class'      => 'menu',
                  'menu_id'         => '',
                  'echo'            => true,
                  'fallback_cb'     => 'wp_page_menu',
                  'before'          => '',
                  'after'           => '',
                  'link_before'     => '',
                  'link_after'      => '',
                  'items_wrap'      => '%3$s',
                  'depth'           => 0,
                  'walker'          => ''
                  );
                wp_nav_menu( $defaults ); 
                ?>
        </ul>
      </div>

      <div class="footer-links">
        <ul>
          <li><a href="<?php echo get_field('facebook_link','option'); ?>"><i class="fa fa-facebook"></i></a></li>
          <li><a href="<?php echo get_field('twitter_link','option'); ?>"><i class="fa fa-twitter"></i></a></li>
          <li><a href="<?php echo get_field('instagram_link','option'); ?>"><i class="fa fa-instagram"></i></a></li>
        </ul>
      </div>


      <div class="copyright">
        <p><?php echo get_field('copy_right_text','option'); ?></p>
      </div>
    </div>
    
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Request Availability</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo do_shortcode('[contact-form-7 id="824" title="Request Availability"]'); ?>
      </div>
    </div>
  </div>
</div>
  </footer>

  <script src="<?php echo get_template_directory_uri();?>/js/jquery-2.2.4.min.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/popper.min.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/bootstrap.min.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/scrolltop.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/slick.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/slick.min.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/slickk.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/slider-two.js"></script>
  <script src="<?php echo get_template_directory_uri();?>/js/custom-data.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/show-content.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/lightgallery.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/js/custom.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<?php 
if($_SERVER['REQUEST_URI'] == '/shop/' || $_SERVER['REQUEST_URI'] == '/wine-tour/' || $_SERVER['REQUEST_URI'] == '/places/' || $_SERVER['REQUEST_URI'] == '/search-result/'){
    ?>
    <script src="<?php echo get_template_directory_uri();?>/js/two-way-slider.js"></script>
<?php
}


// $data = getLocationInfoByIp();
// $countrys = ['Italy','France', 'Germany'];
// $lngCode = ['it','fr','de'];

// $code = strtolower($data);

// if(in_array($code,$lngCode)){
//     echo $code;
//     setcookie("username", "John Carter", time()+30*24*60*60,'/');
// }
wp_footer();
?>
<!--<script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>-->
<!--<script>-->
<!--    Cookies.remove('googtrans');-->
<!--</script>-->
</body>

</html>