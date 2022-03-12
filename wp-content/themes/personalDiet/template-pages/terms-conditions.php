<?php
/* Template Name: terms and conditions */
get_header();
global $post;
?>

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo the_field('banner_image','option'); ?>');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1><?php echo the_field('banner_text','option'); ?></h1>
        </div>
      </div>
    </section>


   <section class="terms">
       <div class="container">
        <div class="heading">
            <h3><?php the_title(); ?></h3>
        </div>
          <?php the_content(); ?>
       </div>
   </section>
   
  </main>
  
<?php
get_footer();
?>