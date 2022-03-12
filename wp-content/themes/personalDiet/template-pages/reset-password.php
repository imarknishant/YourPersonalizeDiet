<?php
/*
Template Name: Reset Password
*/
get_header();
if($_GET['id'] != '' && isset($_GET['id'])){
   $uid = $_GET['id'];
}
?>
  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-banner.jpg');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1>YOUR MEAL <span>PLANNER</span></h1>
        </div>
      </div>
    </section>
      
      <section class="how-it-works signin">
      <div class="container">
          <div class="heading">
            <h2>Reset Password</h2>
          </div>

          <div class="signin-box">
              <form id="reset_password" method="post">
                <div class="form-group">
                    <label>Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="************">
                </div>
                <div class="form-group">
                    <label>Re-Enter Password</label>
                    <input type="password" class="form-control" name="re_password" placeholder="************">
               </div>

              <input type="submit" class="btn" value="Reset Password">
              <input type="hidden" name="action" value="password_reset_by_mail">
              <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
 
              <p>Have an account? <a href="<?php echo get_the_permalink(50); ?>">Log in</a></p>
              </form>
          </div>
      </div>
  </section>
</main>
<?php 
get_footer();
?>