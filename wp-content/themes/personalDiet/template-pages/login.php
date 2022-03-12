<?php
/* Template Name: Login */
get_header();

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
            <h2>Log in</h2>
          </div>

          <div class="signin-box">
              <form id="login_form" method="post" action="">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="login_email" placeholder="David@info.com">
                </div>

              <div class="form-group">
                  <label>Password</label>
                  <input id="pass_log_id" type="password" class="form-control" name="login_password" placeholder="************">
                  <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span>
              </div>

            <div class="frgt-pswrd">
              <a href="#" data-toggle="modal" data-target="#exampleModalCenter">Forgot your password?</a>
            </div>

              <input type="submit" class="btn" value="Log in">
              <input type="hidden" name="action" value="user_login">
 
<!--              <p>Don't have an account? <a href="javascript:void(0);">Sign in</a></p>-->

              <div class="social-btns">
                <a href="https://yourpersonalizeddiet.com/wp-login.php?loginSocial=facebook" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="facebook" data-popupwidth="475" data-popupheight="175">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fb.png"> Continue with facebook
                </a>  
                <a href="https://yourpersonalizeddiet.com/wp-login.php?loginSocial=google" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="google" data-popupwidth="600" data-popupheight="600">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gg.png">Continue with google
                </a>
              </div>
              </form>
          </div>
      </div>
  </section>
  </main>

  <div class="modal fade modal-forgot" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="forgot_pass_form" method="post" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLongTitle">Forgotten Password</h4>
          <p>Enter your email to reset your password</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" name="email" placeholder="david@gmail.com">
            </div>
          
        </div>
        <div class="modal-footer">
        
          <!--<button type="button" class="btn btn-primary">Reset</button>-->
            <input type="submit" class="btn btn-primary" value="Reset">
            <input type="hidden" name="action" value="forgot_password">
        </div>
      </div>
          </form>
    </div>
  </div>

<?php
get_footer();
?>