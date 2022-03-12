<?php
/* Template Name: Step 14 Email address */
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

if(isset($_GET['meat'])){
    $meat = $_GET['meat'];
}else{
    $meat = $_GET['meat_arr'];
}

if(isset($_GET['vegetables'])){
    $vegetables = $_GET['vegetables'];
}else{
    $vegetables = $_GET['vege_arr'];
}

if(isset($_GET['fruits'])){
    $fruits = $_GET['fruits'];
}else{
    $fruits = $_GET['fruits_arr'];
}

if(isset($_GET['grains'])){
    $grains = $_GET['grains'];
}else{
    $grains = $_GET['grains_arr'];
}

if(isset($_GET['dairy'])){
    $dairy = $_GET['dairy'];
}else{
    $dairy = $_GET['dairy_arr'];
}

if(isset($_GET['beans'])){
    $beans = $_GET['beans'];
}else{
    $beans = $_GET['beans_arr'];
}

if(isset($_GET['allergies'])){
    $allergies = $_GET['allergies'];
}else{
    $allergies = $_GET['allergies_arr'];
}

//$fruits     = $_GET['fruits'];
//$grains     = $_GET['grains'];
//$dairy      = $_GET['dairy'];
//$beans      = $_GET['beans'];
//$allergies  = $_GET['allergies'];

if(is_user_logged_in()){
    $userdata = get_userdata(get_current_user_id());
    $useremail = $userdata->data->user_email;
}else{
    $useremail = '';
}

$preUrl = $_SERVER['QUERY_STRING'];

if(!is_array($allergies)){
    $allergies = explode(',',$allergies);
}
/**** Acf fields start ****/
$ale = get_field('allergies',67);
$alenotselect = [];
if(!empty($ale)){
    foreach($ale as $al){
        if(!in_array($al['allergies_name'],$allergies)){
            $alenotselect[] = $al['allergies_name'];
        }
        
    }
}
$cookieale = implode(',',$alenotselect);

/**** Acf fields start ****/

$useremail = $_GET['email'];

?>
<script>
$.cookie("allergies", '<?php echo $cookieale; ?>', {
expires : 10,
path    : '/'
});
</script>
  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-banner.jpg');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1>YOUR MEAL <span>PLANNER</span></h1>
        </div>
      </div>
    </section>


    <section class="step1 step2-age email-address">
        <div class="container">
            <div class="step-bar">
                <!-- <span></span> -->
                <div class="progress">
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 30%;"></div>
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
					  <li><a href="#">11. beans</a></li>
					  <li><a href="#">12. allergies</a></li>
					  <li><a href="#">13. Email address</a></li>
                        
                      <?php }else{?>
                        
                      <li><a href="#">4. Health Status</a></li>
                      <li><a href="#">5. Activity</a></li>
                      <li><a href="#">6. Measurement</a></li>
					  <li><a href="#">7. Meat</a></li>
					  <li><a href="#">8. vegetables</a></li>
					  <li><a href="#">9. fruits</a></li>
					  <li><a href="#">10. grains</a></li>
					  <li><a href="#">11. dairy</a></li>
					  <li><a href="#">12. beans</a></li>
					  <li><a href="#">13. allergies</a></li>
					  <li><a href="#">14. Email address</a></li>
                      <?php }?>
                    </ol>
                  </nav>
            </div>
            <div class="heading email-header">
                <h2>Enter Email Address</h2>
            </div>
            <div class="step-process-box meal-process-box email-content">
                <form id="signup_form" method="post">
					<div class="age-select radio-btns meal">
						<div class="form-group">
						   <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $useremail; ?>">
						</div>

						<div class="check-label">
							<input type="checkbox" id="signup_terms" value="agree">
							<label for="signup_terms">I agree with <a href="<?php echo get_the_permalink(78); ?>"> Terms and Conditions</a> & <a href="<?php echo get_the_permalink(242); ?>"> Privacy Policy</a></label>
						</div>
										   

						<div class="back-continue-btns">
							<a href="<?php echo get_the_permalink(67).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
							
							<input type="submit" class="btn btn-email" name="submit" value="Continue">
							<input type="hidden" name="action" value="User_signup">
                            
							<input type="hidden" name="gender" value="<?php echo $gender; ?>">
                            <input type="hidden" name="age" value="<?php echo $age; ?>" >
                            <input type="hidden" name="weight" value="<?php echo $weight; ?>" >
                            <input type="hidden" name="meal" value="<?php echo $meal; ?>" >
                            <input type="hidden" name="health" value="<?php echo $health_status; ?>" >
                            <input type="hidden" name="activity" value="<?php echo $activity; ?>" >
                            <input type="hidden" name="imperial_feet" value="<?php echo $imperial_feet; ?>" >
                            <input type="hidden" name="imperial_inch" value="<?php echo $imperial_inch; ?>" >
                            <input type="hidden" name="imperial_weight" value="<?php echo $imperial_weight; ?>" >
                            <input type="hidden" name="imperial_target_weight" value="<?php echo $imperial_target_weight; ?>" >
                            <input type="hidden" name="metric_height" value="<?php echo $metric_height; ?>" >
                            <input type="hidden" name="metric_weight" value="<?php echo $metric_weight; ?>" >
                            <input type="hidden" name="metric_target_weight" value="<?php echo $metric_target_weight; ?>" >
                            <input type="hidden" name="meat" value="<?php echo $meat; ?>" >
                            <input type="hidden" name="vegetables" value="<?php echo $vegetables; ?>" >
                            <input type="hidden" name="fruits" value="<?php echo $fruits; ?>" >
                            <input type="hidden" name="grains" value="<?php echo $grains; ?>" >
                            <input type="hidden" name="dairy" value="<?php echo $dairy; ?>" >
                            <input type="hidden" name="beans" value="<?php echo $beans; ?>" >
                            <input type="hidden" name="allergies" value="<?php echo implode(",",$allergies); ?>" >    
                      </div>
					</div>
                </form>

            </div>
        </div>
    </section>
      
      <section class="how-it-works choose-your-plan" style="display:none;">
            <div class="container">
                <div class="heading">
                    <h2>Processing Meal Plan</h2>
                </div>
                <div id="shiva"><span class="count">100%</span></div>
            </div>
        </section>
   
  </main>

<?php
get_footer();
?>