<?php
// File: wp-content/themes/ProEvent/index.php

// fallback template – not super important for this MVP but giving it same shell

get_header();
?>

<div class="proevent-page">

	<?php if ( have_posts() ) : ?>

		<header class="mb-6">
			<h1 class="proevent-page-title">
				<?php esc_html_e( 'Latest posts', 'my-project' ); ?>
			</h1>
		</header>

		<div class="space-y-6">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'bg-white rounded-lg shadow-sm border border-slate-100 p-5' ); ?>>

					<h2 class="text-xl font-semibold mb-2">
						<a href="<?php the_permalink(); ?>" class="hover:underline">
							<?php the_title(); ?>
						</a>
					</h2>

					<div class="text-xs text-slate-500 mb-2">
						<?php echo esc_html( get_the_date() ); ?>
					</div>

					<div class="prose max-w-none text-sm">
						<?php the_excerpt(); ?>
					</div>

				</article>
				<?php
			endwhile;
			?>
		</div>

		<div class="mt-8">
			<?php the_posts_pagination(); ?>
		</div>

	<?php else : ?>

		<p class="text-sm text-slate-500">
			<?php esc_html_e( 'No content found.', 'my-project' ); ?>
		</p>

	<?php endif; ?>

</div>

<?php
get_footer();
