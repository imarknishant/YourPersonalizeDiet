<?php
/* Template Name: How its works */
get_header();
global $post;
$featured_img = get_the_post_thumbnail_url($post->ID,'full');
?>

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo $featured_img; ?>');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1><?php echo get_field('banner_text',$post->ID); ?></h1>
        </div>
      </div>
    </section>


    <section class="how-it-works">
        <div class="container">
            <div class="heading">
                <h2>How it works</h2>
            </div>
        <?php
		$works = get_field('how_its_works_content',$post->ID);	
		?>
            <div class="row">
			<?php  foreach($works as $works_v){ ?>
                <div class="col-lg-4 col-md-6 mrg-btm">
                    <div class="how-single-box">
                        <figure>
                            <img src="<?php echo $works_v['image']; ?>">
                        </figure>

                        <h6><?php echo $works_v['heading']; ?></h6>
                        <p><?php echo $works_v['content']; ?></p>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </section>

  </main>

<?php
get_footer();
?>