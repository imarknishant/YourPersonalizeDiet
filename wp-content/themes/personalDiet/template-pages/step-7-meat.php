<?php
/* Template Name: step 7 meat */
get_header();
global $post;

// Start the session
session_start();

$gender = $_GET['gender'];
$age    = $_GET['age'];
$weight = $_GET['weight'];
$meal   = $_GET['meal'];
$health_status   = $_GET['health'];
$activity   = $_GET['activity'];


$imperial_feet   = $_GET['imperial_feet'];
$imperial_inch   = $_GET['imperial_inch'];
$imperial_weight   = $_GET['imperial_weight'];
$imperial_target_weight   = $_GET['imperial_target_weight'];
$metric_height   = $_GET['metric_height'];
$metric_weight   = $_GET['metric_weight'];
$metric_target_weight   = $_GET['metric_target_weight'];

$remove = $_GET['removed'];
if($remove == ''){
    $remove = 0;
}

$cookiemeat = $_COOKIE['meat'];
if(!empty($cookiemeat)){
    $mtcook = explode(',',$cookiemeat);
    if($mtcook[0] == 'null'){
        $mtcount = 0;
    }
}

?>  

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo the_field('banner_image','option'); ?>');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1><?php echo the_field('banner_text','option'); ?></h1>
        </div>
      </div>
    </section>
    <section class="step1 step2-age step4-meat">
        <div class="container">
            <div class="step-bar">
                <!-- <span></span> -->
                <div class="progress">
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
                   </div>
            

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li><a href="#">1. Age</a></li>
                      <li><a href="#">2. I Want To</a> </li>
                      <li><a href="#">3. Number Of Meals</a> </li>
                        
                      <?php if($gender == 'male'){?>
                     
                      <li><a href="#">4. Activity</a></li>
                      <li><a href="#">5. Measurement</a></li>
					  <li><a href="#">6. Meat</a></li>
                        
                       <?php }else{?>
                       <li><a href="#">4. Health Status</a></li>
                       <li><a href="#">5. Activity</a></li>
                       <li><a href="#">6. Measurement</a></li>
					   <li><a href="#">7. Meat</a></li>
                        <?php }?>
                    </ol>
                </nav>
            </div>

            <div class="step-process-box meal-process-box">
                <div class="heading">
                    <h2>Meat</h2>
                  <h5>Please click to remove</h5>
                </div>

        <form id="activity_form" method="get" action="<?php echo get_the_permalink(65); ?>">
			<div class="meat-box radio-btns">
				<div class="row">
                    <?php 
                    $i = 0;
                    $meats = get_field('meats',$post->ID);
                    foreach($meats as $meat){
                    ?>
					<div class="col-lg-3 col-md-6 mrg-btm">  
						<div class="meat-checkbox <?php if(in_array($meat['meat_name'],$mtcook)){ echo 'open'; }?>">
							<div class="form-group">
							  <input type="checkbox" id="html<?php echo $i; ?>" name="meat[]" value="<?php echo $meat['meat_name']; ?>" <?php if(!in_array($meat['meat_name'],$mtcook)){ echo 'checked'; }else if(empty($mtcook)){  echo 'checked'; } ?> class="veg_checkbox" onchange="validation(this);">
								<label for="html<?php echo $i; ?>">
									<div class="meal-box">
										<div class="svg-icon">
										<?php echo $meat['meat_icon']; ?>
										<h5><?php echo $meat['meat_name']; ?></h5>
										</div>
									</div>
								</label>
							</div>
						</div>
					</div>
                    <?php
                        $i++;
                    }
                    ?>
					
                <input type="hidden" name="gender" value="<?php echo $gender; ?>">
                <input type="hidden" name="weight" value="<?php echo $weight; ?>">
                <input type="hidden" name="age" value="<?php echo $age; ?>">
                <input type="hidden" name="meal" value="<?php echo $meal; ?>">
                <input type="hidden" name="health" value="<?php echo $health-status; ?>">
                <input type="hidden" name="activity" value="<?php echo $activity; ?>">
				
				
				<input type="hidden" name="imperial_feet" value="<?php echo $imperial_feet; ?>">
			    <input type="hidden" name="imperial_inch" value="<?php echo $imperial_inch; ?>">
				<input type="hidden" name="imperial_weight" value="<?php echo $imperial_weight; ?>">
				<input type="hidden" name="imperial_target_weight" value="<?php echo $imperial_target_weight; ?>">
				<input type="hidden" name="metric_height" value="<?php echo $metric_height; ?>">
				<input type="hidden" name="metric_weight" value="<?php echo $metric_weight; ?>">
				<input type="hidden" name="metric_target_weight" value="<?php echo $metric_target_weight; ?>">
									 
                </div>

                <div class="back-continue-btns">
                    <a href="<?php //echo get_the_permalink(38); ?>" class="btn btn-back" onclick="window.history.back()"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                    <input type="submit" class="btn" name="meat_btn" value="Continue">
                </div>
			</div>
            <input type="hidden" name="removed" id="removed" value="<?php echo $remove; ?>">
		</form>	
			  
            </div>
        </div>
    </section>
    

   
  </main>

<?php
get_footer();
?>