<?php
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



/**
 * enqueue compiled tailwind css + a small js file (later)
 */
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

	// placeholder js, we can wire up block logic / small interactivity later
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

	// labels are pretty standard, keeping them simple
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
		'label'               => __( 'Events', 'my-project' ),
		'labels'              => $labels,
		'public'              => true,
		'has_archive'         => true,
		'show_in_rest'        => true,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-calendar-alt',
		'rewrite'             => array(
			'slug'       => 'events',
			'with_front' => false,
		),
		'show_in_nav_menus'   => true,
		'capability_type'     => 'post',
	);

	register_post_type( 'event', $args );


	// taxonomy for event categories
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
 * Event meta box (manual custom fields: date, time, location, registration link)
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

	// nonce so we can verify on save
	wp_nonce_field( 'proevent_save_event_meta', 'proevent_event_meta_nonce' );

	$event_date  = get_post_meta( $post->ID, '_proevent_event_date', true );
	$event_time  = get_post_meta( $post->ID, '_proevent_event_time', true );
	$location    = get_post_meta( $post->ID, '_proevent_event_location', true );
	$reg_link    = get_post_meta( $post->ID, '_proevent_event_registration_link', true );

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
				   style="min-width: 220px;">
		</p>

		<p>
			<label for="proevent_event_time" style="display:block;font-weight:600;margin-bottom:4px;">
				<?php esc_html_e( 'Time', 'my-project' ); ?>
			</label>
			<input type="time"
				   id="proevent_event_time"
				   name="proevent_event_time"
				   value="<?php echo esc_attr( $event_time ); ?>"
				   style="min-width: 220px;">
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

	// quick safety checks
	if ( ! isset( $_POST['proevent_event_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['proevent_event_meta_nonce'], 'proevent_save_event_meta' ) ) {
		return;
	}

	// don't run during autosave / revisions
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'event' !== get_post_type( $post_id ) ) {
		return;
	}

	// capability check: only users who can edit this event
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// ok, now we can save the fields
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
