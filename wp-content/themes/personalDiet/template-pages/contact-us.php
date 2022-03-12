<?php
/* Template Name: contact us */
get_header();
global $post;
$featured_img = get_the_post_thumbnail_url($post->ID,'full');
?>

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo $featured_img; ?>');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1><?php echo get_field('banner_text',$post->ID); ?></h1>
        </div>
      </div>
    </section>


   <section class="terms contact-us">
    <div class="container">
        <div class="heading">
            <h2>Contact Us</h2>
        </div>
        <div class="contact-us-box">
            <div class="heading">
                <h3>Get In Touch With Our Team</h3>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <?php echo do_shortcode('[contact-form-7 id="141" title="YourpersonalizedDiet form"]'); ?>                   
                </div>
                <div class="col-lg-5">
                    <figure>
                        <img src="<?php echo get_field('contact_us_main_image',$post->ID); ?>">
                    </figure>
                </div>
            </div>
        </div>
    </div>
   </section>
 
  </main>

<?php
get_footer();
?>