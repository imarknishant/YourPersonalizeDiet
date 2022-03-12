<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentysixteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentysixteen
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteen' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'twentysixteen' ),
		'social'  => __( 'Social Links Menu', 'twentysixteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'twentysixteen_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'twentysixteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'twentysixteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'twentysixteen-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}

	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160816', true );

	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteen' ),
		'collapse' => __( 'collapse child menu', 'twentysixteen' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 840 <= $width ) {
		$sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';
	}

	if ( 'page' === get_post_type() ) {
		if ( 840 > $width ) {
			$sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
		}
	} else {
		if ( 840 > $width && 600 <= $width ) {
			$sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		} elseif ( 600 > $width ) {
			$sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return array The filtered attributes for the image markup.
 */
function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		} else {
			$attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
		}
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentysixteen_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list'; 

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args' );

/****** Woocommerce support ********/
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

add_filter( 'woocommerce_product_tabs', 'yikes_remove_description_tab', 20, 1 );

function yikes_remove_description_tab( $tabs ) {

	// Remove the description tab
	if ( isset( $tabs['description'] ) ) unset( $tabs['description'] );      	    
	return $tabs;
}
/**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );


/********POST VIEWS****************/

function getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0 View";
	}
	return $count.' Views';
}
function setPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	}else{
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}
// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


add_action( 'init', 'sennza_register_cpt_tell_your_story' );

function sennza_register_cpt_tell_your_story() {
	$args = array(
		'public' => true,
		'query_var' => 'tell-your-story',
		'rewrite' => array(
			'slug' => 'tell-your-story',
			'with_front' => false
		),
		'supports' => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'revisions',
			'comments'

		),
		'labels' => array(
			'name' => 'Tell Your Story',
			'singular_name' => 'Tell Your Story',
			'add_new' => 'Add New Tell Your Story',
			'add_new_item' => 'Add New Tell Your Story',
			'edit_item' => 'Edit Tell Your Story',
			'new_item' => 'New Tell Your Story',
			'view_item' => 'View Tell Your Story',
			'search_items' => 'Search Tell Your Story',
			'not_found' => 'No Tell Your Story found',
			'not_found_in_trash' => 'No Tell Your Story found in Trash',
		),
	);
	register_post_type( 'tell_your_story', $args );
}

function comment_validation_init() {
	?>        
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			jQuery('#commentform').validate({

				rules: {
					author: {
						required: true,
						minlength: 2
					},

					email: {
						required: true,
						email: true
					},

					comment: {
						required: true,
					}
				},

				messages: {
					author: "Please fill the required field",
					email: "Please enter a valid email address.",
					comment: "Please fill the required field"
				},

				errorElement: "div",
				errorPlacement: function(error, element) {
					element.after(error);
				}

			});
		});
	</script>
	<?php

}
    //add_action('wp_footer', 'comment_validation_init');

function prefix_disable_comment_url($fields) {
	unset($fields['url']);

	return $fields;
}
add_filter('comment_form_default_fields','prefix_disable_comment_url');

//Comment Field Order
add_filter( 'comment_form_fields', 'mo_comment_fields_custom_order' );
function mo_comment_fields_custom_order( $fields ) {

$a = $_SERVER['REQUEST_URI'];
if (strpos($a, 'tell-your-story') !== false) {

		$comment_field = $fields['comment'];
		$author_field = $fields['author'];
		$email_field = $fields['email'];
		$url_field = $fields['url'];
		$cookies_field = $fields['cookies'];
		unset( $fields['comment'] );
		unset( $fields['author'] );
		unset( $fields['email'] );
		unset( $fields['url'] );
		unset( $fields['cookies'] );

   // the order of fields is the order below, change it as needed:
		$fields['author'] = '<div class="col-lg-6 wrap"><div class="form-group">' . '<input class="form-control" placeholder="Your Name *" id="author" name="author" type="text" required value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div></div>';

		$fields['email'] = '<div class="col-lg-6 wrap"><div class="form-group">' .  '<input class="form-control" placeholder="Your E-mail" id="email" name="email" type="text" required value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div></div>';

		$fields['url'] = $url_field;

		$fields['comment'] = '<div class="col-lg-12 wrap"><div class="form-group"><textarea class="form-control" placeholder="Your Message*" id="comment" name="comment" required aria-required="true"></textarea></div></div>';

		$fields['cookies'] = $cookies_field;

   // done ordering, now return the fields:
		return $fields;
}else{
    
    return $fields;
}



}

function my_wpcf7_form_elements($html) {
    $text = 'Position';
    $html = str_replace('<option value="">---</option>', '<option value="">' . $text . '</option>', $html);
    return $html;
}
add_filter('wpcf7_form_elements', 'my_wpcf7_form_elements');

add_filter('wpcf7_autop_or_not', '__return_false');

 
if ( ! function_exists( 'tuts_faq_cpt' ) ) {
 
// register custom post type
    function tuts_faq_cpt() {
 
        // these are the labels in the admin interface, edit them as you like
        $labels = array(
            'name'                => _x( 'FAQs', 'Post Type General Name', 'tuts_faq' ),
            'singular_name'       => _x( 'FAQ', 'Post Type Singular Name', 'tuts_faq' ),
            'menu_name'           => __( 'FAQ', 'tuts_faq' ),
            'parent_item_colon'   => __( 'Parent Item:', 'tuts_faq' ),
            'all_items'           => __( 'All Items', 'tuts_faq' ),
            'view_item'           => __( 'View Item', 'tuts_faq' ),
            'add_new_item'        => __( 'Add New FAQ Item', 'tuts_faq' ),
            'add_new'             => __( 'Add New', 'tuts_faq' ),
            'edit_item'           => __( 'Edit Item', 'tuts_faq' ),
            'update_item'         => __( 'Update Item', 'tuts_faq' ),
            'search_items'        => __( 'Search Item', 'tuts_faq' ),
            'not_found'           => __( 'Not found', 'tuts_faq' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'tuts_faq' ),
        );
        $args = array(
            // use the labels above
            'labels'              => $labels,
            // we'll only need the title, the Visual editor and the excerpt fields for our post type
            'supports'            => array( 'title', 'editor', 'excerpt', ),
            // we're going to create this taxonomy in the next section, but we need to link our post type to it now
            'taxonomies'          => array( 'tuts_faq_tax' ),
            // make it public so we can see it in the admin panel and show it in the front-end
            'public'              => true,
            // show the menu item under the Pages item
            'menu_position'       => 20,
            // show archives, if you don't need the shortcode
            'has_archive'         => true,
        );
        register_post_type( 'tuts_faq', $args );
 
    }
 
    // hook into the 'init' action
    add_action( 'init', 'tuts_faq_cpt', 0 );
 
}
 
// Method 1: Filter.
function my_acf_google_map_api( $api ){
    $api['key'] = 'AIzaSyC5lPdeCYBRi1hqhtasyq_oCNAzhC-93_0';
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

//// Method 2: Setting.
//function my_acf_init() {
//    acf_update_setting('google_api_key', 'AIzaSyBDxzfCX6R16v9VgSM3GoF0Z6MAezcL35g');
//}
//add_action('acf/init', 'my_acf_init');

add_filter( 'loop_shop_per_page', 'items_per_page_in_product_category_archives', 900 );
function items_per_page_in_product_category_archives( $limit ) {
    if( is_product_category() )
        $limit = 25;

    return $limit;
}

/*custom search for product*/
add_action( 'wp_ajax_shop_map_filter', 'shop_map_filter' );
add_action( 'wp_ajax_nopriv_shop_map_filter', 'shop_map_filter' );

function shop_map_filter(){
    $location = $_POST['location'];
    $catID = $_POST['cat_id'];
    $category_link = get_category_link( $catID );
    $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'posts_per_page'        => 4,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field'         => 'term_id', 
                    'terms'         => $catID,
                    'operator'      => 'IN',
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => 'location',
                    'value' => $location,
                    'compare' => 'LIKE',
                ),
            ),
        );
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post();
        global $product; 
        $imgurl = wp_get_attachment_url(get_post_thumbnail_id($product->get_id()));
?>
<div class="place-all-places-box all-places-boxes-similar">
        <div class="left-place-image">
            <figure>
                <img src="<?php echo $imgurl; ?>">
            </figure>
        </div>
        <div class="right-place-content">
            <div class="single-place-box">
            <h6><?php echo $product->get_name(); ?></h6>
            <h4><?php echo $product->get_price_html(); ?></h4>
            <?php $location = get_field('location',$product->get_id()); ?>
            <p><?php echo $location['address']; ?></p>
            <p><?php echo wp_trim_words($product->get_description(),20,''); ?></p>
            <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="btn" data-toggle="modal" data-target=".bd-example-modal-lg">ADD TO CART</a>
            </div>
        </div>
    </div>
<?php
    
        endwhile;
        wp_reset_query();
?>
<div class="place-pagination">
    <?php
    echo paginate_links(
       array(
           'base'      => $category_link.'%_%',
           'format'    => 'page/%#%',
           'current'   => max( 1, get_query_var( 'paged' ) ),
           'total'     => $loop->max_num_pages,
           'prev_text' => 'Prev',
           'next_text' => 'Next',
       )
    );
    ?>
<!--<nav class="woocommerce-pagination">-->
<!--
	<ul class="pagination">
        <li class="page-item"><span aria-current="page" class="page-numbers current">1</span>
        </li><li class="page-item"><a class="page-numbers" href="https://discoverwine.customer-devreview.com/shop/page/2/">2</a></li>
        <li class="page-item"><a class="next page-numbers" href="https://discoverwine.customer-devreview.com/shop/page/2/">Next</a></li>
    </ul></nav>
-->
</div>
<?php
    exit();
}


/*custom search for product*/
add_action( 'wp_ajax_sidefilter', 'sidefilter' );
add_action( 'wp_ajax_nopriv_sidefilter', 'sidefilter' );

function sidefilter(){

    $location = $_POST['search_location'];
    $date = $_POST['date_of_search'];
    $peoples = $_POST['no_of_peoples'];
    $newformat = strtotime($date);
    $startDate = date('Y-m-d', $newformat);
    $cat = $_POST['cat_id'];
    
    $args = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'posts_per_page'        => 4,
        'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field'         => 'term_id', 
                'terms'         => $cat,
            ),
        ),
        'meta_query' => array(
            array(
                'key' => 'location',
                'value' => $location,
                'compare' => 'LIKE',
            ),
        ),
    );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post();
    global $product;
    $imgurl = wp_get_attachment_url(get_post_thumbnail_id($product->get_id()));
?>
<div class="place-all-places-box all-places-boxes-similar">
    <div class="left-place-image">
        <figure>
            <img src="<?php echo $imgurl; ?>">
        </figure>
    </div>
    <div class="right-place-content">
        <div class="single-place-box">
        <h6><?php echo $product->get_name(); ?></h6>
        <h4><?php echo $product->get_price_html(); ?></h4>
        <?php $location = get_field('location',$product->get_id()); ?>
        <p><?php echo $location['address']; ?></p>
        <p><?php echo wp_trim_words($product->get_description(),20,''); ?></p>
        <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="btn" data-toggle="modal" data-target=".bd-example-modal-lg">BOOK NOW</a>
        </div>
    </div>
</div>
<?php
    endwhile;
    wp_reset_query();
    exit();
}


/*custom search for filter wine by colour*/
add_action( 'wp_ajax_filter_by_wine_colour', 'filter_by_wine_colour' );
add_action( 'wp_ajax_nopriv_filter_by_wine_colour', 'filter_by_wine_colour' );

function filter_by_wine_colour(){
    $wineColour = $_POST['winecolour'];
    $category = $_POST['cat_id_wine_colour'];
    $category_link = get_category_link( $category );
    
    if($category == 20){
            if(!empty($wineColour)){
            $args = array(
            'post_type'             => 'product',
    //        'post_status'           => 'publish',
            'posts_per_page'        => 4,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field'         => 'term_id', 
                    'terms'         => $category,
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => 'wine_colour',
                    'value' => $wineColour,
                    'compare' => 'IN',
                ),
            ),
        );
        }else{
            $args = array(
            'post_type'             => 'product',
            'posts_per_page'        => 4,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field'         => 'term_id', 
                    'terms'         => $category,
                ),
            ),
        );
      }
    }elseif($category == 19){
        if(!empty($wineColour)){
            $args = array(
            'post_type'             => 'product',
            'posts_per_page'        => 4,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field'         => 'term_id', 
                    'terms'         => $category,
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => 'place_facilities',
                    'value' => $wineColour,
                    'compare' => 'LIKE',
                ),
            ),
        );
        }else{
            $args = array(
            'post_type'             => 'product',
            'posts_per_page'        => 4,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field'         => 'term_id', 
                    'terms'         => $category,
                ),
            ),
        );
      }
    }
  


    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post();
    global $product;
    $imgurl = wp_get_attachment_url(get_post_thumbnail_id($product->get_id()));
    ?>
<div class="place-all-places-box all-places-boxes-similar">
    <div class="left-place-image">
        <figure>
            <img src="<?php echo $imgurl; ?>">
        </figure>
    </div>
    <div class="right-place-content">
        <div class="single-place-box">
        <h6><?php echo $product->get_name(); ?></h6>
        <h4><?php echo $product->get_price_html(); ?></h4>
        <?php $location = get_field('location',$product->get_id()); ?>
        <p><?php echo $location['address']; ?></p>
        <p><?php echo wp_trim_words($product->get_description(),20,''); ?></p>
        <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="btn" data-toggle="modal" data-target="bd-example-modal-lg">ADD TO CART</a>
        </div>
    </div>
</div>


<?php
    endwhile;
    wp_reset_query();
    ?>
<div class="place-pagination">
    <?php
    echo paginate_links(
       array(
           'base'      => $category_link.'%_%',
           'format'    => 'page/%#%',
           'current'   => max( 1, get_query_var( 'paged' ) ),
           'total'     => $loop->max_num_pages,
           'prev_text' => 'Prev',
           'next_text' => 'Next',
       )
    );
    ?>
<!--<nav class="woocommerce-pagination">-->
<!--
	<ul class="pagination">
        <li class="page-item"><span aria-current="page" class="page-numbers current">1</span>
        </li><li class="page-item"><a class="page-numbers" href="https://discoverwine.customer-devreview.com/shop/page/2/">2</a></li>
        <li class="page-item"><a class="next page-numbers" href="https://discoverwine.customer-devreview.com/shop/page/2/">Next</a></li>
    </ul></nav>
-->
</div>
<?php
    exit();
}


/*custom search for filter price*/
add_action( 'wp_ajax_price_filter', 'price_filter' );
add_action( 'wp_ajax_nopriv_price_filter', 'price_filter' );

function price_filter(){

$min = $_POST['min_price'];
$max = $_POST['max_price'];
$category = $_POST['current_cat'];
    
$min = str_replace("$","",$min);
$min = (int)$min;
$max = str_replace("$","",$max);
$max = (int) $max;
    
    $args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
        'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field'         => 'term_id', 
                'terms'         => $category,
            ),
        ),
		'meta_query' => array(
			array(
				'key' => '_price',
				'value' => array($min,$max),
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			)
		), 
	);
	
	$the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
            global $product;
    $imgurl = wp_get_attachment_url(get_post_thumbnail_id($product->get_id()));
?>
<div class="place-all-places-box all-places-boxes-similar">
    <div class="left-place-image">
        <figure>
            <img src="<?php echo $imgurl; ?>">
        </figure>
    </div>
    <div class="right-place-content">
        <div class="single-place-box">
        <h6><?php echo $product->get_name(); ?></h6>
        <h4><?php echo $product->get_price_html(); ?></h4>
        <?php $location = get_field('location',$product->get_id()); ?>
        <p><?php echo $location['address']; ?></p>
        <p><?php echo wp_trim_words($product->get_description(),20,''); ?></p>
        <a href="<?php echo get_the_permalink($product->get_id()); ?>" class="btn" data-toggle="modal" data-target="bd-example-modal-lg">ADD TO CART</a>
        </div>
    </div>
</div>
<?php
        endwhile;
    endif;
    
?>
<div class="place-pagination">
    <?php
    echo paginate_links(
       array(
           'base'      => $category_link.'%_%',
           'format'    => 'page/%#%',
           'current'   => max( 1, get_query_var( 'paged' ) ),
           'total'     => $loop->max_num_pages,
           'prev_text' => 'Prev',
           'next_text' => 'Next',
       )
    );
    ?>
</div>
<?php
    exit();
}

/**
 * @snippet       Remove Additional Information Tab @ WooCommerce Single Product Page
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
add_filter( 'woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 9999 );
  
function bbloomer_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] ); 
    return $tabs;
}

/**** Stop all updates ****/

// function remove_core_updates(){
// global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
// }
// add_filter('pre_site_transient_update_core','remove_core_updates');
// add_filter('pre_site_transient_update_plugins','remove_core_updates');
// add_filter('pre_site_transient_update_themes','remove_core_updates');

/**
 * @snippet       Plus Minus Quantity Buttons @ WooCommerce Single Product Page
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 4.5
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// -------------
// 1. Show Buttons
  
add_action( 'woocommerce_before_add_to_cart_quantity', 'bbloomer_display_quantity_plus' );
  
function bbloomer_display_quantity_plus() {
    echo '<button type="button" class="minus" >-</button>';
   
}
  
add_action( 'woocommerce_after_add_to_cart_quantity', 'bbloomer_display_quantity_minus' );
  
function bbloomer_display_quantity_minus() {
   echo '<button type="button" class="plus" >+</button>';
}
 
// Note: to place minus @ left and plus @ right replace above add_actions with:
// add_action( 'woocommerce_before_add_to_cart_quantity', 'bbloomer_display_quantity_minus' );
// add_action( 'woocommerce_after_add_to_cart_quantity', 'bbloomer_display_quantity_plus' );
  
// -------------
// 2. Trigger jQuery script
  
add_action( 'wp_footer', 'bbloomer_add_cart_quantity_plus_minus' );
  
function bbloomer_add_cart_quantity_plus_minus() {
   // Only run this on the single product page
   if ( ! is_product() ) return;
   ?>
      <script type="text/javascript">
           
      jQuery(document).ready(function($){   
           
         $('form.cart').on( 'click', 'button.plus, button.minus', function() {
  
            // Get current quantity values
            var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
            var val   = parseFloat(qty.val());
            var max = parseFloat(qty.attr( 'max' ));
            var min = parseFloat(qty.attr( 'min' ));
            var step = parseFloat(qty.attr( 'step' ));
  
            // Change the value if plus or minus
            if ( $( this ).is( '.plus' ) ) {
               if ( max && ( max <= val ) ) {
                  qty.val( max );
               } else {
                  qty.val( val + step );
               }
            } else {
               if ( min && ( min >= val ) ) {
                  qty.val( min );
               } else if ( val > 1 ) {
                  qty.val( val - step );
               }
            }
              
         });
           
      });
           
      </script>
   <?php
}


// Display User IP in WordPress
 
 
function getLocationInfoByIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
   
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
    if($ip_data && $ip_data->geoplugin_countryCode != null){
        $result = $ip_data->geoplugin_countryCode;
    }
    return $result;
}
 
//  function getUserIpAddr(){
//     if(!empty($_SERVER['HTTP_CLIENT_IP'])){
//         //ip from share internet
//         $ip = $_SERVER['HTTP_CLIENT_IP'];
//     }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
//         //ip pass from proxy
//         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//     }else{
//         $ip = $_SERVER['REMOTE_ADDR'];
//     }
//     return $ip;
// }
// add_shortcode('show_ip', 'getLocationInfoByIp');