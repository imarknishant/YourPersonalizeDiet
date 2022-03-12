<?php
/* Template Name: Blogs */
get_header();
?>

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo the_field('banner_image','option'); ?>');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1><?php echo the_field('banner_text','option'); ?></h1>
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
                    <div class="row">
			<?php
			
            $args = array(
			         'post_type' => 'post',
					 'posts_per_page' => 6,
					 'post_status'   => 'publish', 			
			);
			
			$loop = new wp_query($args);
			if($loop->have_posts()):
			while($loop->have_posts()):
			$loop->the_post();
			
			$featured_img = get_the_post_thumbnail_url($post->ID,'full');

            ?>			
                <div class="col-md-6">					
                    <div class="single-blog-box">
                        <figure>
                            <img src="<?php echo $featured_img; ?>">
                        </figure>

						<div class="blog-box-content">
						   
							<div class="profile-content">
								<figure>
								  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/view.png">
								 
								</figure>
								<div class="pf-name">
								  <h6><?php echo get_the_author(); ?></h6>
								</div>

								<ul>
								  <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/calenderr.png"><?php echo get_the_time('F j Y', $post->ID); ?></li>
								  <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/time.png"><?php echo get_the_time( '', $post->ID ); ?></li>
								  <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/comment.png"><?php  ?></li>
								</ul>
								
							</div>

							<h5><?php echo the_title(); ?></h5>
							<p><?php echo wp_trim_words(get_the_content(),20,'...'); ?></p>
							<div class="read-more">
								<a href="<?php echo get_the_permalink(get_the_ID()); ?>">Read More <img src="<?php echo get_template_directory_uri(); ?>/assets/images/a-arrow.png"></a>
							</div>
						</div>
                    </div>
                </div>				
			<?php
            endwhile;
            wp_reset_query();
		    endif;
            ?>
                   
            <div class="pagination">
            <?php   
            $big = 999999999; // need an unlikely integer

            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' =>  $loop->max_num_pages
            ) );
            ?>	
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
                            
<!--
                            <ul>
                                <li>February 2021 <cite>(20)</cite></li>
                                <li>January 2021 <cite>(18)</cite></li>
                                <li>December 2020 <cite>(10)</cite></li>
                                <li>November 2020 <cite>(12)</cite></li>
                                <li>October 2020 <cite>(15)</cite></li>
                            </ul>
-->
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
                                  <cite><?php echo date('F j Y'); ?></cite>
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
                        $adds = get_field("advertisement",$post->ID);
                        $j=0;
                        foreach($adds as $ad){
                            if($ad['image_or_video'] == 'Image'){
                    ?>
                        <a href="<?php echo $ad['link']; ?>" target="_blank">
                          <figure>
                              <img src="<?php echo $ad['image']; ?>">
                          </figure>
                        </a>
                        <?php
                            }else{
                        ?>
                        <a href="javascript:void(0);">
                            <figure class="video-figure">
                            <video class="ply-video" id="video_<?php echo $j; ?>" onclick="play_video(<?php echo $j; ?>);">
                             <source src="<?php echo $ad['video']; ?>" type="video/mp4">
                            </video>
                            <span class="ply-btn btnn-ply-<?php echo $j; ?>" onclick="play_video(<?php echo $j; ?>);"><i class="fa fa-play-circle-o" aria-hidden="true"></i></span>
                            </figure>
                        </a>
                        <?php
                            }
                            $j++;
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