// File: wp-content/themes/ProEvent/assets/js/blocks.js
// very simple, no build step, just using wp.* globals and createElement

(function ( wp ) {

	if ( ! wp || ! wp.blocks ) {
		// editor not ready or something odd – just bail
		return;
	}

	var el = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var __ = wp.i18n.__;
	var components = wp.components;
	var editor = wp.blockEditor || wp.editor;
	var data = wp.data;

	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var URLInput = editor.URLInput;
	var InspectorControls = editor.InspectorControls;
	var RichText = editor.RichText;
	var MediaUpload = editor.MediaUpload;
	var MediaUploadCheck = editor.MediaUploadCheck;

	function safeArray( value ) {
		if ( ! value ) {
			return [];
		}
		if ( Array.isArray( value ) ) {
			return value;
		}
		return [];
	}

	/* ------------------------------------------------------------------------
	 * Hero with CTA block
	 *  - now supports background image + optional dark overlay
	 * --------------------------------------------------------------------- */

	registerBlockType( 'proevent/hero-cta', {

		title: __( 'ProEvent Hero with CTA', 'my-project' ),
		icon: 'megaphone',
		category: 'layout',

		attributes: {
			title: {
				type: 'string',
				source: 'html',
				selector: 'h2'
			},
			text: {
				type: 'string',
				source: 'html',
				selector: 'p'
			},
			ctaText: {
				type: 'string',
				default: ''
			},
			ctaUrl: {
				type: 'string',
				default: ''
			},
			bgImageUrl: {
				type: 'string',
				default: ''
			},
			bgImageId: {
				type: 'number'
			},
			darkOverlay: {
				type: 'boolean',
				default: true
			}
		},

		edit: function ( props ) {

			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var bgStyle = {};
			if ( attributes.bgImageUrl ) {
				bgStyle.backgroundImage = 'url(' + attributes.bgImageUrl + ')';
			}

			// background controls
			var bgControls = el(
				PanelBody,
				{ title: __( 'Background', 'my-project' ), initialOpen: false },

				el( 'p', { className: 'components-base-control__help' },
					__( 'Use a background image or leave it as a solid hero.', 'my-project' )
				),

				el( MediaUploadCheck, {},
					el( MediaUpload, {
						onSelect: function ( media ) {
							if ( ! media ) {
								return;
							}
							setAttributes( {
								bgImageUrl: media.url || '',
								bgImageId: media.id || 0
							} );
						},
						allowedTypes: [ 'image' ],
						value: attributes.bgImageId || 0,
						render: function ( obj ) {
							var open = obj.open;
							return el(
								'div',
								null,
								el(
									'button',
									{
										type: 'button',
										className: 'components-button is-primary',
										onClick: open
									},
									attributes.bgImageUrl
										? __( 'Change background image', 'my-project' )
										: __( 'Select background image', 'my-project' )
								),
								attributes.bgImageUrl &&
								el(
									'button',
									{
										type: 'button',
										className: 'components-button is-link is-destructive',
										style: { marginLeft: '6px' },
										onClick: function () {
											setAttributes( {
												bgImageUrl: '',
												bgImageId: 0
											} );
										}
									},
									__( 'Remove', 'my-project' )
								)
							);
						}
					} )
				),

				attributes.bgImageUrl &&
				el( components.ToggleControl, {
					label: __( 'Dark overlay on image', 'my-project' ),
					help: __( 'Helps text stay readable on busy photos.', 'my-project' ),
					checked: !! attributes.darkOverlay,
					onChange: function ( value ) {
						setAttributes( { darkOverlay: !! value } );
					}
				} )
			);

			var inspector = el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'CTA Settings', 'my-project' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Button text', 'my-project' ),
						value: attributes.ctaText,
						onChange: function ( value ) {
							setAttributes( { ctaText: value } );
						}
					} ),
					el( URLInput, {
						label: __( 'Button link', 'my-project' ),
						value: attributes.ctaUrl,
						onChange: function ( value ) {
							setAttributes( { ctaUrl: value } );
						}
					} )
				),
				bgControls
			);

			var heroButton = null;
			if ( attributes.ctaText ) {
				heroButton = el(
					'a',
					{
						href: attributes.ctaUrl || '#',
						className: 'inline-flex items-center px-5 py-3 rounded-md bg-primary hover:bg-primary/80 text-sm font-semibold'
					},
					attributes.ctaText
				);
			}

			var wrapperClasses = 'proevent-hero-cta relative overflow-hidden rounded-xl px-6 py-10 md:px-10 md:py-16';
			if ( attributes.bgImageUrl ) {
				wrapperClasses += ' bg-cover bg-center text-white';
			} else {
				wrapperClasses += ' bg-slate-900 text-white';
			}

			var overlay = null;
			if ( attributes.bgImageUrl && attributes.darkOverlay ) {
				overlay = el(
					'div',
					{
						className: 'absolute inset-0 bg-black/60'
					}
				);
			}

			var content = el(
				'div',
				{
					className: 'relative max-w-xl'
				},
				el( RichText, {
					tagName: 'h2',
					className: 'text-3xl md:text-4xl font-bold mb-4',
					placeholder: __( 'Hero title…', 'my-project' ),
					value: attributes.title,
					onChange: function ( value ) {
						setAttributes( { title: value } );
					}
				} ),
				el( RichText, {
					tagName: 'p',
					className: 'text-sm md:text-base text-slate-200 mb-6',
					placeholder: __( 'Short supporting text for the hero section.', 'my-project' ),
					value: attributes.text,
					onChange: function ( value ) {
						setAttributes( { text: value } );
					}
				} ),
				heroButton
			);

			var block = el(
				'section',
				{
					className: wrapperClasses,
					style: bgStyle
				},
				overlay,
				content
			);

			return el( wp.element.Fragment, null, inspector, block );
		},

		save: function ( props ) {

			var attributes = props.attributes;

			var bgStyle = {};
			if ( attributes.bgImageUrl ) {
				bgStyle.backgroundImage = 'url(' + attributes.bgImageUrl + ')';
			}

			var wrapperClasses = 'proevent-hero-cta relative overflow-hidden rounded-xl px-6 py-10 md:px-10 md:py-16';
			if ( attributes.bgImageUrl ) {
				wrapperClasses += ' bg-cover bg-center text-white';
			} else {
				wrapperClasses += ' bg-slate-900 text-white';
			}

			var overlay = null;
			if ( attributes.bgImageUrl && attributes.darkOverlay ) {
				overlay = el(
					'div',
					{
						className: 'absolute inset-0 bg-black/60'
					}
				);
			}

			var heroButton = null;
			if ( attributes.ctaText ) {
				heroButton = el(
					'a',
					{
						href: attributes.ctaUrl || '#',
						className: 'inline-flex items-center px-5 py-3 rounded-md bg-primary hover:bg-primary/80 text-sm font-semibold'
					},
					attributes.ctaText
				);
			}

			return el(
				'section',
				{
					className: wrapperClasses,
					style: bgStyle
				},
				overlay,
				el(
					'div',
					{ className: 'relative max-w-xl' },
					attributes.title &&
						el( RichText.Content, {
							tagName: 'h2',
							className: 'text-3xl md:text-4xl font-bold mb-4',
							value: attributes.title
						} ),
					attributes.text &&
						el( RichText.Content, {
							tagName: 'p',
							className: 'text-sm md:text-base text-slate-200 mb-6',
							value: attributes.text
						} ),
					heroButton
				)
			);
		}

	} );



	/* ------------------------------------------------------------------------
	 * helper: load event categories from REST
	 * --------------------------------------------------------------------- */

	function useEventCategories() {
		return data.useSelect( function ( select ) {
			var core = select( 'core' );
			if ( ! core || ! core.getEntityRecords ) {
				return [];
			}
			var records = core.getEntityRecords( 'taxonomy', 'event-category', { per_page: -1 } );
			return safeArray( records );
		}, [] );
	}



	/* ------------------------------------------------------------------------
	 * Event Grid block
	 * --------------------------------------------------------------------- */

	registerBlockType( 'proevent/event-grid', {

		title: __( 'ProEvent Event Grid', 'my-project' ),
		icon: 'grid-view',
		category: 'widgets',

		attributes: {
			limit: {
				type: 'number',
				default: 6
			},
			category: {
				type: 'string',
				default: ''
			},
			sort: {
				type: 'string',
				default: 'upcoming'
			}
		},

		edit: function ( props ) {

			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var categories = useEventCategories() || [];

			var categoryOptions = [
				{ label: __( 'All categories', 'my-project' ), value: '' }
			];

			categories.forEach( function ( term ) {
				categoryOptions.push( {
					label: term && term.name ? term.name : '',
					value: term && term.slug ? term.slug : ''
				} );
			} );

			var inspector = el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Event Grid Settings', 'my-project' ), initialOpen: true },
					el( RangeControl, {
						label: __( 'Number of events', 'my-project' ),
						min: 1,
						max: 12,
						value: attributes.limit,
						onChange: function ( value ) {
							setAttributes( { limit: value } );
						}
					} ),
					el( SelectControl, {
						label: __( 'Category', 'my-project' ),
						value: attributes.category,
						options: categoryOptions,
						onChange: function ( value ) {
							setAttributes( { category: value } );
						}
					} ),
					el( SelectControl, {
						label: __( 'Sorting', 'my-project' ),
						value: attributes.sort,
						options: [
							{ label: __( 'Upcoming (soonest first)', 'my-project' ), value: 'upcoming' },
							{ label: __( 'Recent (newest first)', 'my-project' ), value: 'recent' }
						],
						onChange: function ( value ) {
							setAttributes( { sort: value } );
						}
					} )
				)
			);

			var summary = el(
				'ul',
				{ className: 'text-xs text-slate-600 space-y-1' },
				el(
					'li',
					null,
					el( 'strong', null, __( 'Limit:', 'my-project' ) ),
					' ',
					String( attributes.limit || 0 )
				),
				el(
					'li',
					null,
					el( 'strong', null, __( 'Category:', 'my-project' ) ),
					' ',
					attributes.category || __( 'All', 'my-project' )
				),
				el(
					'li',
					null,
					el( 'strong', null, __( 'Sort:', 'my-project' ) ),
					' ',
					attributes.sort === 'upcoming'
						? __( 'Upcoming', 'my-project' )
						: __( 'Recent', 'my-project' )
				)
			);

			var box = el(
				'div',
				{
					className: 'proevent-event-grid-placeholder border border-dashed border-slate-300 rounded-md p-4'
				},
				el(
					'p',
					{ className: 'text-sm mb-2' },
					__( 'Event Grid preview (frontend will render live events).', 'my-project' )
				),
				summary
			);

			return el( wp.element.Fragment, null, inspector, box );
		},

		save: function () {
			// dynamic block – markup comes from PHP
			return null;
		}

	} );

})( window.wp || {} );
