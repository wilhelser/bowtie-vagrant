<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package buckhead
 */

?>

<?php while(the_flexible_field('blocks')): ?>
	<?php $get_row_layout = get_row_layout(); $width = get_sub_field('width'); ?>
	<section class="block <?php print $get_row_layout; ?> <?php print 'height-'.get_sub_field('height'); ?> <?php print get_sub_field('padding') ? 'padded' : ''; ?>" id="block-<?php print get_row_index(); ?>">
		<div class="row collapse <?php print $width[0] == 'full' ? 'expanded' : ''; ?> <?php print get_sub_field('vertical_align') == 'center' ? 'vertical-center': ''; ?>">
			<?php if($get_row_layout == 'full'): ?>
			<div class="column">
				<?php the_sub_field('content'); ?>
			</div>
			<?php elseif($get_row_layout == 'two_column'): ?>
			<div class="small-12 medium-6 columns left <?php print 'height-'.get_sub_field('height'); ?><?php print get_sub_field('background_image') ? ' with-photo' : ''; ?>">
				<?php the_sub_field('content'); ?>
			</div>
			<div class="small-12 medium-6 columns right <?php print 'height-'.get_sub_field('height'); ?><?php print get_sub_field('background_image_r') ? ' with-photo' : ''; ?>">
				<?php the_sub_field('content_r'); ?>
			</div>
			<?php endif; ?>
		</div>
	</section>
<?php endwhile; ?>

<style>
<?php while(the_flexible_field('blocks')): ?>
	<?php $get_row_layout = get_row_layout(); ?>
	<?php if($get_row_layout == 'full'): ?>
	#block-<?php print get_row_index(); ?> {
		<?php if(get_sub_field('background_color') || get_sub_field('background_image')): ?>
		background: <?php print the_sub_field('background_color'); ?> url('<?php the_sub_field('background_image'); ?>') no-repeat center;
		background-size: cover;
		<?php endif; ?>
	}
	<?php endif; ?>

	<?php if($get_row_layout == 'two_column'): ?>
	#block-<?php print get_row_index(); ?> .left {
		<?php if(get_sub_field('background_color') || get_sub_field('background_image')): ?>
		background: <?php print the_sub_field('background_color'); ?> url('<?php the_sub_field('background_image'); ?>') no-repeat center;
		background-size: cover;
		<?php endif; ?>
	}
	#block-<?php print get_row_index(); ?> .right {
		<?php if(get_sub_field('background_color_r') || get_sub_field('background_image_r')): ?>
		background: <?php print the_sub_field('background_color_r'); ?> url('<?php the_sub_field('background_image_r'); ?>') no-repeat center;
		background-size: cover;
		<?php endif; ?>
	}
	<?php endif; ?>
<?php endwhile; ?>
</style>
