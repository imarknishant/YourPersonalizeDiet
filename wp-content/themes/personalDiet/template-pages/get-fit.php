<?php
/* Template Name: get fit */
get_header('dashboard');
$gender = get_field('gender','user_'.get_current_user_id());

?>
  <main id="dash-main">
    <div class="get-fit-mainbox">
        <div class="heading">
            <h4>Get Fit in 10</h4>
        </div>

        <div class="row">
        <?php
        $args = array(
            'hide_empty' => false
        );
        $get_fit_cat = get_terms('get-fit-category',$args); 
        foreach($get_fit_cat as $cat){
            if($gender == 'female'){
                $img = get_field('get_fit_image','get-fit-category_'.$cat->term_id);
            }else{
                $img = get_field('male_get_fit_image','get-fit-category_'.$cat->term_id);
            }
            
        ?>    
          <div class="col-lg-4 col-md-6">
            <div class="single-link">
              <figure>
                <img src="<?php echo $img; ?>">
                <a href="<?php echo get_term_link($cat->slug, 'get-fit-category'); ?>" class="video-btn"><?php echo $cat->name; ?></a>
              </figure>
            </div>
          </div>
        <?php }?>

        </div>
    </div>
  </main>

<?php
get_footer('dashboard');
?>