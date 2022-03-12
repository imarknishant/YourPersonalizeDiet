 <?php
/* Template Name: step 5 activity */
get_header();

$gender = $_GET['gender'];
$age    = $_GET['age'];
$weight = $_GET['weight'];
$meal   = $_GET['meal'];
if($_REQUEST['health'] != ''){
    $health_status   = $_REQUEST['health'];
}else{
    $health_status   = 'nill';
}
$preUrl = $_SERVER['QUERY_STRING']; 

$seleted_activity = $_COOKIE['activity'];
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
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                   </div>
            

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li><a>1. I Want To</a></li>
                      <li><a>2. Age</a> </li>
                      <li><a>3. Number Of Meals</a> </li>
                        <?php if($gender == 'male'){?>
                        <li><a>4. Activity</a> </li>
                        <?php }else{?>
                        <li><a>4. Health Status</a></li>
                        <?php }?>
                    </ol>
                  </nav>
            </div>
			
        <form id="activity_form" method="get" action="<?php echo get_the_permalink(38); ?>">
            <div class="step-process-box">
                <div class="heading">
                    <h2>Activity</h2>

                    <h5>Select Current Activity Level</h5>
                </div>

                <div class="radio-btns">
                    <div class="form-group">
                   
                    <input type="radio" id="test1" name="activity" value="Low" <?php if($seleted_activity == 'Low'){ echo 'checked'; }?> >
                    <label for="test1">Low (Light Activity)</label>
                </div>
                <div class="form-group">
                    
                    <input type="radio" id="test2" name="activity" value="Medium" <?php if($seleted_activity == 'Medium'){ echo 'checked'; }?> >
                    <label for="test2">Medium (Exercise 1-3 times per week)</label>
                </div>
                <div class="form-group">
                   
                    <input type="radio" id="test3" name="activity" value="High" <?php if($seleted_activity == 'High'){ echo 'checked'; }?> >
                    <label for="test3">High (Exercise 5 times a Week/Extreme (Athlets))</label>
                </div>
				
					<input type="hidden" name="gender" value="<?php echo $gender; ?>">
					<input type="hidden" name="weight" value="<?php echo $weight; ?>">
					<input type="hidden" name="age" value="<?php echo $age; ?>">
					<input type="hidden" name="meal" value="<?php echo $meal; ?>">
					<input type="hidden" name="health" value="<?php echo $health_status; ?>">

                <div class="back-continue-btns">
                    <?php if($gender == 'male'){?>
                    
                    <a href="<?php echo get_the_permalink(59).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                    
                    <?php }else{?>
                    
                    <a href="<?php echo get_the_permalink(57).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
                    
                    <?php }?>
                    
                    <button type="submit" class="btn activity_cont">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
					<input type="submit" class="btn" name="activity_btn" value="Continue" style="display:none;">
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
<script>
jQuery(document).ready(function(){
   jQuery(".activity_cont").click(function(e){
       e.preventDefault();
       var formValues = jQuery("input[name='activity']:checked").val();
       if(formValues == '' || formValues == null){
           toastr.error("Please select value");
       }else{
           jQuery("input[name='activity_btn']").click();
       } 
   });
});
</script>