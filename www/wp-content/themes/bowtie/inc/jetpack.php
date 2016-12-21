<?php
/**
 * Jetpack Compatibility File.
 *
 * @link http://jetpack.me/
 *
 * @package bowtie
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function bowtie_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'bowtie_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function bowtie_jetpack_setup
add_action( 'after_setup_theme', 'bowtie_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function bowtie_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'page-templates/content', get_post_format() );
	}
} // end function bowtie_infinite_scroll_render
