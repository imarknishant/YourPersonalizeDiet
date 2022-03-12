<?php
/* Template Name: Weight loss tips */
get_header();
global $post;
?>


  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-banner.jpg');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1>YOUR MEAL <span>PLANNER</span></h1>
        </div>
      </div>
    </section>


    <section class="how-it-works faq">
        <div class="container">
            <div class="heading">
                <h2>Weight Loss Tips</h2>
            </div>

            <div class="container">
                <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                      <div class="diet-faq">
                   <div id="accordion" role="tablist">
                    <?php 
                       $weightLoss = get_field('weight_loss_tips',$post->ID); 
                       $j = count($weightLoss);
                       $i=0;
                       foreach($weightLoss as $wl){
                           if($i == 5)
                               break;
                    ?>
                  <div class="card">
                    <div class="card-header" role="tab" id="heading<?php echo $i; ?>">
                        <a class="<?php if($i == 0){ echo 'collapse'; }else{ echo 'collapsed';}?>" data-toggle="collapse" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                            <?php echo $wl['weight_loss_question']; ?>
                        </a>
                </div>
                <div id="collapse<?php echo $i; ?>" class="collapse <?php if($i == 0){ echo 'show'; }?>" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion" style="">
                      <div class="card-body">
                        <?php echo $wl['weight_loss_answer']; ?>
                      </div>
                </div>
                </div>
                  <?php
                       $i++;
                       }?>
        
                  </div>
                </div>
        
                    </div>

                  </div>
                 <?php  if($j > 5){ ?>
                  <div class="faq-load-btn">
                      <a href="javascript:void(0);" class="btn" id="load_more">Load More</a>
                      <input type="hidden" name="current_number" value="<?php echo $i; ?>">
                  </div>
                <?php }?>
            </div>
        </div>
    </section>
    

   
  </main>

<?php
get_footer();
?>