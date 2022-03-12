  <footer id="footer">
    <div class="container">
      <div class="footer-content">
        <div class="social-icons">
          <h4>Follow Us</h4>
          <ul>
            <li><a href="<?php the_field('facebook','option'); ?>" target="_blank"><i class="fa fa-facebook-f"></i></a></li>
            <li><a href="<?php the_field('instagram','option'); ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href="<?php the_field('youtube_play','option'); ?>" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
          </ul>
        </div>
        <div class="terms">
            <?php  
			   $args = array(
			            'menu' => 'Footer menu',
			   );
			   
			   wp_nav_menu($args);
			
			?>
        </div>
      </div>

      <div class="footer-copyright">
        <p>Copyright © <?php echo date('Y'); ?> All rights reserved.</p>
      </div>
    </div>
  </footer>
  <!-- jQuery first, then Bootstrap JS. -->

  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/popper.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/bootstrap.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/aos.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/slick.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
  <?php
if($_GET['plan'] == 'no'){
?>

<script>
jQuery(document).ready(function(){
   toastr.error("YOU HAVE NO ACTIVE PLAN");
});
</script>
<?php }?>

  <?php if(!is_page(array(8))){ ?>
  <script src="//code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
  <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <?php } ?>
  
  
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/custom-data.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/quiz-validation.js"></script>
  
  <?php //wp_footer(); ?>
  
</body>

</html>