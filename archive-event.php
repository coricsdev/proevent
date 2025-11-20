<?php
// File: wp-content/themes/ProEvent/archive-event.php

get_header();

$paged = max( 1, get_query_var( 'paged' ) );
$today = current_time( 'Y-m-d' );

$args = array(
	'post_type'      => 'event',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'meta_key'       => '_proevent_event_date',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_query'     => array(
		array(
			'key'     => '_proevent_event_date',
			'value'   => $today,
			'compare' => '>=',
			'type'    => 'DATE',
		),
	),
);

$query = new WP_Query( $args );
?>
<div class="proevent-page">

	<header class="mb-8">
		<h1 class="proevent-page-title">
			<?php
			$pto = get_post_type_object( 'event' );
			echo $pto ? esc_html( $pto->labels->name ) : esc_html__( 'Events', 'my-project' );
			?>
		</h1>
		<p class="proevent-page-subtitle">
			<?php esc_html_e( 'Browse upcoming events.', 'my-project' ); ?>
		</p>
	</header>

	<?php if ( $query->have_posts() ) : ?>

		<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();

				$event_date = get_post_meta( get_the_ID(), '_proevent_event_date', true );
				$event_time = get_post_meta( get_the_ID(), '_proevent_event_time', true );
				$location   = get_post_meta( get_the_ID(), '_proevent_event_location', true );

				$formatted_date = '';
				if ( $event_date ) {
					$ts            = strtotime( $event_date );
					$formatted_date = $ts ? date_i18n( get_option( 'date_format' ), $ts ) : $event_date;
				}
				?>
				<article <?php post_class( 'proevent-card' ); ?>>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="proevent-card-media">
							<?php
							the_post_thumbnail(
								'medium_large',
								array(
									'loading' => 'lazy',
								)
							);
							?>
						</div>
					<?php endif; ?>

					<div class="proevent-card-body">

						<?php if ( $formatted_date || $event_time ) : ?>
							<div class="proevent-meta-row">
								<?php if ( $formatted_date ) : ?>
									<span><?php echo esc_html( $formatted_date ); ?></span>
								<?php endif; ?>
								<?php if ( $event_time ) : ?>
									<span>&middot; <?php echo esc_html( $event_time ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<h2 class="proevent-card-title">
							<a href="<?php the_permalink(); ?>" class="hover:underline">
								<?php the_title(); ?>
							</a>
						</h2>

						<?php if ( $location ) : ?>
							<div class="proevent-location">
								<?php echo esc_html( $location ); ?>
							</div>
						<?php endif; ?>

						<div class="proevent-card-excerpt">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 25 ) ); ?>
						</div>

						<div class="mt-auto pt-2">
							<a href="<?php the_permalink(); ?>" class="proevent-link">
								<?php esc_html_e( 'View details', 'my-project' ); ?>
								<span class="ml-1">&rarr;</span>
							</a>
						</div>

					</div>

				</article>
				<?php
			endwhile;
			?>
		</div>

		<div class="mt-10">
			<?php
			echo paginate_links(
				array(
					'total'   => $query->max_num_pages,
					'current' => $paged,
				)
			);
			?>
		</div>

	<?php else : ?>

		<p class="text-sm text-slate-500">
			<?php esc_html_e( 'No upcoming events found.', 'my-project' ); ?>
		</p>

	<?php endif; ?>

</div>
<?php
wp_reset_postdata();

get_footer();
