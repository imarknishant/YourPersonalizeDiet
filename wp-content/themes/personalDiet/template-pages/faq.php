<?php
/* Template Name: Faq */
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


    <section class="how-it-works faq">
        <div class="container">
            <div class="heading">
                <h2>FAQ</h2>
            </div>

            <div class="container" id="faq-container">
    
    
                <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                      <div class="diet-faq">
                   <div id="accordion" role="tablist">
				   
				<?php
				$args = array(
                    'post_type' => 'personal_faq',
                    'post_status' => 'publish',
                    'posts_per_page' =>  8,
                    'paged' => 1,
				);
				
				$loop = new wp_query($args);
				if($loop->have_posts()):
				$i=0;
				while($loop->have_posts()): $loop->the_post();

                ?>				
                  <div class="card">
                    <div class="card-header" role="tab" id="heading<?php echo $i; ?>">
                     
                        <a class="collapse" data-toggle="collapse" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                            <?php the_title(); ?>
                        </a>
                    
                </div>
                <div id="collapse<?php echo $i; ?>" class="collapse <?php if($i==0){ echo 'show'; } ?>" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion" style="">
                      <div class="card-body">
                        <?php echo the_content(); ?>
                      </div>
                </div>
                </div>
                <?php
				$i++;
				endwhile;
				wp_reset_query();
				endif;
				
				?>
               
                  </div>
                </div>
          
                    </div>
                  
                  </div>
				  <div class="faq-load-btn">
                      <input type="hidden" name="current_number" value="<?php echo $i; ?>">
                      <a href="javascript:void(0);" class="btn" id="load_more_faq">Load More</a>
                 </div>
            </div>
        </div>
    </section>
    

   
  </main>

<?php
get_footer();
?>