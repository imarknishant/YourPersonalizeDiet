<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
$twentytwentyone_unique_id = wp_unique_id( 'search-form-' );

$twentytwentyone_aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
?>

<div class="blog-sidebar">
	<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
		<div class="form-group">
			<input type="text" class="search-field form-control"
				placeholder="Search"
				value="<?php echo get_search_query() ?>" name="s"
				title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
				<button type="submit" class="search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
		</div>	
	</form>
</div>
