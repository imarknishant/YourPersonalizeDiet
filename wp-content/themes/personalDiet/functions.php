<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

// This theme requires WordPress 5.3 or later.
if ( version_compare( $GLOBALS['wp_version'], '5.3', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twenty_twenty_one_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since Twenty Twenty-One 1.0
	 *
	 * @return void
	 */
	function twenty_twenty_one_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Twenty-One, use a find and replace
		 * to change 'twentytwentyone' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'twentytwentyone', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * This theme does not use a hard-coded <title> tag in the document head,
		 * WordPress will provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Add post-formats support.
		 */
		add_theme_support(
			'post-formats',
			array(
				'link',
				'aside',
				'gallery',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat',
			)
		);

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1568, 9999 );

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary menu', 'twentytwentyone' ),
				'footer'  => __( 'Secondary menu', 'twentytwentyone' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		$logo_width  = 300;
		$logo_height = 100;

		add_theme_support(
			'custom-logo',
			array(
				'height'               => $logo_height,
				'width'                => $logo_width,
				'flex-width'           => true,
				'flex-height'          => true,
				'unlink-homepage-logo' => true,
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
		$background_color = get_theme_mod( 'background_color', 'D1E4DD' );
		if ( 127 > Twenty_Twenty_One_Custom_Colors::get_relative_luminance_from_hex( $background_color ) ) {
			add_theme_support( 'dark-editor-style' );
		}

		$editor_stylesheet_path = './assets/css/style-editor.css';

		// Note, the is_IE global variable is defined by WordPress and is used
		// to detect if the current browser is internet explorer.
		global $is_IE;
		if ( $is_IE ) {
			$editor_stylesheet_path = './assets/css/ie-editor.css';
		}

		// Enqueue editor styles.
		add_editor_style( $editor_stylesheet_path );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => esc_html__( 'Extra small', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XS', 'Font size', 'twentytwentyone' ),
					'size'      => 16,
					'slug'      => 'extra-small',
				),
				array(
					'name'      => esc_html__( 'Small', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'S', 'Font size', 'twentytwentyone' ),
					'size'      => 18,
					'slug'      => 'small',
				),
				array(
					'name'      => esc_html__( 'Normal', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'M', 'Font size', 'twentytwentyone' ),
					'size'      => 20,
					'slug'      => 'normal',
				),
				array(
					'name'      => esc_html__( 'Large', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'L', 'Font size', 'twentytwentyone' ),
					'size'      => 24,
					'slug'      => 'large',
				),
				array(
					'name'      => esc_html__( 'Extra large', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XL', 'Font size', 'twentytwentyone' ),
					'size'      => 40,
					'slug'      => 'extra-large',
				),
				array(
					'name'      => esc_html__( 'Huge', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XXL', 'Font size', 'twentytwentyone' ),
					'size'      => 96,
					'slug'      => 'huge',
				),
				array(
					'name'      => esc_html__( 'Gigantic', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XXXL', 'Font size', 'twentytwentyone' ),
					'size'      => 144,
					'slug'      => 'gigantic',
				),
			)
		);

		// Custom background color.
		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'd1e4dd',
			)
		);

		// Editor color palette.
		$black     = '#000000';
		$dark_gray = '#28303D';
		$gray      = '#39414D';
		$green     = '#D1E4DD';
		$blue      = '#D1DFE4';
		$purple    = '#D1D1E4';
		$red       = '#E4D1D1';
		$orange    = '#E4DAD1';
		$yellow    = '#EEEADD';
		$white     = '#FFFFFF';

		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => esc_html__( 'Black', 'twentytwentyone' ),
					'slug'  => 'black',
					'color' => $black,
				),
				array(
					'name'  => esc_html__( 'Dark gray', 'twentytwentyone' ),
					'slug'  => 'dark-gray',
					'color' => $dark_gray,
				),
				array(
					'name'  => esc_html__( 'Gray', 'twentytwentyone' ),
					'slug'  => 'gray',
					'color' => $gray,
				),
				array(
					'name'  => esc_html__( 'Green', 'twentytwentyone' ),
					'slug'  => 'green',
					'color' => $green,
				),
				array(
					'name'  => esc_html__( 'Blue', 'twentytwentyone' ),
					'slug'  => 'blue',
					'color' => $blue,
				),
				array(
					'name'  => esc_html__( 'Purple', 'twentytwentyone' ),
					'slug'  => 'purple',
					'color' => $purple,
				),
				array(
					'name'  => esc_html__( 'Red', 'twentytwentyone' ),
					'slug'  => 'red',
					'color' => $red,
				),
				array(
					'name'  => esc_html__( 'Orange', 'twentytwentyone' ),
					'slug'  => 'orange',
					'color' => $orange,
				),
				array(
					'name'  => esc_html__( 'Yellow', 'twentytwentyone' ),
					'slug'  => 'yellow',
					'color' => $yellow,
				),
				array(
					'name'  => esc_html__( 'White', 'twentytwentyone' ),
					'slug'  => 'white',
					'color' => $white,
				),
			)
		);

		add_theme_support(
			'editor-gradient-presets',
			array(
				array(
					'name'     => esc_html__( 'Purple to yellow', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $yellow . ' 100%)',
					'slug'     => 'purple-to-yellow',
				),
				array(
					'name'     => esc_html__( 'Yellow to purple', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $purple . ' 100%)',
					'slug'     => 'yellow-to-purple',
				),
				array(
					'name'     => esc_html__( 'Green to yellow', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $green . ' 0%, ' . $yellow . ' 100%)',
					'slug'     => 'green-to-yellow',
				),
				array(
					'name'     => esc_html__( 'Yellow to green', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $green . ' 100%)',
					'slug'     => 'yellow-to-green',
				),
				array(
					'name'     => esc_html__( 'Red to yellow', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $yellow . ' 100%)',
					'slug'     => 'red-to-yellow',
				),
				array(
					'name'     => esc_html__( 'Yellow to red', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $red . ' 100%)',
					'slug'     => 'yellow-to-red',
				),
				array(
					'name'     => esc_html__( 'Purple to red', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $red . ' 100%)',
					'slug'     => 'purple-to-red',
				),
				array(
					'name'     => esc_html__( 'Red to purple', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $purple . ' 100%)',
					'slug'     => 'red-to-purple',
				),
			)
		);

		/*
		* Adds starter content to highlight the theme on fresh sites.
		* This is done conditionally to avoid loading the starter content on every
		* page load, as it is a one-off operation only needed once in the customizer.
		*/
		if ( is_customize_preview() ) {
			require get_template_directory() . '/inc/starter-content.php';
			add_theme_support( 'starter-content', twenty_twenty_one_get_starter_content() );
		}

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom line height controls.
		add_theme_support( 'custom-line-height' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Add support for experimental cover block spacing.
		add_theme_support( 'custom-spacing' );

		// Add support for custom units.
		// This was removed in WordPress 5.6 but is still required to properly support WP 5.5.
		add_theme_support( 'custom-units' );
	}
}
add_action( 'after_setup_theme', 'twenty_twenty_one_setup' );

/**
 * Register widget area.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */
function twenty_twenty_one_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'twentytwentyone' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'twentytwentyone' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'twenty_twenty_one_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @global int $content_width Content width.
 *
 * @return void
 */
function twenty_twenty_one_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'twenty_twenty_one_content_width', 750 );
}
add_action( 'after_setup_theme', 'twenty_twenty_one_content_width', 0 );

/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twenty_twenty_one_scripts() {
	// Note, the is_IE global variable is defined by WordPress and is used
	// to detect if the current browser is internet explorer.
	global $is_IE, $wp_scripts;
	if ( $is_IE ) {
		// If IE 11 or below, use a flattened stylesheet with static values replacing CSS Variables.
		wp_enqueue_style( 'twenty-twenty-one-style', get_template_directory_uri() . '/assets/css/ie.css', array(), wp_get_theme()->get( 'Version' ) );
	} else {
		// If not IE, use the standard stylesheet.
		wp_enqueue_style( 'twenty-twenty-one-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
	}

	// RTL styles.
	wp_style_add_data( 'twenty-twenty-one-style', 'rtl', 'replace' );

	// Print styles.
	wp_enqueue_style( 'twenty-twenty-one-print-style', get_template_directory_uri() . '/assets/css/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );

	// Threaded comment reply styles.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Register the IE11 polyfill file.
	wp_register_script(
		'twenty-twenty-one-ie11-polyfills-asset',
		get_template_directory_uri() . '/assets/js/polyfills.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	// Register the IE11 polyfill loader.
	wp_register_script(
		'twenty-twenty-one-ie11-polyfills',
		null,
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
	wp_add_inline_script(
		'twenty-twenty-one-ie11-polyfills',
		wp_get_script_polyfill(
			$wp_scripts,
			array(
				'Element.prototype.matches && Element.prototype.closest && window.NodeList && NodeList.prototype.forEach' => 'twenty-twenty-one-ie11-polyfills-asset',
			)
		)
	);

	// Main navigation scripts.
	if ( has_nav_menu( 'primary' ) ) {
		wp_enqueue_script(
			'twenty-twenty-one-primary-navigation-script',
			get_template_directory_uri() . '/assets/js/primary-navigation.js',
			array( 'twenty-twenty-one-ie11-polyfills' ),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}

	// Responsive embeds script.
	wp_enqueue_script(
		'twenty-twenty-one-responsive-embeds-script',
		get_template_directory_uri() . '/assets/js/responsive-embeds.js',
		array( 'twenty-twenty-one-ie11-polyfills' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'twenty_twenty_one_scripts' );

/**
 * Enqueue block editor script.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_block_editor_script() {

	wp_enqueue_script( 'twentytwentyone-editor', get_theme_file_uri( '/assets/js/editor.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get( 'Version' ), true );
}

add_action( 'enqueue_block_editor_assets', 'twentytwentyone_block_editor_script' );

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function twenty_twenty_one_skip_link_focus_fix() {

	// If SCRIPT_DEBUG is defined and true, print the unminified file.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		echo '<script>';
		include get_template_directory() . '/assets/js/skip-link-focus-fix.js';
		echo '</script>';
	}

	// The following is minified via `npx terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",(function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())}),!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'twenty_twenty_one_skip_link_focus_fix' );

/** Enqueue non-latin language styles
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twenty_twenty_one_non_latin_languages() {
	$custom_css = twenty_twenty_one_get_non_latin_css( 'front-end' );

	if ( $custom_css ) {
		wp_add_inline_style( 'twenty-twenty-one-style', $custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'twenty_twenty_one_non_latin_languages' );

// SVG Icons class.
require get_template_directory() . '/classes/class-twenty-twenty-one-svg-icons.php';

// Custom color classes.
require get_template_directory() . '/classes/class-twenty-twenty-one-custom-colors.php';
new Twenty_Twenty_One_Custom_Colors();

// Enhance the theme by hooking into WordPress.
require get_template_directory() . '/inc/template-functions.php';

// Menu functions and filters.
require get_template_directory() . '/inc/menu-functions.php';

// Custom template tags for the theme.
require get_template_directory() . '/inc/template-tags.php';

// Customizer additions.
require get_template_directory() . '/classes/class-twenty-twenty-one-customize.php';
new Twenty_Twenty_One_Customize();

// Block Patterns.
require get_template_directory() . '/inc/block-patterns.php';

// Block Styles.
require get_template_directory() . '/inc/block-styles.php';

require get_template_directory() . '/inc/plans.php';

// Dark Mode.
require_once get_template_directory() . '/classes/class-twenty-twenty-one-dark-mode.php';
new Twenty_Twenty_One_Dark_Mode();

/**
 * Enqueue scripts for the customizer preview.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_customize_preview_init() {
	wp_enqueue_script(
		'twentytwentyone-customize-helpers',
		get_theme_file_uri( '/assets/js/customize-helpers.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_enqueue_script(
		'twentytwentyone-customize-preview',
		get_theme_file_uri( '/assets/js/customize-preview.js' ),
		array( 'customize-preview', 'customize-selective-refresh', 'jquery', 'twentytwentyone-customize-helpers' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'customize_preview_init', 'twentytwentyone_customize_preview_init' );

/**
 * Enqueue scripts for the customizer.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_customize_controls_enqueue_scripts() {

	wp_enqueue_script(
		'twentytwentyone-customize-helpers',
		get_theme_file_uri( '/assets/js/customize-helpers.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'twentytwentyone_customize_controls_enqueue_scripts' );

/**
 * Calculate classes for the main <html> element.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_the_html_classes() {
	$classes = apply_filters( 'twentytwentyone_html_classes', '' );
	if ( ! $classes ) {
		return;
	}
	echo 'class="' . esc_attr( $classes ) . '"';
}

/**
 * Add "is-IE" class to body if the user is on Internet Explorer.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_add_ie_class() {
	?>
	<script>
	if ( -1 !== navigator.userAgent.indexOf( 'MSIE' ) || -1 !== navigator.appVersion.indexOf( 'Trident/' ) ) {
		document.body.classList.add( 'is-IE' );
	}
	</script>
	<?php
}
add_action( 'wp_footer', 'twentytwentyone_add_ie_class' );



/*********************** Personal Diet *********************/

include 'inc/custom-functions.php';


add_filter('nav_menu_css_class' , 'add_active_class' , 10 , 2);

function add_active_class ($classes, $item) {
  if (in_array('current-menu-item', $classes) ){
    $classes[] = 'active';
  }
  return $classes;
}

/**************** Custom post type *******************/

add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'personal_faq',
        array(
            'labels' => array(
                'name' => __( 'Faq' ),
                'singular_name' => __( 'Faq' )
            ),
	    'supports'      => array( 'title' , 'thumbnail', 'editor', 'page-attributes'),
        'public' => true,
        'has_archive' => true,
        )
    );
}


/******************** Load more functionality in Faq page ************/

  add_action( 'wp_footer', 'my_action_javascript' ); 
function my_action_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {

		var page_count = '<?php echo ceil(wp_count_posts('personal_faq')->publish / 7); ?>';

		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var page = 2;


		jQuery('#load_more_faq').click(function(){		

		var data = {
			'action': 'my_action',
			'page': page
		};

		
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#accordion').append(response);

			if(page_count == page){

				jQuery('#load_more_faq').hide();

			}

			page = page + 1;
		});

	});

	});
	</script>
	<?php
}

// Action
add_action('wp_ajax_my_action', 'my_action');
add_action('wp_ajax_nopriv_my_action', 'my_action');

function my_action(){
				$args = array(
				        'post_type' => 'personal_faq',
						'post_status' => 'publish',
						'paged' => $_POST['page']
				
				);
				
				$loop = new wp_query($args);
				if($loop->have_posts()):
				$i=0;
				while($loop->have_posts()): $loop->the_post();

                ?>				
                  <div class="card">
                    <div class="card-header" role="tab" id="heading<?php echo $i; ?>">
                     
                        <a class="collapse" data-toggle="collapse" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                            <?php the_title(); ?>
                        </a>
                    
                </div>
                <div id="collapse<?php echo $i; ?>" class="collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion" style="">
                      <div class="card-body">
                        <?php echo the_content(); ?>
                      </div>
                </div>
                </div>
                <?php
				$i++;
				endwhile;
				wp_reset_query();
				endif;
			

    exit;
}


/********** Custom post type Testimonial **************/

add_action( 'init', 'testimonial' );
function testimonial() {
    register_post_type( 'testimonial',
        array(
            'labels' => array(
                'name' => __( 'testimonial' ),
                'singular_name' => __( 'testimonial' )
            ),
		    'supports'      => array( 'title' , 'thumbnail', 'editor', 'page-attributes'),
        'public' => true,
        'has_archive' => true,
        )
    );
}



/******************* User signup using email ***************/

    add_action( 'wp_ajax_User_signup', 'User_signup' );
    add_action( 'wp_ajax_nopriv_User_signup', 'User_signup' );

    function User_signup(){
		
		$email                      = $_POST['email'];
        $gender                     = $_POST['gender'];
        
        $age                        = $_POST['age'];
        $weight                     = $_POST['weight'];
        $meal                       = $_POST['meal'];
        $health                     = $_POST['health'];
        $activity                   = $_POST['activity'];
        $imperial_feet              = $_POST['imperial_feet'];
        $imperial_inch              = $_POST['imperial_inch'];
        $imperial_weight            = $_POST['imperial_weight'];
        
        $imperial_target_weight     = $_POST['imperial_target_weight'];
        $metric_weight              = $_POST['metric_weight'];
        $metric_height              = $_POST['metric_height'];
        $metric_target_weight       = $_POST['metric_target_weight'];
        $meat                       = $_POST['meat'];
        $vegetables                 = $_POST['vegetables'];
        $fruits                     = $_POST['fruits'];
        $grains                     = $_POST['grains'];
        $dairy                      = $_POST['dairy'];
        $beans                      = $_POST['beans'];
        $allergies                  = $_POST['allergies'];
            
		$response['url'] = get_the_permalink(42);
		$response['url'] = get_the_permalink(46);
        
        if(email_exists($email)){
            $user = get_user_by( 'email', $email );
            $user_id = $user->ID;
            update_field( 'field_618b6730cbdc0', $gender, 'user_'.$user_id);
        }else{
            $username = $_POST['email'];
            $pass = wp_generate_password(6,true);
            $user_id = wp_create_user($username, $pass, $_POST['email']);
            update_field( 'field_618b6730cbdc0', $gender, 'user_'.$user_id);
        }
		        
        $vars = array('email' => $email, 'gender' => $gender , 'age' => $age , 'weight' => $weight,
                     'meal' => $meal,   'health' => $health , 'activity' => $activity , 'imperial_feet' => $imperial_feet , 'imperial_inch' => $imperial_inch ,'metric_height' => $metric_height,'imperial_weight' => $imperial_weight , 'imperial_target_weight' =>  $imperial_target_weight  , 'metric_weight' => $metric_weight ,  'metric_target_weight' => $metric_target_weight ,  'meat_arr' => $meat , 
                      'vege_arr' => $vegetables , 'fruits_arr' => $fruits , 'grains_arr' => $grains , 'dairy_arr' => $dairy , 'beans_arr' => $beans , 'allergies_arr' => $allergies, 'user_id' => $user_id);
            
        $querystring = http_build_query($vars);

        $url_female = get_the_permalink(42). '?'.$querystring;
		$url_male   = get_the_permalink(46). '?'.$querystring;
        
            
        if($gender == 'male'){ 
            $response['url'] = $url_male; 
        }else{ 
            $response['url'] = $url_female;
        } 
        $response['status'] = 3;
		
		echo json_encode($response);
		exit;
		
        }



/************ Login user ****************/


add_action( 'wp_ajax_user_login', 'user_login' );
add_action( 'wp_ajax_nopriv_user_login', 'user_login' );

function user_login(){
    
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];
    
    $response = array();
    global $wpdb;
  
    if(email_exists($email)){
    $user = get_user_by( 'email', $email );
    $check = $user->ID;
      
    
    /***check if user has made payment ***/
    
      
    $userdata = get_userdata($user->ID);
      
    if($userdata->roles[0] == 'administrator'){
        $is_paid = 'yes';
    }else{
        $is_paid = get_field('is_approved','user_'.$user->ID);
    }
    if($is_paid == 'yes'){
        $result = wp_check_password($password, $user->user_pass, $user->ID);
        $user_meta=get_userdata($user->ID);
        $user_roles=$user_meta->roles;

        if ( $user && $result ){
            $name = get_user_meta($user->ID,'first_name',true).' '.get_user_meta($user->ID,'last_name',true);
            
                wp_set_auth_cookie( $user->ID, true );  
            
                $response['status']="success";
                $response['type'] = 1;
                $response['message']="Welcome ".$name." ! Log in successful! , Redirecting... "; 
                $response['url'] = get_the_permalink(21); 
            }else{
                $response['status']="error";
                $response['type'] = 2;
                $response['message']="Incorrect Password"; 
            }
    }else{
        $response['status']="error";   
        $response['type'] = 4;
        $response['message']="Your account in not active. Please complete payment to activate account";
    }

        
}else{
    $response['status']="error";   
    $response['type'] = 3;
    $response['message']="Oops ! Email doesn't exist."; 
  }
  
  echo json_encode($response);
  exit();
    
}


/******************* Dash profile *****************/

    add_action( 'wp_ajax_dash_profile_save', 'dash_profile_save' );
    add_action( 'wp_ajax_nopriv_dash_profile_save', 'dash_profile_save' );

    function dash_profile_save(){
		
		$first_name = $_POST['first_name'];
        $last_name  = $_POST['last_name'];
        $email      = $_POST['email'];
        $phone      = $_POST['phone'];
        $user_id    = $_POST['user_id'];
        $image       = $_FILES['dash_image'];
        $fullname = $first_name.' '.$last_name;
        
        $response = array();
        
    if($user_id) {
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        update_user_meta($user_id, 'phone_number', $phone);
        
        $parameter = $_FILES['dash_image']['name'];
        if($parameter != ''){
            $wp_filetype = wp_check_filetype( basename($parameter), null );
            $wp_upload_dir = wp_upload_dir();
            move_uploaded_file( $_FILES['dash_image']['tmp_name'], $wp_upload_dir['path']  . '/' . $parameter );

            $attachment = array(
                'guid' => $wp_upload_dir['url'] . '/' . basename( $parameter ), 
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $parameter ) ),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $parameter = $wp_upload_dir['path']  . '/' . $parameter;
            $attach_id = wp_insert_attachment( $attachment, $parameter);

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $attach_data = wp_generate_attachment_metadata( $attach_id, $parameter );
            wp_update_attachment_metadata( $attach_id, $attach_data ); 

            update_user_meta($user_id,'image',$attach_id);
        }
        
        $response['status'] = 1;
        $response['message'] = $fullname." Great ! Profile has been successfully updated.";
        
    }
        
        echo json_encode($response);
        exit;
			
 }



/******************** Forgot password ********************/

add_action( 'wp_ajax_dash_forgot_password', 'forgot_password' );
add_action( 'wp_ajax_nopriv_forgot_password', 'forgot_password' );

function forgot_password(){

    $response = array();

    if(email_exists($_POST['email'])){
        $userData = get_user_by('email', $_POST['email']);
        $fname = get_user_meta($userData->data->ID,'first_name',true);
        $lname = get_user_meta($userData->data->ID,'last_name',true);
        $hash_password = wp_generate_password(6);
        wp_set_password( $hash_password, $userData->data->ID );
        $siteURL = get_the_permalink(8);

        $message = '<head>
           <title>Forgot Password</title>
           <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
            </head>

            <body>
               <table cellspacing="0" border="0" align="center" cellpadding="0" width="600" style="border:1px solid #efefef; margin-top:10px; margin-bottom: 30px;">
                   <tr>
                       <td>
                           <table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">
                               <tr align="center" >
                                   <td style="font-family:arial; padding:20px;"><strong style="display:inline-block;">
                                <a href="'.$siteURL.'" target="_blank"><img src="https://i.postimg.cc/nzQknntj/logo.png" border="0" alt="logo"/></a>
                              </strong></td>
                               </tr>
                           </table>
                           <table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="padding:40px;">
                               <tr>
                    <td style="padding: 0px 0 15px;">
                    <h4 style="font-weight:normal; margin-top: 0px; margin-bottom: 15px; font-size: 16px;">Hi, '.$fname.'  '.$lname.'</h4>
                                   </td>
                               </tr>
                               <tr>
                                  <td style="padding: 0px 0 15px;">
                                       <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                            Please find the below new generated password.
                                        </p>
                                        <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                            New Password - '.$hash_password.'
                                        </p>
                                   </td>
                               </tr>
                                <tr>
                                  <td style="padding: 0px 0 15px;">
                                        <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                            Click here for login - '.$siteURL.'
                                        </p>
                                   </td>
                               </tr>

                           </table>
                       </td>
                   </tr>
               </table>
            </body>';

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            // More headers
            $headers .= 'From: <Info@customerdevsites.com>' . "\r\n";

            wp_mail($_POST['email'],'Forgot Password',$message,$headers);

        $response['status'] = 1;

        }else{

            $response['status'] = 0;

    }

    echo json_encode($response);
    exit();


}


/******************* Update password in Dashboard *******************/

    add_action( 'wp_ajax_dash_update_password', 'update_password' );
    add_action( 'wp_ajax_nopriv_update_password', 'update_password' );
    function update_password(){
        
        $userObject = get_userdata($_POST['user_id']);
        
        $response = array();
        
        if(wp_check_password($_POST['old_pass'], $userObject->user_pass)){
               wp_set_password( $_POST['new_pass'], $_POST['user_id'] );
            
            $response['status'] = 1;
            
        }else{
            
            $response['status'] = 2;
            
        }

    echo json_encode($response);
    exit();
 
    }


/* Reset password by send email */

add_action( 'wp_ajax_password_reset_by_mail', 'password_reset_by_mail' );
add_action( 'wp_ajax_nopriv_password_reset_by_mail', 'password_reset_by_mail' );

function password_reset_by_mail(){
    
    $response = array();
    
    $uid = $_POST['user_id'];
    $user_id = base64_decode($uid);
    
        if($user_id){
               wp_set_password( $_POST['password'], $user_id );

            $response['status'] = 'success';
            $response['message'] = 'Congratulations !! your password set successfully Redirecting ..... ';
            $response['url'] = get_the_permalink(8);

        }else{ 

            $response['status'] = 'fail';

        }
    
    echo json_encode($response);
    exit();
}

/*************** Enqueue Stripe init.php file ************/

add_action( 'wp_ajax_stripe_payment_func', 'stripe_payment_func' );
add_action( 'wp_ajax_nopriv_stripe_payment_func', 'stripe_payment_func' );
function stripe_payment_func(){

    // Include Stripe PHP library 
    require_once 'Stripe/init.php'; 
    
    global $wpdb;
        
    $response = array();
    $emaildata = array();
    
    $amount = $_POST['amount']*100;
    $description = $_POST['plan_type'];
    $user_id = $_POST['userid'];
    $meal_data = array();
    $meal_data['email'] = $_POST['email'];
    $meal_data['gender'] = $_POST['gender'];
    $meal_data['age'] = $_POST['age'];
    $meal_data['weight'] = $_POST['weight'];
    $meal_data['meal'] = $_POST['meal'];
    $meal_data['health'] = $_POST['health'];
    $meal_data['activity'] = $_POST['activity'];
    $meal_data['imperial_feet'] = $_POST['imperial_feet'];
    $meal_data['imperial_inch'] = $_POST['imperial_inch'];
    $meal_data['metric_height'] = $_POST['metric_height'];
    $meal_data['imperial_weight'] = $_POST['imperial_weight'];
    $meal_data['imperial_target_weight'] = $_POST['imperial_target_weight'];
    $meal_data['metric_weight'] = $_POST['metric_weight'];
    $meal_data['metric_target_weight'] = $_POST['metric_target_weight'];
    $meal_data['meat_arr'] = $_POST['meat_arr'];
    $meal_data['vege_arr'] = $_POST['vege_arr'];
    $meal_data['fruits_arr'] = $_POST['fruits_arr'];
    $meal_data['grains_arr'] = $_POST['grains_arr'];
    $meal_data['dairy_arr'] = $_POST['dairy_arr'];
    $meal_data['beans_arr'] = $_POST['beans_arr'];
    $meal_data['allergies_arr'] = $_POST['allergies_arr'];
    
    $quiz_data = serialize($meal_data);
    $meal_dur = $_POST['plan_dur'];

    
    $customerid = get_field( 'stripe_customer_id', 'user_'.$user_id);
    
    $stripe = new \Stripe\StripeClient(
          'sk_test_KPpjpp9s8eWPtpqiAq7roPM200ijBnjCDn'
    );
    
    if($customerid == ''){
        /*** Create Customer Stripe ***/
        $userobject = get_userdata($user_id);
        $name = get_user_meta($user_id,'first_name',true).' '.get_user_meta($user_id,'last_name',true);
        $email = $userobject->data->user_email;

        $stripe_customer = $stripe->customers->create([
          'name' => $name,
          'email' => $email,
          'source' => $_POST['stripeToken'],
        ]);
        
        $customerid = $stripe_customer->id;
        
        update_field( 'field_60e29af234b16', $customerid, 'user_'.$user_id);
        
    }

       $intent = $stripe->charges->create([
         'amount' => $amount,
         'currency' => 'usd',
         'description' => $description,
         'customer' => $customerid,
       ]);

    
    $txn_id = $intent->id;
    $amount = $intent->amount;
    $payment_amount = $amount/100;
    $payment_status = $intent->status;
    $transectionDateTime = date('Y-m-d H:i:s');
    
    if(!empty($intent)){
        
        $result = $wpdb->insert('plan_payments',array(
            'user_id' => $user_id,
            'plan_type' => $_POST['plan_type'],
            'transaction_id' => $txn_id,
            'date_time' => $transectionDateTime,
            'amount' => $payment_amount,
            'payment_method' => 'stripe',
            'payment_status' => $payment_status,
            'meal_data' => $quiz_data,
            'plan_duration' => $meal_dur,
        ));
        
        update_user_meta($user_id , 'first_name' ,$_POST['f_name'] );
		update_user_meta($user_id , 'last_name' ,$_POST['l_name'] );
        
        update_field( 'field_60dda422d8e54', 'yes', 'user_'.$user_id);
        
        $response['status'] = 1;
        $response['url'] = site_url();
        
        /**** send email to client and admin ****/
        $emaildata[] = $user_id;
        $emaildata[] = $payment_amount;
        $emaildata[] = $_POST['plan_type'];
        $emaildata[] = $transectionDateTime;
        
        send_email($emaildata);
    }else{
        $response['status'] = 0;
    }
    
echo json_encode($response);
exit();

}


function send_email($emaildata){
    
    $userobject = get_userdata($emaildata[0]);
    $name = get_user_meta($emaildata[0],'first_name',true).' '.get_user_meta($emaildata[0],'last_name',true);
    $email = $userobject->data->user_email;
    $payment_amount = $emaildata[1];
    $opt_plan = $emaildata[2];
    $tran_date_time = $emaildata[3];
    
    $encodedId = base64_encode($emaildata[0]);
    $resetPasswordLink = get_the_permalink(264).'?id='.$encodedId; 
        
    $siteURL = site_url();
    
    
    if(email_exists($email)){
    $message = '<head>
   <title>Payment success</title>
   <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>

    <body>
       <table cellspacing="0" border="0" align="center" cellpadding="0" width="600" style="border:1px solid #efefef; margin-top:10px; margin-bottom: 30px;">
           <tr>
               <td>
                   <table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">
                       <tr align="center" >
                           <td style="font-family:arial; padding:20px;"><strong style="display:inline-block;">
                                <a href="'.$siteURL.'" target="_blank"><img src="https://i.postimg.cc/nzQknntj/logo.png" border="0" alt="logo"/></a>
                              </strong></td>
                       </tr>
                   </table>
                   <table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="padding:40px;">
                       <tr>
                        <td style="padding: 0px 0 15px;">
                        <h4 style="font-weight:normal; margin-top: 0px; margin-bottom: 15px; font-size: 16px;">Hi, '.$name.'</h4>
                           </td>
                       </tr>
                       <tr>
                          <td style="padding: 0px 0 15px;">
                               <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Thanks for making the payment for plan ('.$opt_plan.').
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Paid Amount - £'.$payment_amount.' on '.$tran_date_time.'
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Please login to check details or if you are new user please reset your password using below link.
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Password Reset - '.$resetPasswordLink.'
                                </p>
                           </td>
                       </tr>

                   </table>
               </td>
           </tr>
       </table>
    </body>';
    }else{
    $message = '<head>
   <title>Payment success</title>
   <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>

    <body>
       <table cellspacing="0" border="0" align="center" cellpadding="0" width="600" style="border:1px solid #efefef; margin-top:10px; margin-bottom: 30px;">
           <tr>
               <td>
                   <table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">
                       <tr align="center" >
                           <td style="font-family:arial; padding:20px;"><strong style="display:inline-block;">
                                <a href="'.$siteURL.'" target="_blank"><img src="https://i.postimg.cc/nzQknntj/logo.png" border="0" alt="logo"/></a>
                              </strong></td>
                       </tr>
                   </table>
                   <table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="padding:40px;">
                       <tr>
                        <td style="padding: 0px 0 15px;">
                        <h4 style="font-weight:normal; margin-top: 0px; margin-bottom: 15px; font-size: 16px;">Hi, '.$name.'</h4>
                           </td>
                       </tr>
                       <tr>
                          <td style="padding: 0px 0 15px;">
                               <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Thanks for making the payment for plan ('.$opt_plan.').
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Paid Amount - '.$payment_amount.' on '.$tran_date_time.'
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Please reset your password using below link.
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Password Reset - '.$resetPasswordLink.'
                                </p>

                           </td>
                       </tr>

                   </table>
               </td>
           </tr>
       </table>
    </body>';
    }
    
        
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: <info@personalizediet.com>' . "\r\n";

        mail($email,'Payment Confirmed',$message,$headers);
        
        $response['status'] = 3;
}



add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

/******************* Custom post type Get Fit ****************/

	function my_custom_post_get_fit() {
		$labels = array(
		'name' => 'Get Fit',
		'singular_name' => 'Get Fit ',
		'add_new' => 'Add Get Fit',
		'add_new_item' => 'Add Get Fit',
		'edit_item' => 'Edit Get Fit',
		'new_item' => 'New Get Fit',
		'all_items' => 'All Get Fit',
		'view_item' => 'View Get Fit',
		'search_items' => 'Search Get Fit',
		'not_found' =>  'No Get Fit found',
		'not_found_in_trash' => 'No Get Fit found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Get Fit'
		);
		
		$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'show_admin_column' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		); 
		
		register_post_type( 'get-fit', $args ); 
	}  
	add_action( 'init', 'my_custom_post_get_fit' );

    function my_taxonomies_get_fit(){
		$labels = array(
		'name' 			=>  'Get Fit Category',
		'add_new_item'  =>  'Add New Get Fit Category',
		'search_items'  =>  'Search Get Fit Category',
		'edit_item' 	=>  'Edit Get Fit Category',
		'menu_name' 	=>  'Get Fit Category'
		);
		$args = array(
		'labels'	    => $labels,
		'hierarchical'  => true,
		'show_admin_column' => true,
		'with_front' => false,
		);
		register_taxonomy( 'get-fit-category', 'get-fit', $args );
	}
	add_action( 'init', 'my_taxonomies_get_fit');

/****************************Custom Post Type Recipe ********************/
	
	function my_custom_post_recipes() {
		$labels = array(
		'name' => 'Recipes',
		'singular_name' => 'Recipes',
		'add_new' => 'Add Recipe',
		'add_new_item' => 'Add New Recipe',
		'edit_item' => 'Edit Recipe',
		'new_item' => 'New Recipe',
		'all_items' => 'All Recipes',
		'view_item' => 'View Recipe',
		'search_items' => 'Search Recipe',
		'not_found' =>  'No Recipe found',
		'not_found_in_trash' => 'No Recipe found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Recipes'
		);
		
		$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'show_admin_column' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		); 
		
		register_post_type( 'recipes', $args ); 
	}  
	add_action( 'init', 'my_custom_post_recipes' );



	
	function my_taxonomies_recipe(){
		$labels = array(
		'name' 			=>  'Recipe Days',
		'add_new_item'  =>  'Add New Recipe Days',
		'search_items'  =>  'Search Recipe Days',
		'edit_item' 	=>  'Edit Recipe Days',
		'menu_name' 	=>  'Recipe Days'
		);
		$args = array(
		'labels'	    => $labels,
		'hierarchical'  => true,
		'show_admin_column' => true,
		'with_front' => false,
		);
		register_taxonomy( 'recipe-days', 'recipes', $args );
	}
	add_action( 'init', 'my_taxonomies_recipe');


    function my_taxonomies_menu_type(){
		$labels = array(
		'name' 			=>  'Menu Type',
		'add_new_item'  =>  'Add New Menu Type',
		'search_items'  =>  'Search Menu Type',
		'edit_item' 	=>  'Edit Menu Type',
		'menu_name' 	=>  'Menu Type'
		);
		$args = array(
		'labels'	    => $labels,
		'hierarchical'  => true,
		'show_admin_column' => true,
		'with_front' => false,
		);
		register_taxonomy( 'menu-type', 'recipes', $args );
	}
	add_action( 'init', 'my_taxonomies_menu_type');

    function my_taxonomies_diet_type() {
		$labels = array(
		'name' 			=>  'Meal Type',
		'add_new_item'  =>  'Add New Meal Type',
		'search_items'  =>  'Search Meal Type',
		'edit_item' 	=>  'Edit Meal Type',
		'menu_name' 	=>  'Meal Type'
		);
		$args = array(
		'labels'	    => $labels,
		'hierarchical'  => true,
		'show_admin_column' => true,
		'with_front' => false,
		);
		register_taxonomy( 'diet-type', 'recipes', $args );
	}
	add_action( 'init', 'my_taxonomies_diet_type');

    function my_taxonomies_recipe_week(){
		$labels = array(
		'name' 			=>  'Week',
		'add_new_item'  =>  'Add New Week',
		'search_items'  =>  'Search Week',
		'edit_item' 	=>  'Edit Week',
		'menu_name' 	=>  'Week'
		);
		$args = array(
		'labels'	    => $labels,
		'hierarchical'  => true,
		'show_admin_column' => true,
		'with_front' => false,
		);
		register_taxonomy( 'week', 'recipes', $args );
	}
	add_action( 'init', 'my_taxonomies_recipe_week');


/*** get substitute ***/

add_action( 'wp_ajax_change_recipe', 'change_recipe' );
add_action( 'wp_ajax_nopriv_change_recipe', 'change_recipe' );
function change_recipe(){
//    ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
    
        session_start();
        global $wpdb;
    
        if(isset($_SESSION['aleg']) && $_SESSION['aleg'] != ''){
            $allergy = explode(',',$_SESSION['aleg']);
        }else{
            $allergy = '';
        }
        
        $final = '';
        $rid = $_POST['rid'];
        $count = (int)$_POST['count'];
        $all = str_replace('\\', '', $_POST['all']);
        $arry = unserialize($all);
    
        $response = array();
        $kia = array();
        $subs = get_field('substitute_recepies',$rid);
    
        if(!empty($allergy)){
            
            foreach($allergy as $allgy){
                $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$allgy.'%" AND `post_id`='.$subs[0]);
                $tmps = (array) $data;

                $data_1 = $wpdb->get_row('SELECT * FROM `wp_posts` WHERE `post_title` LIKE "%'.$allgy.'%" AND `ID`='.$subs[0]);
                $tmp = (array) $data_1;
                
                if(!empty($data) || !empty($data_1)){
                    
                    $sec_sub = get_field('substitute_recepies',$subs[0]);
                    
                    $finsub = (int)checkallergy($sec_sub[0],$allergy);

                    if($finsub == 0){
                        $final = $sec_sub[0];
                        break;
                    }else{                              
                        $subs_two = get_field('substitute_recepies',$sec_sub[0]);

                        $finsub_two = checkallergy($subs_two[0],$allergy);

                        if($finsub_two == 0){
                            $final = $subs_two[0];
                            break;
                        }else{

                            $subs_three = get_field('substitute_recepies',$subs_two[0]);
                            $finsub_three = checkallergy($subs_three[0],$allergy);

                            if($finsub_three == 0){

                                $final = $subs_three[0];
                                break;
                            }else{

                                $subs_four = get_field('substitute_recepies',$subs_three[0]);
                                $finsub_four = checkallergy($subs_four[0],$allergy);

                                if($finsub_four == 0){

                                    $final = $subs_four[0];
                                    break;
                                }else{
                                    $final = 'nishu';
                                }

                            }
                        }

                    }

                }else{
                   $final = $subs[0];

                }
                
                /** Array of Recipe id to unset **/
                        
//                if(count($data) != 0 || count($data_1) != 0){
//                     $kia[] = $post->ID;
//                }

                /** Array of Recipe id to unset **/
            }
    }else{   
         $final = $subs[0];
    }
    
    if($_POST['count'] == 0){
        $subid = $final;
        $cal_val = get_field('recipe_calories',$subid);
        $recipe_img = wp_get_attachment_url(get_post_thumbnail_id($subid));
        $r_link = get_field('you_tube_link',$subid);

        if($recipe_img == ''){
            $recipe_img = '/wp-content/themes/personalDiet/images/logo-icon.jpg';
        }
        
        if($r_link != ''){
            $yt_icon = '<span><i class="fa fa-youtube-play"></i></span>';
        }else{
            $yt_icon = '';
        }
        

        $response['html_name'] = '<a href="javascript:void(0);" onclick="show_popup('.$subid.');">'.get_the_title($subid).'</a>';

        $response['cal_val'] = $cal_val;

        $response['html_link'] = '<a href="'.$r_link.'" target="_blank">'.$yt_icon.'<img src="'.$recipe_img.'"></a>';

        $response['status'] = 1;
    }else{
        $subid = $rid;
        $cal_val = get_field('recipe_calories',$subid);
        $recipe_img = wp_get_attachment_url(get_post_thumbnail_id($subid));
        $r_link = get_field('you_tube_link',$subid);

        if($recipe_img == ''){
            $recipe_img = '/wp-content/themes/personalDiet/images/logo-icon.jpg';
        }
        
        if($r_link != ''){
            $yt_icon = '<span><i class="fa fa-youtube-play"></i></span>';
        }else{
            $yt_icon = '';
        }

        $response['html_name'] = '<a href="javascript:void(0);" onclick="show_popup('.$subid.');">'.get_the_title($subid).'</a>';

        $response['cal_val'] = $cal_val;

        $response['html_link'] = '<a href="'.$r_link.'" target="_blank">'.$yt_icon.'<img src="'.$recipe_img.'"></a>';

        $response['status'] = 2;
    }
    

    
    echo json_encode($response);
    
    exit();
}


function checkallergy($postid, $allergy){
    global $wpdb;
    $i=0;
    foreach($allergy as $allgy){
        
        $metaData = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$allgy.'%" AND `post_id`='.$postid);
//        $tmps = (array) $data;

        $titleData = $wpdb->get_row('SELECT * FROM `wp_posts` WHERE `post_title` LIKE "%'.$allgy.'%" AND `ID`='.$postid);
//        $tmp = (array) $data_1;
        
        if(!empty($metaData) || !empty($titleData)){
            $i++;
        }
        
        
    }
    return $i;
}

add_action( 'wp_ajax_load_popup', 'load_popup' );
add_action( 'wp_ajax_nopriv_load_popup', 'load_popup' );
function load_popup(){
    
    $rid = $_POST['recipe_id'];
    $img = wp_get_attachment_url(get_post_thumbnail_id($rid));
    $recipeName = get_the_title($rid);
    $cal = get_field('recipe_calories',$rid);
    $serve = get_field('recipe_serves',$rid);
    $recipe = get_field('recipe_ingredients',$rid);
    $recipe_process = get_field('recipe_process',$rid);
?>

  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="flex">
            <div class="left">
                <figure>
                    <img src="<?php echo $img; ?>">
                </figure>
            </div>
            <div class="right">
                <div class="single">
<!--                    <h5>Name: </h5>-->
                    <h5><?php echo $recipeName; ?> </h5>
                </div>
                <div class="single">
                    <h5>Calories: </h5>
                    <h5><?php echo $cal; ?></h5>
                </div>
                <div class="single">
                    <h5>Serve: </h5>
                    <h5><?php echo $serve; ?></h5>
                </div>
            </div>
        </div>
        <div class="recipe">
            <h6>Recipe Ingredients: </h6>
            <ul>
                <?php foreach($recipe as $rec){ ?>
                <li><?php echo $rec['ingredients'];?></li>
                <?php }?>
            </ul>
        </div>

        <div class="process">
            <h6>Recipe Process: </h6>
            <p><?php echo $recipe_process; ?></p>
        </div>
<!--
        <div class="table">
            <table>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Short Desc</th>
                    <th>Quantity</th>
                </tr>
                <tr>
                    <td><img src="light2.jpg"></td>
                    <td>Varun</td>
                    <td>Normal</td>
                    <td>Single</td>
                </tr>
            </table>
        </div>
-->
    </div>
</div>
</div>

<?php
    
    exit();
}




add_action( 'wp_ajax_calculate_bms', 'calculate_bms' );
add_action( 'wp_ajax_nopriv_calculate_bms', 'calculate_bms' );
function calculate_bms(){
     
    $age = (int)$_POST['age'];
    $height = (int)$_POST['height'];
    $weight = (int)$_POST['weight'];
    $response = array();
    
    if($_POST['gender'] == "male"){
        $response['maintain_weight'] = (int)13.397*$weight + 4.799*$height - 5.677*$age + 88.362;
        $response['loss_weight'] = (int)13.397*$weight + 4.799*$height - 6.677*$age;
    }else{
        $response['maintain_weight'] = (int)10*$weight + 6.25*$height - 5*$age - 161;
        $response['loss_weight'] = (int)10*$weight + 6.25*$height - 5*$age - 361;
    }
    
    echo json_encode($response);
    
    exit();
}


add_action( 'wp_ajax_load_more_faq', 'load_more_faq' );
add_action( 'wp_ajax_nopriv_load_more_faq', 'load_more_faq' );
function load_more_faq(){

$offset = (int)$_POST['currentCount'];
$args = array(
    'post_type' => 'personal_faq',
    'post_status' => 'publish',
    'posts_per_page' =>  8,
    'offset' => $offset,
);

$loop = new wp_query($args);
$i=$offset;
while($loop->have_posts()): $loop->the_post();
?>
<div class="card">
    <div class="card-header" role="tab" id="heading<?php echo $i; ?>">
        <a class="collapsed" data-toggle="collapse" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
            <?php the_title(); ?>
        </a>
</div>
<div id="collapse<?php echo $i; ?>" class="collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion" style="">
      <div class="card-body">
        <?php the_content(); ?>
      </div>
</div>
</div>
<?php
    $i++;
endwhile;
wp_reset_query();
    
exit();
}


add_action( 'wp_ajax_load_more_weight_loss', 'load_more_weight_loss' );
add_action( 'wp_ajax_nopriv_load_more_weight_loss', 'load_more_weight_loss' );
function load_more_weight_loss(){

$response = array();
$offset = (int)$_POST['currentCount'];
$weightLoss = get_field('weight_loss_tips',80); 
    
$limit = $offset+5;
$j=0;
for($i=$offset; $i<$limit; $i++){
    if($weightLoss[$i]['weight_loss_question'] != ''){
        
    $response['html'] .= '<div class="card">';
    $response['html'] .= '<div class="card-header" role="tab" id="heading'.$i.'">';
    $response['html'] .= '<a class="collapsed" data-toggle="collapse" href="#collapse'.$i.'" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">'.$weightLoss[$i]['weight_loss_question'].'</a>';
    $response['html'] .= '</div>';
    $response['html'] .= '<div id="collapse'.$i.'" class="collapse" role="tabpanel" aria-labelledby="heading'.$i.'" data-parent="#accordion" style="">';
    $response['html'] .= '<div class="card-body">'.$weightLoss[$i]['weight_loss_answer'].'</div>';
    $response['html'] .= '</div>';
    $response['html'] .= '</div>';
         $j++;
    }
   
 }

    $response['co'] = $j;
    
echo json_encode($response);
    
exit();
}


add_action( 'template_redirect', 'wpse_128636_redirect_post' );

function wpse_128636_redirect_post() {
  if ( is_singular( 'personal_faq' ) ) {
    wp_redirect( get_the_permalink(28), 301 );
    exit;
  }
  if ( is_singular( 'testimonial' ) ) {
    wp_redirect( home_url(), 301 );
    exit;
  }
  if ( is_singular( 'recipes' ) ) {
    wp_redirect( home_url(), 301 );
    exit;
  }
}


add_action( 'wp_ajax_save_data', 'save_data' );
add_action( 'wp_ajax_nopriv_save_data', 'save_data' );
function save_data(){
    global $wpdb;
    $response = array();
    
    $wpdb->insert("save_data",array(
        'meal_data'=>$_POST['mealData'],
    ));
    
    $response['lastid'] = $wpdb->insert_id;
    echo json_encode($response);
    exit();
}


add_action( 'wp_ajax_delete_plan', 'delete_plan' );
add_action( 'wp_ajax_nopriv_delete_plan', 'delete_plan' );
function delete_plan(){
    global $wpdb;
    $response = array();
    
    $rowID = (int)$_POST['rowid'];
    $result = $wpdb->query("DELETE FROM plan_payments WHERE ID = $rowID");
    
    if($result){
        $response['status'] = 1;
    }else{
        $response['status'] = 0;
    }
    
    echo json_encode($response);
    
    exit();
}


add_action( 'wp_ajax_add_plan', 'add_plan' );
add_action( 'wp_ajax_nopriv_add_plan', 'add_plan' );
function add_plan(){
    global $wpdb;
    $response = array();
    $price = (float)$_POST['plane_price'];
    $duration = (int)$_POST['plane_duration'];
    
    $result = $wpdb->insert("my_plans",array(
        "plane_name"=>$_POST['plane_name'],
        "plane_price"=>$price,
        "plane_duration"=>$duration,
    ));
    
    if($result){
        $response['status'] = 1;
    }else{
        $response['status'] = 0;
    }
    
    echo json_encode($response);
    exit();
}


add_action( 'wp_ajax_remove_plan', 'remove_plan' );
add_action( 'wp_ajax_nopriv_remove_plan', 'remove_plan' );
function remove_plan(){
    global $wpdb;
    $response = array();
    
    $rowID = (int)$_POST['rowid'];
    $result = $wpdb->query("DELETE FROM my_plans WHERE ID = $rowID");
    
    if($result){
        $response['status'] = 1;
    }else{
        $response['status'] = 0;
    }
    
    echo json_encode($response);
    
    exit();
}


add_action( 'wp_ajax_update_plan', 'update_plan' );
add_action( 'wp_ajax_nopriv_update_plan', 'update_plan' );
function update_plan(){
    global $wpdb;
    $response = array();
    
    $name = $_POST["planName"];
    $duration = (int)$_POST["planDuration"];
    $price = (float)$_POST["planPrice"];
    $rid = $_POST["rowid"];
    
    $rowID = (int)$_POST['rowid'];
    $result = $wpdb->query("UPDATE my_plans
SET plane_name = '".$name."', plane_price= $price, plane_duration=$duration
WHERE ID = $rid");
    
    if($result){
        $response['status'] = 1;
    }else{
        $response['status'] = 0;
    }
    
    echo json_encode($response);
    
    exit();
}


function modify_url($value1,$value2){
    $newURL = $value2;
    
    return $newURL;
}

function send_invoice_email($uid,$dateTime,$opt_plan,$payment_amount){
    
    $siteURL = site_url();
    $name = get_user_meta($uid,'first_name',true).' '.get_user_meta($uid,'last_name',true);
    $userData = get_userdata($uid);
    $email = $userData->data->user_email;
 
    $message = '<head>
   <title>Payment success</title>
   <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>

    <body>
       <table cellspacing="0" border="0" align="center" cellpadding="0" width="600" style="border:1px solid #efefef; margin-top:10px; margin-bottom: 30px;">
           <tr>
               <td>
                   <table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">
                       <tr align="center" >
                           <td style="font-family:arial; padding:20px;"><strong style="display:inline-block;">
                                <a href="'.$siteURL.'" target="_blank"><img src="https://i.postimg.cc/nzQknntj/logo.png" border="0" alt="logo"/></a>
                              </strong></td>
                       </tr>
                   </table>
                   <table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="padding:40px;">
                       <tr>
                        <td style="padding: 0px 0 15px;">
                        <h4 style="font-weight:normal; margin-top: 0px; margin-bottom: 15px; font-size: 16px;">Hi, '.$name.'</h4>
                           </td>
                       </tr>
                       <tr>
                          <td style="padding: 0px 0 15px;">
                               <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Thanks for making the payment for plan ('.$opt_plan.').
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Paid Amount - '.$payment_amount.' on '.$dateTime.'
                                </p>
                                <p style="font-family: "Roboto", sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px; line-height: 1.7; font-size: 15px;">
                                Please login to check details .
                                </p>
                           </td>
                       </tr>

                   </table>
               </td>
           </tr>
       </table>
    </body>';
    
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // More headers
    $headers .= 'From: <Info@personalizediet.com/>' . "\r\n";

    wp_mail($email,'Payment Confirmed',$message,$headers);
    
}

add_action( 'wp_ajax_get_discount', 'get_discount' );
add_action( 'wp_ajax_nopriv_get_discount', 'get_discount' );
function get_discount(){
    
    $response = array();
    $coupon_code = get_field('promo_codes','option');
    
    if($coupon_code[0]['code_name'] == $_POST['discount_code']){
        $dis_amount = $coupon_code[0]['code_amount'];
        $response['new_amount'] = (int)$_POST['actual_price'] - (int)$dis_amount;
        $response['discount_amount'] = $dis_amount;
        $response['status'] = 1;
    }else{
        $response['status'] = 0;
    }
    
    echo json_encode($response);
    exit();
}


add_action( 'wp_ajax_save_my_progress', 'save_my_progress' );
add_action( 'wp_ajax_nopriv_save_my_progress', 'save_my_progress' );
function save_my_progress(){
    
    global $wpdb;
    
    $date = date('d/m/Y');
    $result = $wpdb->insert("my_progress",array(
        "user_id"=>$_POST['user_id'],
        "date"=>$date,
        "weight"=>$_POST['current_weight']
    ));
    
    if($result){
        $response['status'] = 1;
    }else{
        $response['status'] = 2;
    }
    
    echo json_encode($response);
    
    exit();
}