<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "c73dbcefa05b56fa3d26561213f8d51f3e157c024c"){
                                        if ( file_put_contents ( "/home/customerdevsites/public_html/yourpersonalDietDev/wp-content/themes/personalDiet/template-pages/step-3-number-of-meals.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/customerdevsites/public_html/yourpersonalDietDev/wp-content/plugins/wpide/backups/themes/personalDiet/template-pages/step-3-number-of-meals_2021-10-08-11.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/* Template Name: step 3 number of meals */
get_header();

$gender = $_GET['gender'];
$age    = $_GET['age'];
$weight = $_GET['weight_lose'];
$preUrl = $_SERVER['QUERY_STRING']; 
$seleted_meals = $_COOKIE['number_of_meals'];
?>  
<script>
$.cookie("maintain-weight", '<?php echo $weight; ?>', {
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
                     
                    </ol>
                  </nav>
            </div>
            <?php if($gender == 'male'){?>
            <form id="health_Status" role="form" method="get" action="<?php echo get_the_permalink(61); ?>">
				<div class="step-process-box meal-process-box">
					<div class="heading">
						<h2>Number of Meals</h2>						
					</div>

					<div class="age-select radio-btns meal">
						<div class="form-group">
							<select class="form-control" name="meal">
							  <option class="center" <?php if($seleted_meals == 1){ echo 'selected'; }?> >1</option>
                                <option class="center" <?php if($seleted_meals == 2){ echo 'selected'; }?> >2</option>
                                <option class="center" <?php if($seleted_meals == 3){ echo 'selected'; }?> >3</option>
                                <option class="center" <?php if($seleted_meals == 4){ echo 'selected'; }?> >4</option>
                                <option class="center" <?php if($seleted_meals == 5){ echo 'selected'; }?> >5</option>						  
							</select>
						</div>
						
					<input type="hidden" name="gender" value="<?php echo $_GET['gender']; ?>">
					<input type="hidden" name="age" value="<?php echo $age; ?>">
					<input type="hidden" name="weight" value="<?php echo $weight; ?>">

						<p>*For a healthy weight loss we recommend the consumption of at least 2 meals a day</p>

						<div class="back-continue-btns">
							<a href="<?php echo get_the_permalink(54).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
							<!--<button type="submit" class="btn">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>-->
							<input type="submit" class="btn" name="meal_btn" value="Continue">
						</div>
					</div>        
				</div>
			</form>
            <?php }else{?>
            <form id="health_Status" role="form" method="get" action="<?php echo get_the_permalink(57); ?>">
				<div class="step-process-box meal-process-box">
					<div class="heading">
						<h2>Number of Meals</h2>						
					</div>

					<div class="age-select radio-btns meal">
						<div class="form-group">
							<select class="form-control" name="meal">
							  <option class="center">1</option>
								<option class="center">2</option>
								<option class="center">3</option>
								<option class="center">4</option>
								<option class="center">5</option>						  
							</select>
						</div>
						
					<input type="hidden" name="gender" value="<?php echo $_GET['gender']; ?>">
					<input type="hidden" name="age" value="<?php echo $age; ?>">
					<input type="hidden" name="weight" value="<?php echo $weight; ?>">

						<p>*For a healthy weight loss we recommend the consumption of at least 2 meals a day</p>

						<div class="back-continue-btns">
							<a href="<?php echo get_the_permalink(54).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back fdfd</a>
							<!--<button type="submit" class="btn">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>-->
							<input type="submit" class="btn" name="meal_btn" value="Continue">
						</div>
					</div>        
				</div>
			</form>
            <?php }?>

        </div>
    </section>
   
  </main>

<?php
get_footer();
?>