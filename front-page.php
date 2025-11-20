<?php
// File: wp-content/themes/ProEvent/front-page.php

// front page is now fully block-driven (Hero + Event Grid etc.)
get_header();
?>

<div class="max-w-6xl mx-auto px-4 py-10">
	<?php
	if ( have_posts() ) :

		while ( have_posts() ) :
			the_post();

			// just render blocks/content, no custom query here
			the_content();

		endwhile;

	else :
		?>
		<p class="text-sm text-slate-500">
			<?php esc_html_e( 'No content on the front page yet. Add some blocks in the editor.', 'my-project' ); ?>
		</p>
		<?php
	endif;
	?>
</div>

<?php
get_footer();
