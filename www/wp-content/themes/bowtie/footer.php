<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bowtie
 */
?>

</div><!-- #content -->

<footer class="main" role="contentinfo">
	<div class="row">
		<div class="medium-6 columns">
			<?php print do_shortcode('[contact id="address"]'); ?>
			<p class="copyright"><?php print get_field('copyright_name', 'options'); ?> &copy; <?php print date('Y'); ?></p>
		</div>

		<div class="medium-6 columns">
			<?php dynamic_sidebar( 'footer' ); ?>
		</div>
	</div><!-- .column.row -->
</footer><!-- #colophon -->

</div>

<?php wp_footer(); ?>
</body>
</html>
