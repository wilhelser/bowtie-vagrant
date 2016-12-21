<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bowtie
 */

$background = get_field('background');

get_header(); ?>

<div class="hero <?php print get_field('hero_disabled') ? 'disabled' : ''; ?>">
  <div class="row">
		<?php if(get_field('headline')): ?>
		<h1><?php the_field('headline'); ?>
		<?php else: ?>
		<h1><?php the_title(); ?></h1>
		<?php endif; ?>

    <?php if($background['type'] == 'video'): ?>
    <video class="bg" loop="" autoplay="" preload="auto">
      <source src="<?php print wp_get_attachment_url($background['ID']); ?>" type="<?php print $background['mime_type']; ?>">
    </video>
    <?php endif; ?>
  </div>
</div>

<main id="main" class="site-main" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'components/content', 'page' ); ?>

	<?php endwhile; // End of the loop. ?>

	<?php get_template_part( 'components/content', 'blocks' ); ?>

	<?php //get_sidebar(); ?>

</main><!-- #main -->

<?php if($background['type'] == 'image') { ?>
<style>
	.hero {
		background: linear-gradient(90deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.4) 100%), url('<?php print wp_get_attachment_url($background['ID']); ?>') no-repeat center!important;
		background-size: cover!important;
	}
</style>
<?php } ?>

<?php get_footer(); ?>
