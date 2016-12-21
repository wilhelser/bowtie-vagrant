<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bowtie
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="off-canvas-wrapper">
  <div class="off-canvas position-right" id="offCanvasRight" data-off-canvas>
		<button class="close-button" aria-label="Close menu" type="button" data-close>
			<span aria-hidden="true">&times;</span>
		</button>
		<?php
		 $args = array (
			 'theme_location' 	=> 'primary',
			 'container' 				=> 'nav',
			 'container_class'	=> 'offcanvas-navigation',
			 'menu_class' 			=> 'mobile-menu',
		 );
			wp_nav_menu( $args );
		?>
  </div><!-- #offCanvasLeft -->
  <div class="off-canvas-content" data-off-canvas-content>

		<header class="main" role="banner">
      <div class="row">
				<a href="<?php esc_attr_e( home_url( '/' ) ); ?>" rel="home">
						<h1 class="site-name"><?php bloginfo( 'name' ); ?></h1>
            <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
            <?php print do_shortcode('[logo]'); ?>
				</a>

				<nav class="main" role="navigation">
          <button class="menu-icon" type="button" data-toggle="offCanvasRight"></button>
          <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
				</nav><!-- #site-navigation -->
      </div>
		</header><!-- #masthead -->

		<div id="content" class="site-content">
