<?php
// front page will eventually be our “upcoming events” + hero block.
// for now just re-use the loop so we can see something render.

get_header();
?>

<div class="max-w-6xl mx-auto px-4 py-10">

	<section class="mb-10">
		<h1 class="text-3xl font-bold mb-2">
			<?php bloginfo( 'name' ); ?>
		</h1>
		<p class="text-slate-600 text-sm">
			<?php bloginfo( 'description' ); ?>
		</p>
	</section>

	<section>
		<?php if ( have_posts() ) : ?>

			<div class="space-y-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'border-b border-slate-200 pb-6' ); ?>>
						<h2 class="text-xl font-semibold mb-2">
							<a href="<?php the_permalink(); ?>" class="hover:underline">
								<?php the_title(); ?>
							</a>
						</h2>

						<div class="text-sm text-slate-500 mb-2">
							<?php echo esc_html( get_the_date() ); ?>
						</div>

						<div class="prose max-w-none">
							<?php the_excerpt(); ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="mt-8">
				<?php the_posts_pagination(); ?>
			</div>

		<?php else : ?>

			<p><?php esc_html_e( 'No posts yet.', 'my-project' ); ?></p>

		<?php endif; ?>
	</section>
</div>

<?php
get_footer();
