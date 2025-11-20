<?php
// File: wp-content/themes/ProEvent/functions.php

// basic theme setup, will grow as we add features

if ( ! function_exists( 'proevent_setup' ) ) :

	function proevent_setup() {

		add_theme_support( 'title-tag' );

		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);

		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'my-project' ),
				'footer'  => __( 'Footer Menu', 'my-project' ),
			)
		);
	}

endif;
add_action( 'after_setup_theme', 'proevent_setup' );



function proevent_assets() {

	$theme_version = wp_get_theme()->get( 'Version' );

	$css_path = get_stylesheet_directory() . '/assets/css/main.css';
	$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : $theme_version;

	wp_enqueue_style(
		'proevent-main',
		get_stylesheet_directory_uri() . '/assets/css/main.css',
		array(),
		$css_ver
	);

	$js_path = get_stylesheet_directory() . '/assets/js/main.js';
	$js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : $theme_version;

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script(
			'proevent-main',
			get_stylesheet_directory_uri() . '/assets/js/main.js',
			array(),
			$js_ver,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'proevent_assets' );



/**
 * Event CPT + taxonomy
 */
function proevent_register_event_cpt() {

	$labels = array(
		'name'               => __( 'Events', 'my-project' ),
		'singular_name'      => __( 'Event', 'my-project' ),
		'add_new'            => __( 'Add New', 'my-project' ),
		'add_new_item'       => __( 'Add New Event', 'my-project' ),
		'edit_item'          => __( 'Edit Event', 'my-project' ),
		'new_item'           => __( 'New Event', 'my-project' ),
		'view_item'          => __( 'View Event', 'my-project' ),
		'search_items'       => __( 'Search Events', 'my-project' ),
		'not_found'          => __( 'No events found.', 'my-project' ),
		'not_found_in_trash' => __( 'No events found in Trash.', 'my-project' ),
		'all_items'          => __( 'All Events', 'my-project' ),
		'menu_name'          => __( 'Events', 'my-project' ),
	);

	$args = array(
		'label'           => __( 'Events', 'my-project' ),
		'labels'          => $labels,
		'public'          => true,
		'has_archive'     => true,
		'show_in_rest'    => true,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_position'   => 20,
		'menu_icon'       => 'dashicons-calendar-alt',
		'rewrite'         => array(
			'slug'       => 'events',
			'with_front' => false,
		),
		'show_in_nav_menus' => true,
		'capability_type'   => 'post',
	);

	register_post_type( 'event', $args );

	$tax_labels = array(
		'name'          => __( 'Event Categories', 'my-project' ),
		'singular_name' => __( 'Event Category', 'my-project' ),
		'search_items'  => __( 'Search Event Categories', 'my-project' ),
		'all_items'     => __( 'All Event Categories', 'my-project' ),
		'edit_item'     => __( 'Edit Event Category', 'my-project' ),
		'update_item'   => __( 'Update Event Category', 'my-project' ),
		'add_new_item'  => __( 'Add New Event Category', 'my-project' ),
		'new_item_name' => __( 'New Event Category Name', 'my-project' ),
		'menu_name'     => __( 'Event Categories', 'my-project' ),
	);

	$tax_args = array(
		'labels'            => $tax_labels,
		'public'            => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'rewrite'           => array(
			'slug'         => 'event-category',
			'with_front'   => false,
			'hierarchical' => true,
		),
	);

	register_taxonomy( 'event-category', array( 'event' ), $tax_args );
}
add_action( 'init', 'proevent_register_event_cpt' );



/**
 * Event meta box
 */
function proevent_add_event_meta_box() {
	add_meta_box(
		'proevent_event_details',
		__( 'Event Details', 'my-project' ),
		'proevent_render_event_meta_box',
		'event',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'proevent_add_event_meta_box' );



function proevent_render_event_meta_box( $post ) {

	wp_nonce_field( 'proevent_save_event_meta', 'proevent_event_meta_nonce' );

	$event_date = get_post_meta( $post->ID, '_proevent_event_date', true );
	$event_time = get_post_meta( $post->ID, '_proevent_event_time', true );
	$location   = get_post_meta( $post->ID, '_proevent_event_location', true );
	$reg_link   = get_post_meta( $post->ID, '_proevent_event_registration_link', true );

	?>
	<div class="proevent-event-meta-fields">

		<p>
			<label for="proevent_event_date" style="display:block;font-weight:600;margin-bottom:4px;">
				<?php esc_html_e( 'Date', 'my-project' ); ?>
			</label>
			<input type="date"
				   id="proevent_event_date"
				   name="proevent_event_date"
				   value="<?php echo esc_attr( $event_date ); ?>"
				   style="min-width:220px;">
		</p>

		<p>
			<label for="proevent_event_time" style="display:block;font-weight:600;margin-bottom:4px;">
				<?php esc_html_e( 'Time', 'my-project' ); ?>
			</label>
			<input type="time"
				   id="proevent_event_time"
				   name="proevent_event_time"
				   value="<?php echo esc_attr( $event_time ); ?>"
				   style="min-width:220px;">
		</p>

		<p>
			<label for="proevent_event_location" style="display:block;font-weight:600;margin-bottom:4px;">
				<?php esc_html_e( 'Location', 'my-project' ); ?>
			</label>
			<input type="text"
				   id="proevent_event_location"
				   name="proevent_event_location"
				   value="<?php echo esc_attr( $location ); ?>"
				   class="widefat">
		</p>

		<p>
			<label for="proevent_event_registration_link" style="display:block;font-weight:600;margin-bottom:4px;">
				<?php esc_html_e( 'Registration link', 'my-project' ); ?>
			</label>
			<input type="url"
				   id="proevent_event_registration_link"
				   name="proevent_event_registration_link"
				   value="<?php echo esc_attr( $reg_link ); ?>"
				   class="widefat"
				   placeholder="https://">
		</p>

		<p style="color:#666;font-size:12px;margin-top:12px;">
			<?php esc_html_e( 'These fields will be used for the event listing, REST API, and blocks later.', 'my-project' ); ?>
		</p>

	</div>
	<?php
}



function proevent_save_event_meta( $post_id ) {

	if ( ! isset( $_POST['proevent_event_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['proevent_event_meta_nonce'], 'proevent_save_event_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'event' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$date = isset( $_POST['proevent_event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['proevent_event_date'] ) ) : '';
	$time = isset( $_POST['proevent_event_time'] ) ? sanitize_text_field( wp_unslash( $_POST['proevent_event_time'] ) ) : '';
	$loc  = isset( $_POST['proevent_event_location'] ) ? sanitize_text_field( wp_unslash( $_POST['proevent_event_location'] ) ) : '';
	$link = isset( $_POST['proevent_event_registration_link'] ) ? esc_url_raw( wp_unslash( $_POST['proevent_event_registration_link'] ) ) : '';

	update_post_meta( $post_id, '_proevent_event_date', $date );
	update_post_meta( $post_id, '_proevent_event_time', $time );
	update_post_meta( $post_id, '_proevent_event_location', $loc );
	update_post_meta( $post_id, '_proevent_event_registration_link', $link );
}
add_action( 'save_post_event', 'proevent_save_event_meta' );



/**
 * Company Settings page (logo, brand color, socials)
 */
function proevent_register_company_settings() {

	register_setting(
		'proevent_company_settings_group',
		'proevent_company_settings',
		'proevent_sanitize_company_settings'
	);

	add_settings_section(
		'proevent_company_general',
		__( 'Company Branding', 'my-project' ),
		'proevent_company_section_general_cb',
		'proevent-settings'
	);

	add_settings_field(
		'proevent_company_logo',
		__( 'Logo', 'my-project' ),
		'proevent_company_field_logo_cb',
		'proevent-settings',
		'proevent_company_general'
	);

	add_settings_field(
		'proevent_company_brand_color',
		__( 'Brand color', 'my-project' ),
		'proevent_company_field_brand_color_cb',
		'proevent-settings',
		'proevent_company_general'
	);

	add_settings_section(
		'proevent_company_social',
		__( 'Social Links', 'my-project' ),
		'proevent_company_section_social_cb',
		'proevent-settings'
	);

	add_settings_field(
		'proevent_company_social_facebook',
		__( 'Facebook URL', 'my-project' ),
		'proevent_company_field_social_facebook_cb',
		'proevent-settings',
		'proevent_company_social'
	);

	add_settings_field(
		'proevent_company_social_instagram',
		__( 'Instagram URL', 'my-project' ),
		'proevent_company_field_social_instagram_cb',
		'proevent-settings',
		'proevent_company_social'
	);

	add_settings_field(
		'proevent_company_social_twitter',
		__( 'X / Twitter URL', 'my-project' ),
		'proevent_company_field_social_twitter_cb',
		'proevent-settings',
		'proevent_company_social'
	);

	add_settings_field(
		'proevent_company_social_linkedin',
		__( 'LinkedIn URL', 'my-project' ),
		'proevent_company_field_social_linkedin_cb',
		'proevent-settings',
		'proevent_company_social'
	);
}
add_action( 'admin_init', 'proevent_register_company_settings' );



function proevent_sanitize_company_settings( $input ) {

	$output = array();

	$output['logo']        = isset( $input['logo'] ) ? esc_url_raw( $input['logo'] ) : '';
	$output['brand_color'] = isset( $input['brand_color'] ) ? sanitize_hex_color( $input['brand_color'] ) : '';

	$output['facebook']  = isset( $input['facebook'] ) ? esc_url_raw( $input['facebook'] ) : '';
	$output['instagram'] = isset( $input['instagram'] ) ? esc_url_raw( $input['instagram'] ) : '';
	$output['twitter']   = isset( $input['twitter'] ) ? esc_url_raw( $input['twitter'] ) : '';
	$output['linkedin']  = isset( $input['linkedin'] ) ? esc_url_raw( $input['linkedin'] ) : '';

	return $output;
}



function proevent_company_section_general_cb() {
	echo '<p style="max-width:500px;">' . esc_html__( 'Basic branding options used across the ProEvent theme.', 'my-project' ) . '</p>';
}

function proevent_company_section_social_cb() {
	echo '<p style="max-width:500px;">' . esc_html__( 'Public social profile links. Leave blank for any you do not use.', 'my-project' ) . '</p>';
}



function proevent_company_get_settings() {
	$defaults = array(
		'logo'        => '',
		'brand_color' => '',
		'facebook'    => '',
		'instagram'   => '',
		'twitter'     => '',
		'linkedin'    => '',
	);

	$opts = get_option( 'proevent_company_settings', array() );

	if ( ! is_array( $opts ) ) {
		$opts = array();
	}

	return wp_parse_args( $opts, $defaults );
}



function proevent_company_field_logo_cb() {
	$settings = proevent_company_get_settings();
	$value    = $settings['logo'];

	$preview_style = 'display:block;max-width:180px;margin-top:8px;border:1px solid #ddd;padding:6px;background:#fff;';
	?>
	<div>
		<input type="text"
			   id="proevent_company_logo"
			   name="proevent_company_settings[logo]"
			   value="<?php echo esc_attr( $value ); ?>"
			   class="regular-text"
			   placeholder="https://example.com/logo.webp" />

		<button type="button"
				class="button proevent-logo-upload-btn"
				data-target="#proevent_company_logo">
			<?php esc_html_e( 'Select from media', 'my-project' ); ?>
		</button>

		<?php if ( ! empty( $value ) ) : ?>
			<img src="<?php echo esc_url( $value ); ?>"
				 alt="<?php esc_attr_e( 'Logo preview', 'my-project' ); ?>"
				 style="<?php echo esc_attr( $preview_style ); ?>" />
		<?php endif; ?>

		<p class="description">
			<?php esc_html_e( 'Prefer WebP or SVG where possible. This will be used in the header and maybe event pages.', 'my-project' ); ?>
		</p>
	</div>
	<?php
}



function proevent_company_field_brand_color_cb() {
	$settings = proevent_company_get_settings();
	$value    = $settings['brand_color'];

	if ( empty( $value ) ) {
		$value = '#2563eb';
	}
	?>
	<div>
		<input type="text"
			   id="proevent_company_brand_color"
			   name="proevent_company_settings[brand_color]"
			   value="<?php echo esc_attr( $value ); ?>"
			   class="regular-text"
			   placeholder="#2563eb" />
		<input type="color"
			   value="<?php echo esc_attr( $value ); ?>"
			   data-target="#proevent_company_brand_color"
			   class="proevent-color-picker"
			   style="margin-left:6px;vertical-align:middle;">

		<p class="description">
			<?php esc_html_e( 'Primary brand color. We can wire this into Tailwind config or inline styles for now.', 'my-project' ); ?>
		</p>
	</div>
	<?php
}



function proevent_company_field_social_facebook_cb() {
	$settings = proevent_company_get_settings();
	$value    = $settings['facebook'];
	?>
	<input type="url"
		   name="proevent_company_settings[facebook]"
		   value="<?php echo esc_attr( $value ); ?>"
		   class="regular-text"
		   placeholder="https://facebook.com/yourpage" />
	<?php
}

function proevent_company_field_social_instagram_cb() {
	$settings = proevent_company_get_settings();
	$value    = $settings['instagram'];
	?>
	<input type="url"
		   name="proevent_company_settings[instagram]"
		   value="<?php echo esc_attr( $value ); ?>"
		   class="regular-text"
		   placeholder="https://instagram.com/yourprofile" />
	<?php
}

function proevent_company_field_social_twitter_cb() {
	$settings = proevent_company_get_settings();
	$value    = $settings['twitter'];
	?>
	<input type="url"
		   name="proevent_company_settings[twitter]"
		   value="<?php echo esc_attr( $value ); ?>"
		   class="regular-text"
		   placeholder="https://x.com/yourhandle" />
	<?php
}

function proevent_company_field_social_linkedin_cb() {
	$settings = proevent_company_get_settings();
	$value    = $settings['linkedin'];
	?>
	<input type="url"
		   name="proevent_company_settings[linkedin]"
		   value="<?php echo esc_attr( $value ); ?>"
		   class="regular-text"
		   placeholder="https://linkedin.com/company/yourcompany" />
	<?php
}



function proevent_add_company_settings_page() {

	add_theme_page(
		__( 'Company Settings', 'my-project' ),
		__( 'Company Settings', 'my-project' ),
		'manage_options',
		'proevent-settings',
		'proevent_render_company_settings_page'
	);
}
add_action( 'admin_menu', 'proevent_add_company_settings_page' );



function proevent_render_company_settings_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	?>
	<div class="wrap proevent-settings-wrap">
		<h1><?php esc_html_e( 'Company Settings', 'my-project' ); ?></h1>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'proevent_company_settings_group' );
			do_settings_sections( 'proevent-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}



function proevent_admin_assets( $hook ) {

	if ( 'appearance_page_proevent-settings' !== $hook ) {
		return;
	}

	wp_enqueue_media();

	$js_path = get_stylesheet_directory() . '/assets/js/admin-settings.js';
	$ver     = file_exists( $js_path ) ? filemtime( $js_path ) : wp_get_theme()->get( 'Version' );

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script(
			'proevent-admin-settings',
			get_stylesheet_directory_uri() . '/assets/js/admin-settings.js',
			array( 'jquery' ),
			$ver,
			true
		);
	}
}
add_action( 'admin_enqueue_scripts', 'proevent_admin_assets' );



/**
 * Gutenberg blocks: Hero CTA + Event Grid
 */
function proevent_register_blocks() {

	// hero block is purely static markup, no render callback needed
	register_block_type(
		'proevent/hero-cta',
		array(
			'api_version'     => 2,
			'editor_script'   => 'proevent-blocks',
			'render_callback' => null,
		)
	);

	// event grid uses a render callback so it always pulls fresh upcoming events
	register_block_type(
		'proevent/event-grid',
		array(
			'api_version'     => 2,
			'editor_script'   => 'proevent-blocks',
			'render_callback' => 'proevent_render_event_grid_block',
			'attributes'      => array(
				'limit'    => array(
					'type'    => 'number',
					'default' => 6,
				),
				'category' => array(
					'type'    => 'string',
					'default' => '',
				),
				'sort'     => array(
					'type'    => 'string',
					'default' => 'upcoming', // upcoming | recent
				),
			),
		)
	);
}
add_action( 'init', 'proevent_register_blocks' );



function proevent_block_editor_assets() {

	$js_path = get_stylesheet_directory() . '/assets/js/blocks.js';
	if ( ! file_exists( $js_path ) ) {
		return;
	}

	$ver = filemtime( $js_path );

	wp_enqueue_script(
		'proevent-blocks',
		get_stylesheet_directory_uri() . '/assets/js/blocks.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-components',
			'wp-block-editor',
			'wp-i18n',
			'wp-data',
		),
		$ver,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'proevent_block_editor_assets' );



/**
 * render callback for Event Grid block
 */
function proevent_render_event_grid_block( $attributes, $content ) {

	$limit    = isset( $attributes['limit'] ) && (int) $attributes['limit'] > 0 ? (int) $attributes['limit'] : 6;
	$category = isset( $attributes['category'] ) ? sanitize_text_field( $attributes['category'] ) : '';
	$sort     = ! empty( $attributes['sort'] ) ? $attributes['sort'] : 'upcoming';

	$today = current_time( 'Y-m-d' );

	$meta_query = array();
	$order      = 'ASC';

	if ( 'upcoming' === $sort ) {
		$meta_query[] = array(
			'key'     => '_proevent_event_date',
			'value'   => $today,
			'compare' => '>=',
			'type'    => 'DATE',
		);
		$order = 'ASC';
	} else {
		// "recent" – show events from past + upcoming, newest first
		$order = 'DESC';
	}

	$args = array(
		'post_type'           => 'event',
		'posts_per_page'      => $limit,
		'ignore_sticky_posts' => true,
		'meta_key'            => '_proevent_event_date',
		'orderby'             => 'meta_value',
		'order'               => $order,
	);

	if ( ! empty( $meta_query ) ) {
		$args['meta_query'] = $meta_query;
	}

	if ( ! empty( $category ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'event-category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) : ?>
		<div class="proevent-event-grid grid gap-6 md:grid-cols-2 lg:grid-cols-3">
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

						<h3 class="text-base font-semibold mb-2">
							<a href="<?php the_permalink(); ?>" class="hover:underline">
								<?php the_title(); ?>
							</a>
						</h3>

						<?php if ( $location ) : ?>
							<div class="text-xs text-slate-600 mb-3">
								<?php echo esc_html( $location ); ?>
							</div>
						<?php endif; ?>

						<div class="text-sm text-slate-700 mb-4 flex-1">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
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
	<?php
	else :
		?>
		<p class="text-sm text-slate-500">
			<?php esc_html_e( 'No events found for this grid.', 'my-project' ); ?>
		</p>
		<?php
	endif;

	wp_reset_postdata();

	return ob_get_clean();
}



/**
 * REST endpoint: /wp-json/proevent/v1/next
 * returns 5 nearest upcoming events
 */
function proevent_register_rest_routes() {

	register_rest_route(
		'proevent/v1',
		'/next',
		array(
			'methods'             => 'GET',
			'callback'            => 'proevent_rest_get_next_events',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'proevent_register_rest_routes' );



function proevent_rest_get_next_events( WP_REST_Request $request ) {

	$today = current_time( 'Y-m-d' );

	$args = array(
		'post_type'           => 'event',
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => true,
		'meta_key'            => '_proevent_event_date',
		'orderby'             => 'meta_value',
		'order'               => 'ASC',
		'meta_query'          => array(
			array(
				'key'     => '_proevent_event_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	);

	$query = new WP_Query( $args );

	$data = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$event_date = get_post_meta( get_the_ID(), '_proevent_event_date', true );
			$event_time = get_post_meta( get_the_ID(), '_proevent_event_time', true );
			$location   = get_post_meta( get_the_ID(), '_proevent_event_location', true );
			$reg_link   = get_post_meta( get_the_ID(), '_proevent_event_registration_link', true );

			$data[] = array(
				'id'          => get_the_ID(),
				'title'       => get_the_title(),
				'permalink'   => get_permalink(),
				'date'        => $event_date,
				'time'        => $event_time,
				'location'    => $location,
				'registerUrl' => $reg_link,
				'excerpt'     => wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 25 ),
			);
		}
	}

	wp_reset_postdata();

	return rest_ensure_response( $data );
}

// make sure all images rendered via wp_get_attachment_image() lazy load,
// even if core ever changes its default behaviour.
function proevent_force_lazyload( $attr, $attachment, $size ) {

	if ( ! empty( $attr['loading'] ) ) {
		return $attr;
	}

	$attr['loading'] = 'lazy';

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'proevent_force_lazyload', 10, 3 );

// Prefer .webp if a sibling file exists for the same image
// e.g. my-image.jpg + my-image.webp → serve the .webp url.
function proevent_use_webp_if_available( $image, $attachment_id, $size, $icon ) {

	if ( empty( $image ) || ! is_array( $image ) || empty( $image[0] ) ) {
		return $image;
	}

	$original_url = $image[0];

	$uploads = wp_get_upload_dir();
	if ( empty( $uploads['basedir'] ) || empty( $uploads['baseurl'] ) ) {
		return $image;
	}

	// only touch urls inside uploads
	if ( strpos( $original_url, $uploads['baseurl'] ) !== 0 ) {
		return $image;
	}

	$relative_path = substr( $original_url, strlen( $uploads['baseurl'] ) );
	$filepath      = $uploads['basedir'] . $relative_path;

	$info = pathinfo( $filepath );
	if ( empty( $info['extension'] ) ) {
		return $image;
	}

	// already webp, nothing to swap
	if ( strtolower( $info['extension'] ) === 'webp' ) {
		return $image;
	}

	$webp_path = $info['dirname'] . '/' . $info['filename'] . '.webp';
	if ( ! file_exists( $webp_path ) ) {
		return $image;
	}

	$webp_relative = str_replace( $uploads['basedir'], '', $webp_path );
	$webp_url      = trailingslashit( $uploads['baseurl'] ) . ltrim( $webp_relative, '/' );

	$image[0] = $webp_url;

	return $image;
}
add_filter( 'wp_get_attachment_image_src', 'proevent_use_webp_if_available', 10, 4 );
