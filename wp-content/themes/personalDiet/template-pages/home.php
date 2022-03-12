<?php
/* Template Name: Home */
get_header();
global $post;
$featured_img = get_the_post_thumbnail_url($post->ID,'full');
?>
<script>
$.cookie("meat", null, {
expires : 10,
path    : '/'
});
    
$.cookie("vegetables", null, {
expires : 10,
path    : '/'
});
    
$.cookie("fruits", null, {
expires : 10,
path    : '/'
});
    
$.cookie("grains", null, {
expires : 10,
path    : '/'
});

$.cookie("dairy", null, {
expires : 10,
path    : '/'
});
    
$.cookie("beans", null, {
expires : 10,
path    : '/'
});
    
$.cookie("allergies", null, {
expires : 10,
path    : '/'
});
    
$.cookie("age", null, {
expires : 10,
path    : '/'
});
    
$.cookie("maintain-weight", null, {
expires : 10,
path    : '/'
});
    
$.cookie("number_of_meals", null, {
expires : 10,
path    : '/'
});

$.cookie("number_of_meals", null, {
expires : 10,
path    : '/'
});
    
$.cookie("activity", null, {
expires : 10,
path    : '/'
});
</script>
  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo $featured_img;  ?>');" data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1><?php echo get_field('banner_text',$post->ID); ?></h1>
        </div>
      </div>
    </section>


    <section class="select-your-gender" data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="heading text-center">
          <h2>Select your gender</h2>
        </div>
    
		<form id="genderForm" method="post" action="">    
			<div class="both-gender">
			  <div class="female" data-aos="fade-right" data-aos-duration="1500">
				<figure>
				  <img src="<?php echo get_field('female_image',$post->ID); ?>">
				</figure>
				
                <!--<input type="radio" name="select_gender" value="female">--> 
				
				<a href="<?php echo get_the_permalink(52); ?>?gender=<?php echo 'female'; ?>" class="btn" value="female" id="female">Female</a>
			  </div>
			  <div class="male" data-aos="fade-left" data-aos-duration="1500">
				<figure>
				  <img src="<?php echo get_field('male_image',$post->ID); ?>">
				</figure>
				
				<!--<input type="radio" name="select_gender" value="male">-->
				<a href="<?php echo get_the_permalink(52); ?>?gender=<?php echo 'male'; ?>" class="btn" value="male" id="male">Male</a>
			  </div>
			</div>
		</form>	
	
      </div>
    </section>
    

    <section class="they-did-it" data-aos="fade-right" data-aos-duration="1500">
      <div class="container">
        <div class="heading">
          <h2>They did it!</h2>
        </div>

          <div class="row">
    <?php
	
	$args = array(
	        'post_type' => 'testimonial',
			'post_status' => 'publish',
			'posts_per_page' => 2,
	);
	
	$loop = new wp_query($args);
	if($loop->have_posts()):
	$i=0;
	while($loop->have_posts()): $loop->the_post();
	$featured_img = get_the_post_thumbnail_url($post->ID,'full');
	if($i%2==0){
	?>		
        <div class="col-lg-6 mrg-btm" data-aos="fade-right" data-aos-duration="1500">
            <figure>
              <img src="<?php echo $featured_img; ?>">
            </figure>
          </div>
          <div class="col-lg-6 mrg-btm" data-aos="fade-left" data-aos-duration="1500">
            <div class="did-it-box">
              <figure>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/quote.png">
              </figure>

              <p><?php echo the_content(); ?></p>

              <h6><?php echo get_field('name',$post->ID); ?> / <?php echo get_field('age',$post->ID); ?></h6>
            </div>
          </div>
		  
	<?php } else{ ?>

        <div class="col-lg-6 mrg-btm" data-aos="fade-left" data-aos-duration="1500">
            <div class="did-it-box">
              <figure>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/quote.png">
              </figure>

              <p><?php echo the_content(); ?></p>

              <h6><?php echo get_field('name',$post->ID); ?> / <?php echo get_field('age',$post->ID); ?></h6>
            </div>
        </div>
        <div class="col-lg-6 mrg-btm" data-aos="fade-right" data-aos-duration="1500">
            <figure>
              <img src="<?php echo $featured_img; ?>">
            </figure>
        </div>
		  
    <?php } ?>	  
	<?php
	$i++;
    endwhile;
	wp_reset_query();
	endif;
    ?>	
		 
        </div>
		
      </div>
    </section>
  </main>

<?php
get_footer();

?>