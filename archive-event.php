<?php
// File: wp-content/themes/ProEvent/archive-event.php

get_header();

// this archive is basically the "all events" view,
// but we’ll bias it toward upcoming ones using meta query.
$paged = max( 1, get_query_var( 'paged' ) );

$today = current_time( 'Y-m-d' );

// overwrite main query only on event archive
// (using pre_get_posts would be cleaner, but doing it inline for clarity here)
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
<div class="max-w-6xl mx-auto px-4 py-10">

	<header class="mb-8">
		<h1 class="text-3xl font-bold mb-2">
			<?php
			$post_type_obj = get_post_type_object( 'event' );
			echo $post_type_obj ? esc_html( $post_type_obj->labels->name ) : esc_html__( 'Events', 'my-project' );
			?>
		</h1>
		<p class="text-slate-600 text-sm">
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
				<article <?php post_class( 'bg-white rounded-lg shadow-sm overflow-hidden flex flex-col' ); ?>>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="h-40 overflow-hidden">
							<?php
							the_post_thumbnail(
								'medium_large',
								array(
									'loading' => 'lazy',
									'class'   => 'w-full h-full object-cover',
								)
							);
							?>
						</div>
					<?php endif; ?>

					<div class="p-4 flex flex-col flex-1">

						<?php if ( $formatted_date ) : ?>
							<div class="text-xs font-semibold text-blue-700 mb-1">
								<?php echo esc_html( $formatted_date ); ?>
								<?php if ( $event_time ) : ?>
									<span class="text-slate-500">&middot; <?php echo esc_html( $event_time ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<h2 class="text-lg font-semibold mb-2">
							<a href="<?php the_permalink(); ?>" class="hover:underline">
								<?php the_title(); ?>
							</a>
						</h2>

						<?php if ( $location ) : ?>
							<div class="text-xs text-slate-600 mb-3">
								<?php echo esc_html( $location ); ?>
							</div>
						<?php endif; ?>

						<div class="text-sm text-slate-700 mb-4 flex-1">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 25 ) ); ?>
						</div>

						<div class="mt-auto pt-2">
							<a href="<?php the_permalink(); ?>"
							   class="inline-flex items-center text-sm font-semibold text-blue-700 hover:text-blue-900">
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
			// simple pagination using core function
			echo paginate_links(
				array(
					'total'   => $query->max_num_pages,
					'current' => $paged,
				)
			);
			?>
		</div>

	<?php else : ?>

		<p><?php esc_html_e( 'No upcoming events found.', 'my-project' ); ?></p>

	<?php endif; ?>

</div>

<?php
wp_reset_postdata();

get_footer();
