<?php
/* Template Name: step 1 age */
get_header();
global $post;
$gender = $_REQUEST['gender'];
$selectedage = $_COOKIE['age'];
?>  
  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo the_field('banner_image','option'); ?>');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1><?php echo the_field('banner_text','option'); ?></h1>
        </div>
      </div>
    </section>
    <section class="step1 step2-age">
        <div class="container">
            <div class="step-bar">
                <!-- <span></span> -->
                <div class="progress">
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                   </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li><a href="#">1. Age</a> </li>
                     
                    </ol>
                  </nav>
            </div>
        
		<form id="age" role="form" method="get" action="<?php echo get_the_permalink(54); ?>">
            <div class="step-process-box">
                <div class="heading">
                    <h2>Age</h2>
                    <h5>Select Your Age</h5>
                </div>
                <div class="age-select radio-btns">
                    <div class="form-group">
                        <select class="form-control" name="select_age">
                        <?php 
                            $age = get_field('age_repeater',$post->ID);
                            foreach($age as $ag){
                        ?>
                          <option class="center" <?php if($selectedage == $ag['age'].' Years'){ echo "selected"; }?> ><?php echo $ag['age']; ?> Years</option>
                            <?php }?>
                                                  
                        </select>
                    </div>
					<input type="hidden" name="gender" value="<?php echo $gender; ?>">
					
                    <div class="back-continue-btns">
                        <a href="<?php echo get_the_permalink(8); ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                        <!--<button type="submit" class="btn">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>-->
						<input type="submit" class="btn" name="age_btn" value="Continue">
                    </div>
                </div>           
            </div>
			
		</form>
		
        </div>
    </section>
  </main>

<?php
get_footer();
?>