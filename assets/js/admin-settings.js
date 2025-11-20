// File: wp-content/themes/ProEvent/assets/js/admin-settings.js
// small helper script for the Company Settings page:
// - hook into WP media uploader for logo
// - sync color input[type=color] with text field

jQuery( function ( $ ) {

	// logo upload
	$( document ).on( 'click', '.proevent-logo-upload-btn', function ( e ) {
		e.preventDefault();

		var $btn    = $( this );
		var target  = $btn.data( 'target' );
		var $input  = $( target );

		if ( ! $input.length ) {
			return;
		}

		var frame = wp.media({
			title: 'Select logo',
			multiple: false,
			library: { type: [ 'image' ] }
		});

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			if ( attachment && attachment.url ) {
				$input.val( attachment.url ).trigger( 'change' );
			}
		});

		frame.open();
	});


	// color picker sync – nothing fancy
	$( document ).on( 'input change', '.proevent-color-picker', function () {
		var $picker = $( this );
		var target  = $picker.data( 'target' );
		var $input  = $( target );

		if ( $input.length ) {
			$input.val( $picker.val() );
		}
	});

});
