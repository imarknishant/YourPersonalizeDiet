<?php

/* Template Name: Final step */

get_header();

$gender = $_GET['gender'].'<br>';
$weightLoss = $_GET['weightLoss'].'<br>';
$selectAge = $_GET['selectAge']; 
$healthStatus = $_GET['healthStatus'].'<br>';
$meal      = $_GET['meal'].'<br>';
$activity = $_GET['activity'].'<br>';

$meat    = $_GET['meat'];
$vegetables  = $_GET['vegetables'];
$allergies  = $_GET['allergies'];
$fruits   = $_GET['fruits'];
$beans   = $_GET['beans'];
$grains   = $_GET['grains'];
$dairy   = $_GET['dairy'];

$imperial_feet = $_GET['imperial_feet'];
$imperial_inch = $_GET['imperial_inch'];

$imperial_weight = $_GET['imperial_weight'];
$imperial_target_weight = $_GET['imperial_target_weight'];

$metric_height = $_GET['metric_height'];
$metric_weight = $_GET['metric_weight'];
$metric_target_weight = $_GET['metric_target_weight'];


?>

    <main id="main">
		<section class="home-banner" style="background-image: url('<?php echo the_field('banner_image','option'); ?>');"  data-aos="fade-left" data-aos-duration="1500">
		  <div class="container">
			<div class="home-banner-content">
			  <h1><?php echo the_field('banner_text','option'); ?></h1>
			</div>
		  </div>
		</section>
		<section>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
				<h2> Final step </h2>
				<p> Gender :-   <?php echo $gender; ?> </p>
				<p> Aim :-   <?php echo $weightLoss; ?> </p>	
                <p> Age :-   <?php echo $selectAge; ?> </p>
                <p> HealthStatus :-  <?php echo $healthStatus; ?> </p>				
				</div>		
			</div>		
		</div>
		</section>
	</main>
<?php
get_footer();
?>