<?php
get_header('dashboard');
$catobject = get_queried_object();
$userId    = get_current_user_id();
$user_gender    = get_field('gender','user_'.$userId);
?>
  <main id="dash-main">
    <div class="get-fit-mainbox single-video">
        <div class="heading">
            <h4><?php echo $catobject->name; ?></h4>
        </div>

        <div class="row">
          <?php
            $args = array(  
                'post_type' => 'get-fit',
                'post_status' => 'publish',
                'posts_per_page' => -1, 
                'order' => 'ASC', 
                'tax_query' => array(
                    array(
                    'taxonomy' => 'get-fit-category',
                    'terms' => $catobject->slug,
                    'field' => 'slug',
                    )
                )
            );

            $loop = new WP_Query( $args ); 
            if($loop->have_posts()){
            while ( $loop->have_posts() ) : $loop->the_post();
            $gender = get_field('gender',$post->ID); 
          if($user_gender==$gender){       
          ?>
          <div class="col-md-4">
            <div class="single-link">
                <iframe src="<?php echo get_field('video_link',get_the_ID()); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <h6><?php echo get_the_title(get_the_ID()); ?></h6>
            </div>
          </div>
          <?php }
            endwhile;
            wp_reset_query();
            }else{
          ?>
                <h6>No Result Found!</h6>
            <?php
                }
            ?>

        </div>
    </div>
  </main>

<?php
get_footer('dashboard'); 
?>