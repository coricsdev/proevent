<?php
// fallback template. homepage will use front-page.php once that exists.
get_header();
?>

<div class="max-w-6xl mx-auto px-4 py-10">
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

		<p><?php esc_html_e( 'No content found.', 'my-project' ); ?></p>

	<?php endif; ?>
</div>

<?php
get_footer();
