<?php
/* Template Name: step 4 health status */
get_header();

$gender = $_GET['gender'];
$age    = $_GET['age'];
$weight = $_GET['weight'];
$meal   = $_GET['meal'];
$preUrl = $_SERVER['QUERY_STRING']; 
?>
<script>
$.cookie("number_of_meals", '<?php echo $meal; ?>', {
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
    <section class="step1 step2-age">
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
                  <li><a href="#">3. Number of Meals</a> </li>
                  <?php if($gender == 'male'){?>
                  <li><a>4. Activity</a> </li>
                  <?php }else{?>
                  <li><a>4. Health Status</a> </li>
                  <?php }?>
                  
                </ol>
              </nav>
                
            </div>
            <?php if($gender == 'male'){?>
              
            <form id="activity_form" method="get" action="<?php echo get_the_permalink(38); ?>">
            <div class="step-process-box">
                <div class="heading">
                    <h2>Activity</h2>
                    <h5>Select Current Activity Level</h5>
                </div>
                <div class="radio-btns">
                    <div class="form-group">
                    <input type="radio" id="test1" name="activity" value="Low (Light Activity)">
                    <label for="test1">Low (Light Activity)</label>
                </div>
                <div class="form-group">
                    <input type="radio" id="test2" name="activity" value="Medium (Exercise 1-3 times per week)">
                    <label for="test2">Medium (Exercise 1-3 times per week)</label>
                </div>
                <div class="form-group">
                   
                    <input type="radio" id="test3" name="activity" value="High (Exercise 5 times a Week/Extreme (Athlets))">
                    <label for="test3">High (Exercise 5 times a Week/Extreme (Athlets))</label>
                </div>
					<input type="hidden" name="gender" value="<?php echo $gender; ?>">
					<input type="hidden" name="weight" value="<?php echo $weight; ?>">
					<input type="hidden" name="age" value="<?php echo $age; ?>">
					<input type="hidden" name="meal" value="<?php echo $meal; ?>">
					<input type="hidden" name="health" value="<?php echo $health_status; ?>">
                <div class="back-continue-btns">
                    <a href="<?php echo get_the_permalink(57).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                    <!--<button type="submit" class="btn">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>-->
					<input type="submit" class="btn" name="activity_btn" value="Continue">
                </div>
                </div>              
            </div>
		</form>
            <?php }else{ ?>
           
            <form id="health_Status" role="form" method="get" action="<?php echo get_the_permalink(61); ?>">
				<div class="step-process-box">
					<div class="heading">
						<h2>Health Status</h2>
						<h5>Choose Your Health Status</h5>
					</div>
					<div class="radio-btns">
						<div class="form-group">
						<input type="radio" id="test1" name="health" value="Pregnant">
						<label for="test1">Pregnant</label>
					</div>
					<div class="form-group">
						<input type="radio" id="test2" name="health" value="Breastfeeding">
						<label for="test2">Breastfeeding</label>
					</div>
					<div class="form-group">
						<input type="radio" id="test3" name="health" value="None of the above">
						<label for="test3">None of the above</label>
					</div>
					<input type="hidden" name="gender" value="<?php echo $gender; ?>">
					<input type="hidden" name="weight" value="<?php echo $weight; ?>">
					<input type="hidden" name="age" value="<?php echo $age; ?>">
					<input type="hidden" name="meal" value="<?php echo $meal; ?>">

					<div class="back-continue-btns">
						<a href="<?php echo get_the_permalink(59).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
						<!--<button type="submit" class="btn">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>-->
						<input type="submit" class="btn" name="health_Status_btn" value="Continue">
					</div>
					</div>   
				</div>
			</form>	
            <?php }?>

        </div>
    </section>
   
  </main>



<!-- Modal -->
<div id="myModal" class="modal fade health-modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Our meal planner isn’t designed for women who are pregnant or exclusively breastfeeding, so unfortunately you’re unable to proceed.</p>

        <p>This is because when you’re pregnant or exclusively breastfeeding your nutritional requirements are different to that of an average adult, as well as some ingredients are not recommended during pregnancy.</p>

        <p>We advise you seek guidance from a health professional instead so they can provide tailored nutritional recommendations (e.g. GP or Registered Dietitian).</p>
      </div>
      <div class="modal-footer">
          <a href="<?php echo site_url(); ?>" class="btn btn-default" >Go to home</a>
<!--        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>

<?php
get_footer();
?>