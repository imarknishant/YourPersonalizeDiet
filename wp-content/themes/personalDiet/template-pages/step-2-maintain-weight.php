<?php
/* Template Name: step 2 maintain weight */
get_header();

$gender = $_REQUEST['gender'];
$age    = $_GET['select_age'];
$preUrl = $_SERVER['QUERY_STRING']; 
$selecteweight = $_COOKIE['maintain-weight'];
?>
<script>
$.cookie("age", '<?php echo $age; ?>', {
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
	
    <section class="step1">
        <div class="container">
            <div class="step-bar">
                <!-- <span></span> -->
                <div class="progress">
                    <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 10%;"></div>
                   </div>
            

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li><a href="#">2. I Want To</a></li>
                      <li><a href="#"></a> </li>
                    
                    </ol>
                  </nav>
            </div>

            <div class="step-process-box">
                <div class="heading">
                    <h2>I Want To</h2>
                </div>
            
			<form id="weight_lose" method="get" action="<?php echo get_the_permalink(59); ?>" role="form">
                <div class="radio-btns">
					<div class="form-group">				   
						<input type="radio" id="test1" name="weight_lose" value="Lose Weight" <?php if($selecteweight == 'Lose Weight'){ echo 'checked'; }?> >
						<label for="test1">Lose Weight</label>
					</div>
					<div class="form-group">						
						<input type="radio" id="test2" name="weight_lose" value="Maintain Weight" <?php if($selecteweight == 'Maintain Weight'){ echo 'checked'; }?> >
						<label for="test2">Maintain Weight</label>
					</div>

					<div class="form-group">					   
						<input type="radio" id="test3" name="weight_lose" value="Build Up" <?php if($selecteweight == 'Build Up'){ echo 'checked'; }?> >
						<label for="test3">Build Up</label>
					</div>
					<input type="hidden" name="gender" value="<?php echo $_GET['gender']; ?>" required>
					<input type="hidden" name="age" value="<?php echo $age; ?>" required>

					<div class="back-continue-btns">
						<a href="<?php echo get_the_permalink(52).'?'.$preUrl; ?>" class="btn btn-back"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
						<button type="submit" class="btn weight_lose_cont">Continue <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
						
						<input type="submit" class="btn" name="weight_lose_btn" value="Continue" style="display:none;">
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
   jQuery(".weight_lose_cont").click(function(e){
       e.preventDefault();
       var formValues = jQuery("input[name='weight_lose']:checked").val();
       if(formValues == '' || formValues == null){
           toastr.error("Please select value");
       }else{
           jQuery("input[name='weight_lose_btn']").click();
       } 
   });
});
</script>