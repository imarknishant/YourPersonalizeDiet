<?php
/* Template Name: step 8 vegetables */
get_header();

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

$meat = $_GET['meat'];

if(!is_array($meat)){
    $meat = explode(',',$meat);
}
/**** Acf fields start ****/
$meatacf = get_field('meats',63);
$meatnotselect = [];
if(!empty($meatacf)){
    foreach($meatacf as $met){
        if(!in_array($met['meat_name'],$meat)){
            $meatnotselect[] = $met['meat_name'];
        }
        
    }
}
$cookiemeat = implode(',',$meatnotselect);
setcookie("meat", $cookiemeat, time() + (86400 * 30), "/");
$_COOKIE['meat'] = $cookiemeat;
/**** Acf fields start ****/
$remove = $_GET['removed'];
if($remove == ''){
    $remove = 0;
}

/** Get Cookies Value **/
$cookieveg = $_COOKIE['vegetables'];

if(!empty($cookieveg)){
    $vgcook = explode(',',$cookieveg);
    if($vgcook[0] == 'null'){
        $vgcount = 0;
    }else{
        $vgcount = count($vgcook);
    }
}

if($vgcount == ''){
    $vgcount = 0;
}

$checkremove = ($remove - $vgcount);
if($checkremove > 0){
   $preUrl = modify_url(array('removed' => ($remove - 0)), $_SERVER['QUERY_STRING']); 
}else{
   $preUrl = modify_url(array('removed' => 0), $_SERVER['QUERY_STRING']);
}

?> 
<script>
$.cookie("meat", '<?php echo $cookiemeat; ?>', {
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
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
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
					  <li><a href="#">7. vegetables</a></li>
                        
                        <?php }else{?>
                       <li><a href="#">4. Health Status</a></li>
                      <li><a href="#">5. Activity</a></li>
                      <li><a href="#">6. Measurement</a></li>
					  <li><a href="#">7. Meat</a></li>
					  <li><a href="#">8. vegetables</a></li>
                        <?php }?>
                    </ol>
                  </nav>
            </div>

            <div class="step-process-box meal-process-box">
                <div class="heading">
                    <h2>Vegetables</h2>
                  <h5>Please click to remove</h5>
                  
                </div>

            <form id="vegetables" method="get" action="<?php echo get_the_permalink(69); ?>">
			    <div class="meat-box radio-btns">
					<div class="row">
                        <?php 
                        $vegetables = get_field('vegetables',$post->ID); 
                        $i = 0;
                        foreach($vegetables as $vg){
                        ?>
						<div class="col-lg-3 col-md-6 mrg-btm">					
							<div class="meat-checkbox <?php if(in_array($vg['vegetable_name'],$vgcook)){ echo 'open'; }
                            if($vgcount == 5 && !in_array($vg['vegetable_name'],$vgcook)){ echo 'addover_lay'; } 
                            if($remove == 10 && !in_array($vg['vegetable_name'],$vgcook) ){ echo 'addover_lay'; }             
                            ?>">
							<div class="form-group">
							  <input type="checkbox" id="html<?php echo $i; ?>" name="vegetables[]" value="<?php echo $vg['vegetable_name']; ?>" <?php if(!in_array($vg['vegetable_name'],$vgcook)){ echo 'checked'; }else if(empty($vgcook)){  echo 'checked'; } ?>  class="veg_checkbox" onchange="validation(this);">
							  <label for="html<?php echo $i; ?>">

								<div class="meal-box">
								  <div class="svg-icon">
                                    <?php echo $vg['vegetable_icon']; ?>
									
                                <h5><?php echo $vg['vegetable_name']; ?></h5>
                                  </div>
							    </div>
							  </label>
							</div>
							</div>
					    </div>
                        <?php 
                        $i++;
                        } ?>
									
			
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
                <input type="hidden" name="meat" value="<?php echo implode(",",$meat); ?>">
				
				
				
				
		</div>									
			<div class="back-continue-btns">
				<a href="<?php echo get_the_permalink(63).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
				<!--<button type="submit" class="btn">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>-->
				<input type="submit" class="btn" name="vegetables_btn" value="Continue">
                <input type="hidden" name="removed" id="removed" value="<?php echo $remove; ?>">
                <input type="hidden" id="total_veg_removed" name="veg_remove" value="<?php echo $vgcount; ?>">
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