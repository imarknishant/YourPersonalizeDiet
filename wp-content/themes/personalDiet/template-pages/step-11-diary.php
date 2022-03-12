<?php
/* Template Name: step 11 diary */
get_header();

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
$vegetables = $_GET['vegetables'];
$fruits     = $_GET['fruits'];
$grains     = $_GET['grains'];
$remove = $_GET['removed'];

$selectdairy = $_GET['dairy'];



if(!is_array($grains)){
    $grains = explode(',',$grains);
}
/**** Acf fields start ****/
$gra = get_field('grains',74);
$granotselect = [];
if(!empty($gra)){
    foreach($gra as $gr){
        if(!in_array($gr['grains_name'],$grains)){
            $granotselect[] = $gr['grains_name'];
        } 
    }
}

$cookiegra = implode(',',$granotselect);

/**** Acf fields start ****/

/** Get Cookies Value **/
$cookiedai = $_COOKIE['dairy'];
if(!empty($cookiedai)){
    $dacook = explode(',',$cookiedai);
    if($dacook[0] == 'null'){
        $dacount = 0;
    }
}

if($dacount == ''){
    $dacount = 0;
}

$checkremove = ($remove - $dacount);
if($checkremove > 0){
   $preUrl = modify_url(array('removed' => ($remove - $dacount)), $_SERVER['QUERY_STRING']); 
}else{
    $preUrl = modify_url(array('removed' => 0), $_SERVER['QUERY_STRING']);
}

$remove = $remove + $dacount;
?>
<script>
$.cookie("grains", '<?php echo $cookiegra; ?>', {
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
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"></div>
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
					  <li><a href="#">8. fruits</a></li>
					  <li><a href="#">9. grains</a></li>
					  <li><a href="#">10. dairy</a></li>
                        
                        <?php }else{?>
                      <li><a href="#">4. Health Status</a></li>
                      <li><a href="#">5. Activity</a></li>
                      <li><a href="#">6. Measurement</a></li>
					  <li><a href="#">7. Meat</a></li>
					  <li><a href="#">8. vegetables</a></li>
					  <li><a href="#">9. fruits</a></li>
					  <li><a href="#">10. grains</a></li>
					  <li><a href="#">11. dairy</a></li>
                        <?php }?>
                      
                    </ol>
                  </nav>
            </div>
			
        <form id="grains" method="get" action="<?php echo get_the_permalink(72); ?>">
            <div class="step-process-box meal-process-box">
                <?php
                if($remove == 10){
                    $removeMsg = "Maximum ingredients remove limit reached";
                }
                ?>
                <div id="limit_msg" style="text-align:center;"><?php echo $removeMsg; ?></div>
                <div class="heading">
                    <h2>Diary</h2>
                  <h5>Please click to remove</h5>
                </div>
                <div class="meat-box radio-btns dairy-boxes">
                    <div class="row">
                        <?php 
                            $dairy = get_field('dairy',$post->ID);
                            $i=0;
                            foreach($dairy as $da){
                        ?>
                        <div class="col-lg-3 col-md-6 mrg-btm">   
							<div class="meat-checkbox <?php 
                            if($remove == 10 && !in_array($da['dairy_name'],$dacook) ){ echo 'addover_lay'; }
                            if(in_array($da['dairy_name'],$dacook)){ echo ' open'; }
                            ?>">
							<div class="form-group">
							  <input type="checkbox" id="html<?php echo $i; ?>" name="dairy[]" value="<?php echo $da['dairy_name']; ?>" <?php if(!in_array($da['dairy_name'],$dacook)){ echo 'checked'; }else if(empty($dacook)){  echo 'checked'; } ?> onchange="validation(this);" class="veg_checkbox">
							  <label for="html<?php echo $i; ?>">

								<div class="meal-box">
								  <div class="svg-icon">
                                    <?php echo $da['dairy_icon']; ?>
									
							<h5><?php echo $da['dairy_name']; ?></h5>
							</div>
							</div>
							  </label>
							</div>
							</div>
                        </div>
                        <?php
                         $i++;   
                        }?>

				
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
                <input type="hidden" name="meat" value="<?php echo $meat; ?>">
                <input type="hidden" name="vegetables" value="<?php echo $vegetables; ?>">
                <input type="hidden" name="fruits" value="<?php echo $fruits; ?>">
                <input type="hidden" name="grains" value="<?php echo implode(",",$grains); ?>">
				        
            </div>
                           
                <div class="back-continue-btns">
                    <a href="<?php echo get_the_permalink(74).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                    <!--<button type="submit" class="btn">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>-->
					<input type="submit" class="btn" name="dairy_btn" value="Continue">
                    <input type="hidden" name="removed" id="removed" value="<?php echo $remove; ?>">
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