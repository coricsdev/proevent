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
 * stored as one option array: proevent_company_settings
 */
function proevent_register_company_settings() {

	// register main option
	register_setting(
		'proevent_company_settings_group',
		'proevent_company_settings',
		'proevent_sanitize_company_settings'
	);

	// section: general
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

	// section: socials
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

	$output['facebook'] = isset( $input['facebook'] ) ? esc_url_raw( $input['facebook'] ) : '';
	$output['instagram'] = isset( $input['instagram'] ) ? esc_url_raw( $input['instagram'] ) : '';
	$output['twitter']   = isset( $input['twitter'] ) ? esc_url_raw( $input['twitter'] ) : '';
	$output['linkedin']  = isset( $input['linkedin'] ) ? esc_url_raw( $input['linkedin'] ) : '';

	return $output;
}



// sections + fields callbacks
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
		$value = '#2563eb'; // small sensible default
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



/**
 * Company Settings admin page under Appearance
 */
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



/**
 * admin-side assets for settings page only
 * (media uploader + tiny script for color sync)
 */
function proevent_admin_assets( $hook ) {

	// slug will be appearance_page_proevent-settings
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