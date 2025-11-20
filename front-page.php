<?php
// File: wp-content/themes/ProEvent/front-page.php

// now block-driven; just need a nice wrapper
get_header();
?>

<div class="proevent-page">

	<?php
	if ( have_posts() ) :

		while ( have_posts() ) :
			the_post();
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
