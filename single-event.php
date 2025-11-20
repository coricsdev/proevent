<?php
// File: wp-content/themes/ProEvent/single-event.php

get_header();

if ( ! have_posts() ) {
	?>
	<div class="proevent-page">
		<p class="text-sm text-slate-500"><?php esc_html_e( 'Event not found.', 'my-project' ); ?></p>
	</div>
	<?php
	get_footer();
	exit;
}

while ( have_posts() ) :
	the_post();

	$event_date = get_post_meta( get_the_ID(), '_proevent_event_date', true );
	$event_time = get_post_meta( get_the_ID(), '_proevent_event_time', true );
	$location   = get_post_meta( get_the_ID(), '_proevent_event_location', true );
	$reg_link   = get_post_meta( get_the_ID(), '_proevent_event_registration_link', true );

	$formatted_date = '';
	if ( $event_date ) {
		$ts            = strtotime( $event_date );
		$formatted_date = $ts ? date_i18n( get_option( 'date_format' ), $ts ) : $event_date;
	}
	?>
	<div class="proevent-page">

		<a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>"
		   class="inline-flex items-center text-xs md:text-sm text-slate-500 hover:text-slate-800 mb-4">
			<span class="mr-1">&larr;</span>
			<?php esc_html_e( 'Back to events', 'my-project' ); ?>
		</a>

		<article <?php post_class( 'bg-white rounded-xl shadow-sm border border-slate-100 p-5 md:p-8' ); ?>>

			<header class="mb-6">
				<h1 class="text-2xl md:text-3xl font-bold mb-3">
					<?php the_title(); ?>
				</h1>

				<div class="flex flex-wrap gap-3 text-xs md:text-sm text-slate-600">
					<?php if ( $formatted_date ) : ?>
						<div class="flex items-center gap-1">
							<span class="font-semibold"><?php esc_html_e( 'Date:', 'my-project' ); ?></span>
							<span><?php echo esc_html( $formatted_date ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $event_time ) : ?>
						<div class="flex items-center gap-1">
							<span class="font-semibold"><?php esc_html_e( 'Time:', 'my-project' ); ?></span>
							<span><?php echo esc_html( $event_time ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $location ) : ?>
						<div class="flex items-center gap-1">
							<span class="font-semibold"><?php esc_html_e( 'Location:', 'my-project' ); ?></span>
							<span><?php echo esc_html( $location ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<?php
				$terms = get_the_terms( get_the_ID(), 'event-category' );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
					?>
					<div class="mt-4 flex flex-wrap gap-2">
						<?php foreach ( $terms as $term ) : ?>
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="proevent-tag">
								<?php echo esc_html( $term->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="mb-6 rounded-lg overflow-hidden">
					<?php
					the_post_thumbnail(
						'large',
						array(
							'loading' => 'lazy',
							'class'   => 'w-full h-auto object-cover',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<div class="prose max-w-none mb-8">
				<?php the_content(); ?>
			</div>

			<?php if ( $reg_link ) : ?>
				<div class="mt-4">
					<a href="<?php echo esc_url( $reg_link ); ?>"
					   class="proevent-btn-primary"
					   target="_blank" rel="noopener">
						<?php esc_html_e( 'Register for this event', 'my-project' ); ?>
					</a>
				</div>
			<?php endif; ?>

		</article>

		<?php
		// related events
		$term_ids = wp_get_post_terms( get_the_ID(), 'event-category', array( 'fields' => 'ids' ) );

		if ( ! empty( $term_ids ) && ! is_wp_error( $term_ids ) ) {

			$related_args = array(
				'post_type'           => 'event',
				'posts_per_page'      => 3,
				'post__not_in'        => array( get_the_ID() ),
				'ignore_sticky_posts' => true,
				'tax_query'           => array(
					array(
						'taxonomy' => 'event-category',
						'field'    => 'term_id',
						'terms'    => $term_ids,
					),
				),
				'meta_key'            => '_proevent_event_date',
				'orderby'             => 'meta_value',
				'order'               => 'ASC',
			);

			$related_query = new WP_Query( $related_args );

			if ( $related_query->have_posts() ) :
				?>
				<section class="mt-10">
					<h2 class="text-lg md:text-xl font-semibold mb-4">
						<?php esc_html_e( 'Related events', 'my-project' ); ?>
					</h2>

					<div class="grid gap-4 md:grid-cols-3">
						<?php
						while ( $related_query->have_posts() ) :
							$related_query->the_post();

							$r_date = get_post_meta( get_the_ID(), '_proevent_event_date', true );
							$r_fmt  = '';
							if ( $r_date ) {
								$ts   = strtotime( $r_date );
								$r_fmt = $ts ? date_i18n( get_option( 'date_format' ), $ts ) : $r_date;
							}
							?>
							<article <?php post_class( 'proevent-card' ); ?>>
								<div class="proevent-card-body">
									<h3 class="text-sm font-semibold mb-1">
										<a href="<?php the_permalink(); ?>" class="hover:underline">
											<?php the_title(); ?>
										</a>
									</h3>

									<?php if ( $r_fmt ) : ?>
										<div class="text-[11px] text-slate-500 mb-2">
											<?php echo esc_html( $r_fmt ); ?>
										</div>
									<?php endif; ?>

									<div class="text-xs text-slate-600">
										<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
									</div>
								</div>
							</article>
							<?php
						endwhile;
						?>
					</div>
				</section>
				<?php
			endif;

			wp_reset_postdata();
		}
		?>

	</div>
	<?php
endwhile;

get_footer();
