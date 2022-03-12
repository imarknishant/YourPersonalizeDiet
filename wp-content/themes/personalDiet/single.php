<?php
get_header();
global $post;
$author_id = $post->post_author;
$featured_img = get_the_post_thumbnail_url($post->ID,'full');
$pageLink = get_the_permalink($post->ID);
?>
<meta property="og:url"           content="<?php echo $pageLink; ?>" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="<?php echo get_the_title($post->ID); ?>" />
<meta property="og:description"   content="<?php echo get_the_content($post->ID); ?>" />
<meta property="og:image"         content="<?php echo $featured_img; ?>" />

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-banner.jpg');" >
      <div class="container">
        <div class="home-banner-content">
          <h1>YOUR MEAL <span>PLANNER</span></h1>
        </div>
      </div>
    </section>

    <section class="how-it-works blog">
        <div class="container">
            <div class="heading">
                <h2>Blog</h2>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="blog-detail-banner">
                        <figure>
                            <img src="<?php echo $featured_img; ?>">
                        </figure>

						<div class="social-profile">
							<div class="social-icons">
								<ul>
									<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $pageLink; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon1.png"></a></li>
									<li><a href="https://www.instagram.com/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon2.png"></a></li>
									<li><a href="https://twitter.com/share?url=<?php echo $pageLink; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon3.png"></a></li>
<!--									<li><a href="javascript:void(0);"><img src="<?php// echo get_template_directory_uri(); ?>/assets/images/icon4.png"></a></li>-->
								</ul>
							</div>


                        <div class="profile-content">
                            <figure>
                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/view.png">
                             
                            </figure>
                            <div class="pf-name">
                              <h6><?php echo get_the_author_meta( 'nicename', $author_id ); ?></h6>
                            </div>
    
							<ul>
							  <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/calenderr.png"><?php echo get_the_date('F j Y',$post->ID); ?></li>
							  <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/time.png"><?php echo get_post_time( 'm:h A', $post->ID ); ?></li>
                                <li><a href="#comments"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/comment.png"></a></li>
							</ul>
                            

                          <h3><?php the_title(); ?></h3>
                           <p><?php the_content(); ?></p>

                          <div class="post-comment-form" id="comments">
                              <ol class="commentlist">
                                <?php
                                    //Gather comments for a specific page/post 
                                    $comments = get_comments(array(
                                        'post_id' => $post->ID,
                                        'status' => 'approve' //Change this to the type of comments to be displayed
                                    ));

                                    //Display the list of comments
                                    wp_list_comments(array(
                                        'per_page' => 10, //Allow comment pagination
                                        'reverse_top_level' => false //Show the oldest comments at the top of the list
                                    ), $comments);
                                ?>
                            </ol>
                              <h3>Post A Comment</h3>
                              
                              <?php if (is_single ()) comment_form(); ?>
                              
                          </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="blog-sidebar">						
					<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
						<div class="form-group">
							<input type="text" class="search-field form-control"
								placeholder="Search"
								value="<?php echo get_search_query() ?>" name="s"
								title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" required />
								<button type="submit" class="search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
						</div>	
					</form>

                        <div class="categories">
                     
                            <h5>Categories</h5>
                        <?php
						$args = array(
								 'post_type' => 'post',
								 'orderby' => 'name',
								 'hide_empty'      => false,
					
						);
                           $cat= get_categories($args);
                        ?>						
                            <ul>
							<?php foreach($cat as $cat_v){ 
							   if($cat_v->name!='Uncategorized'){
							?>
                                <li><a href="<?php echo get_term_link( $cat_v->term_id );?>"><?php echo $cat_v->name; ?></a></li>
							<?php } }?>	
                            </ul>
                        </div>


                        <div class="archive">
                          <h5>Archive</h5>
                            <ul>
                            <?php
                            echo wp_get_archives( array( 'type' => 'daily', 'limit' => 14, 'show_post_count' => 'true' ) );
                            ?>
                            </ul>
                        </div>

                        <div class="recent-blogs">
							<?php
							$args = array(
									 'post_type' => 'post',
									 'posts_per_page' => 4,
									 'post_status'   => 'publish',
                                     'orderby'   => 'date',
                                     'order'     => 'DESC',
									 
							);
							
							$loop = new wp_query($args);
							if($loop->have_posts()):
							while($loop->have_posts()):
							$loop->the_post();
							
							$featured_img = get_the_post_thumbnail_url($post->ID,'full');
                            ?>	
                            <div class="single-recent-blog">
                                <figure>
                                    <img src="<?php echo $featured_img; ?>">
                                </figure>
                                <div class="recent-content">
                                  <h6><a href="<?php echo get_the_permalink($post->ID); ?>"><?php the_title(); ?></a></h6>
                                  <p><?php echo wp_trim_words(get_the_content(),8,'...'); ?></p>
                                  <cite><?php echo get_the_time('F j Y', $post->ID); ?></cite>
                              </div>
                            </div>
					    <?php
						endwhile;
					    wp_reset_query();
						endif;
						?>				
                        </div>                   
                    </div>
                    <div class="blog-add">
                    <?php
                        $adds = get_field("advertisement",10);
                        foreach($adds as $ad){
                            if($ad['image_or_video'] == 'Image'){
                    ?>
                        <a href="#">
                      <figure>
                          <img src="<?php echo $ad['image']; ?>">
                      </figure>
                            </a>
                        <?php
                            }else{
                        ?>
                         <a href="#">
                        <figure class="video-figure">
                          <video>
                            <source src="<?php echo $ad['video']; ?>" type="video/mp4">
                            
                          </video>
                            <span class="ply-btn"><i class="fa fa-play-circle-o" aria-hidden="true"></i></span>
                        </figure>
                        </a>
                        <?php
                            }
                        }
                        ?>
                  </div>
                </div>
            </div>
        </div>
    </section>
  </main>

<?php
get_footer();
?>
<script>
jQuery(document).ready(function(){
    jQuery("#submit").addClass('btn');
})
</script>