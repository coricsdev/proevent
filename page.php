<?php
// basic static page template (About, Contact, etc.). we’ll customize per-page later if needed.
get_header();
?>

<div class="max-w-3xl mx-auto px-4 py-10">

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<h1 class="text-3xl font-bold mb-6"><?php the_title(); ?></h1>

				<div class="prose max-w-none">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
	endif;
	?>

</div>

<?php
get_footer();
