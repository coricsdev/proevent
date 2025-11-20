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

	// small helper to avoid .default fun if something is missing
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
	 * --------------------------------------------------------------------- */

	registerBlockType( 'proevent/hero-cta', {

		title: __( 'ProEvent Hero with CTA', 'my-project' ),
		icon: 'megaphone',
		category: 'layout',

		attributes: {
			title: {
				type: 'string',
				source: 'html',
				selector: 'h2',
			},
			text: {
				type: 'string',
				source: 'html',
				selector: 'p',
			},
			ctaText: {
				type: 'string',
				default: '',
			},
			ctaUrl: {
				type: 'string',
				default: '',
			}
		},

		edit: function ( props ) {

			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

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
				)
			);

			var heroButton = null;
			if ( attributes.ctaText ) {
				heroButton = el(
					'a',
					{
						href: attributes.ctaUrl || '#',
						className: 'inline-flex items-center px-5 py-3 rounded-md bg-blue-500 hover:bg-blue-600 text-sm font-semibold'
					},
					attributes.ctaText
				);
			}

			var block = el(
				'section',
				{
					className: 'proevent-hero-cta bg-slate-900 text-white rounded-xl px-6 py-10 md:px-10 md:py-16'
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
					className: 'text-sm md:text-base text-slate-200 mb-6 max-w-xl',
					placeholder: __( 'Short supporting text for the hero section.', 'my-project' ),
					value: attributes.text,
					onChange: function ( value ) {
						setAttributes( { text: value } );
					}
				} ),
				heroButton
			);

			return el( wp.element.Fragment, null, inspector, block );
		},

		save: function ( props ) {

			var attributes = props.attributes;

			var heroButton = null;
			if ( attributes.ctaText ) {
				heroButton = el(
					'a',
					{
						href: attributes.ctaUrl || '#',
						className: 'inline-flex items-center px-5 py-3 rounded-md bg-blue-500 hover:bg-blue-600 text-sm font-semibold'
					},
					attributes.ctaText
				);
			}

			return el(
				'section',
				{
					className: 'proevent-hero-cta bg-slate-900 text-white rounded-xl px-6 py-10 md:px-10 md:py-16'
				},
				attributes.title &&
					el( RichText.Content, {
						tagName: 'h2',
						className: 'text-3xl md:text-4xl font-bold mb-4',
						value: attributes.title
					} ),
				attributes.text &&
					el( RichText.Content, {
						tagName: 'p',
						className: 'text-sm md:text-base text-slate-200 mb-6 max-w-xl',
						value: attributes.text
					} ),
				heroButton
			);
		}

	} );



	/* ------------------------------------------------------------------------
	 * Helper: load event categories from REST
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
				default: 'upcoming' // upcoming | recent
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
							{
								label: __( 'Upcoming (soonest first)', 'my-project' ),
								value: 'upcoming'
							},
							{
								label: __( 'Recent (newest first)', 'my-project' ),
								value: 'recent'
							}
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

		// dynamic block → frontend markup comes from PHP render callback
		save: function () {
			return null;
		}

	} );

})( window.wp || {} );
