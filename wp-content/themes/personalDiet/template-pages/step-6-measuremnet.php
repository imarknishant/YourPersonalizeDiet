<?php
/* Template Name: step 6 measurement */
get_header();

$gender = $_GET['gender'];
$age    = $_GET['age'];
$weight = $_GET['weight'];
$meal   = $_GET['meal'];
$health_status   = $_GET['health'];
$activity   = $_GET['activity'];
$preUrl = $_SERVER['QUERY_STRING']; 
?>
<script>
$.cookie("activity", '<?php echo $activity; ?>', {
expires : 10,
path    : '/'
});
</script>
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
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 90%;"></div>
                   </div>
            

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li><a>1. I Want To</a></li>
                      <li><a>2. Age</a> </li>
                      <li><a>3. Number Of Meals</a> </li>
                        <?php if($gender == 'male'){?>
                        
                       <li><a>4. Activity</a> </li>
                       <li><a>5. Measurement</a> </li>
                        
                        <?php }else{?>
                       <li><a>4. Health Status</a> </li>
                       <li><a>5. Activity</a> </li>
                       <li><a>6. Measurement</a> </li>
                        <?php }?>
                      
                     
                    </ol>
                  </nav>
            </div>

            <div class="step-process-box meal-process-box">
                <div class="heading">
                    <h2>Measurements</h2>
                </div>

    <form id="measurement" method="get" action="<?php echo get_the_permalink(63); ?>">
        <div class="measurement-tabs step-process-box">
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
				  <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Imperial</a>
				  <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Metric</a>		   
				</div>
			</nav>

        <div class="radio-btns">
            <div class="tab-content" id="nav-tabContent">      
				<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
					<div class="imperial-box">				   
						<div class="row">
							<div class="col-md-12 col-lg-6">
								<div class="form-group">
									<span><img src="<?php echo get_template_directory_uri(); ?>/assets/images/height.png"></span>
									<input type="tel" class="form-control" placeholder="ft" name="imperial_feet">
								</div>
							</div>
							<div class="col-md-12 col-lg-6">
								<div class="form-group">
									<span><img src="<?php echo get_template_directory_uri(); ?>/assets/images/height.png"></span>
									<input type="tel" class="form-control" placeholder="inch" name="imperial_inch">
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<span><img src="<?php echo get_template_directory_uri(); ?>/assets/images/weightt.png"></span>
									<input type="tel" class="form-control" placeholder="Weight" name="imperial_weight">
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<span><img src="<?php echo get_template_directory_uri(); ?>/assets/images/t-weightt.png"></span>
									<input type="tel" class="form-control" placeholder="Target Weight" name="imperial_target_weight">
								</div>
							</div>
						</div>					  
					</div>
				</div>
				
				<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
					<div class="metric-box">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<span><img src="<?php echo get_template_directory_uri(); ?>/assets/images/height.png"></span>
									<input type="tel" class="form-control" placeholder="Height (CM)" name="metric_height">
								</div>
							</div>

						<div class="col-md-12">
							<div class="form-group">
								<span><img src="<?php echo get_template_directory_uri(); ?>/assets/images/weightt.png"></span>
								<input type="tel" class="form-control" placeholder="Weight (KG)" name="metric_weight">
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<span><img src="<?php echo get_template_directory_uri(); ?>/assets/images/t-weightt.png"></span>
								<input type="tel" class="form-control" placeholder="Target Weight (KG)" name="metric_target_weight">
							</div>
						</div>
					</div>

					</div>
				</div>
				
					<input type="hidden" name="gender" value="<?php echo $gender; ?>">
					<input type="hidden" name="weight" value="<?php echo $weight; ?>">
					<input type="hidden" name="age" value="<?php echo $age; ?>">
					<input type="hidden" name="meal" value="<?php echo $meal; ?>">
					<input type="hidden" name="health" value="<?php echo $health-status; ?>">
					<input type="hidden" name="activity" value="<?php echo $activity; ?>">
				                              
            </div>
            <div class="back-continue-btns">
				<a href="<?php echo get_the_permalink(61).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
				<button type="submit" class="btn measurment_cont">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
				<input type="submit" class="btn" name="measurement_btn" value="Continue" style="display:none;">
			</div>
            </div>
			
            </div> 
        </form> 
        </div>
        </div>
    </section>
  </main>
  
<?php
get_footer();
?>
<script>
jQuery(document).ready(function(){
    
    window.onload = function(){
       setTimeout(function(){
           jQuery("#nav-home-tab").click();
       }, 1000);
    };

//    jQuery(document).setTimeout(function(){
//        jQuery("#nav-home-tab").click();
//    },1000);
   
    
   jQuery("#nav-home-tab").click(function(){
       jQuery("input[name='imperial_feet']").prop('required',true);
       jQuery("input[name='imperial_inch']").prop('required',true);
       jQuery("input[name='imperial_weight']").prop('required',true);
       jQuery("input[name='imperial_target_weight']").prop('required',true);
       
       /*****/
       
       jQuery("input[name='metric_height']").prop('required',false);
       jQuery("input[name='metric_weight']").prop('required',false);
       jQuery("input[name='metric_target_weight']").prop('required',false);
   });
    
    jQuery("#nav-profile-tab").click(function(){
       jQuery("input[name='metric_height']").prop('required',true);
       jQuery("input[name='metric_weight']").prop('required',true);
       jQuery("input[name='metric_target_weight']").prop('required',true);
        
        /*****/
        
       jQuery("input[name='imperial_feet']").prop('required',false);
       jQuery("input[name='imperial_inch']").prop('required',false);
       jQuery("input[name='imperial_weight']").prop('required',false);
       jQuery("input[name='imperial_target_weight']").prop('required',false);
   });
    
   jQuery(".measurment_cont").click(function(e){
       e.preventDefault();
       var formValues = jQuery("#measurement").valid();
       if(!formValues){
           toastr.error("Please enter values");
       }else{
           jQuery("input[name='measurement_btn']").click();
       } 
   });
});
</script>