<?php
// File: wp-content/themes/ProEvent/page.php

get_header();
?>

<div class="proevent-page max-w-3xl">

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<h1 class="proevent-page-title mb-4"><?php the_title(); ?></h1>

				<div class="proevent-page-subtitle mb-6">
					<?php // could add breadcrumbs or intro later ?>
				</div>

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
