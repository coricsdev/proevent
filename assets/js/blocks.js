// File: wp-content/themes/ProEvent/assets/js/blocks.js
// very lightweight block registrations – no build step, just using wp.* globals

( function ( wp ) {

	const { registerBlockType } = wp.blocks;
	const { __ } = wp.i18n;
	const { PanelBody, TextControl, TextareaControl, SelectControl, RangeControl } = wp.components;
	const { InspectorControls, RichText, URLInput } = wp.blockEditor || wp.editor;
	const { useSelect } = wp.data;

	// Hero with CTA block
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
			},
		},

		edit: function ( props ) {

			const { attributes, setAttributes } = props;

			return (
				<>
					<InspectorControls>
						<PanelBody title={ __( 'CTA Settings', 'my-project' ) }>
							<TextControl
								label={ __( 'Button text', 'my-project' ) }
								value={ attributes.ctaText }
								onChange={ function ( value ) {
									setAttributes( { ctaText: value } );
								} }
							/>
							<URLInput
								label={ __( 'Button link', 'my-project' ) }
								value={ attributes.ctaUrl }
								onChange={ function ( value ) {
									setAttributes( { ctaUrl: value } );
								} }
							/>
						</PanelBody>
					</InspectorControls>

					<section className="proevent-hero-cta bg-slate-900 text-white rounded-xl px-6 py-10 md:px-10 md:py-16">
						<RichText
							tagName="h2"
							className="text-3xl md:text-4xl font-bold mb-4"
							placeholder={ __( 'Hero title…', 'my-project' ) }
							value={ attributes.title }
							onChange={ function ( value ) {
								setAttributes( { title: value } );
							} }
						/>

						<RichText
							tagName="p"
							className="text-sm md:text-base text-slate-200 mb-6 max-w-xl"
							placeholder={ __( 'Short supporting text for the hero section.', 'my-project' ) }
							value={ attributes.text }
							onChange={ function ( value ) {
								setAttributes( { text: value } );
							} }
						/>

						{ attributes.ctaText && (
							<a
								href={ attributes.ctaUrl || '#' }
								className="inline-flex items-center px-5 py-3 rounded-md bg-blue-500 hover:bg-blue-600 text-sm font-semibold"
							>
								{ attributes.ctaText }
							</a>
						) }
					</section>
				</>
			);
		},

		save: function ( props ) {
			const { attributes } = props;

			return (
				<section className="proevent-hero-cta bg-slate-900 text-white rounded-xl px-6 py-10 md:px-10 md:py-16">
					{ attributes.title && (
						<RichText.Content
							tagName="h2"
							className="text-3xl md:text-4xl font-bold mb-4"
							value={ attributes.title }
						/>
					) }

					{ attributes.text && (
						<RichText.Content
							tagName="p"
							className="text-sm md:text-base text-slate-200 mb-6 max-w-xl"
							value={ attributes.text }
						/>
					) }

					{ attributes.ctaText && (
						<a
							href={ attributes.ctaUrl || '#' }
							className="inline-flex items-center px-5 py-3 rounded-md bg-blue-500 hover:bg-blue-600 text-sm font-semibold"
						>
							{ attributes.ctaText }
						</a>
					) }
				</section>
			);
		},
	} );



	// small helper hook to fetch event categories
	function useEventCategories() {
		return useSelect( function ( select ) {
			const store = select( 'core' );
			if ( ! store || ! store.getEntityRecords ) {
				return [];
			}
			return store.getEntityRecords( 'taxonomy', 'event-category', { per_page: -1 } ) || [];
		}, [] );
	}



	// Event Grid block
	registerBlockType( 'proevent/event-grid', {

		title: __( 'ProEvent Event Grid', 'my-project' ),
		icon: 'grid-view',
		category: 'widgets',

		attributes: {
			limit: {
				type: 'number',
				default: 6,
			},
			category: {
				type: 'string',
				default: '',
			},
			sort: {
				type: 'string',
				default: 'upcoming',
			},
		},

		edit: function ( props ) {

			const { attributes, setAttributes } = props;
			const categories = useEventCategories() || [];

			const categoryOptions = [
				{ label: __( 'All categories', 'my-project' ), value: '' },
			].concat(
				categories.map( function ( term ) {
					return {
						label: term.name,
						value: term.slug,
					};
				} )
			);

			return (
				<>
					<InspectorControls>
						<PanelBody title={ __( 'Event Grid Settings', 'my-project' ) } initialOpen={ true }>
							<RangeControl
								label={ __( 'Number of events', 'my-project' ) }
								min={ 1 }
								max={ 12 }
								value={ attributes.limit }
								onChange={ function ( value ) {
									setAttributes( { limit: value } );
								} }
							/>

							<SelectControl
								label={ __( 'Category', 'my-project' ) }
								value={ attributes.category }
								options={ categoryOptions }
								onChange={ function ( value ) {
									setAttributes( { category: value } );
								} }
							/>

							<SelectControl
								label={ __( 'Sorting', 'my-project' ) }
								value={ attributes.sort }
								options={ [
									{ label: __( 'Upcoming (soonest first)', 'my-project' ), value: 'upcoming' },
									{ label: __( 'Recent (newest date first)', 'my-project' ), value: 'recent' },
								] }
								onChange={ function ( value ) {
									setAttributes( { sort: value } );
								} }
							/>
						</PanelBody>
					</InspectorControls>

					<div className="proevent-event-grid-placeholder border border-dashed border-slate-300 rounded-md p-4">
						<p className="text-sm mb-2">
							{ __( 'Event Grid preview (frontend will render live events).', 'my-project' ) }
						</p>
						<ul className="text-xs text-slate-600 space-y-1">
							<li>
								<strong>{ __( 'Limit:', 'my-project' ) }</strong> { attributes.limit }
							</li>
							<li>
								<strong>{ __( 'Category:', 'my-project' ) }</strong>{ ' ' }
								{ attributes.category || __( 'All', 'my-project' ) }
							</li>
							<li>
								<strong>{ __( 'Sort:', 'my-project' ) }</strong>{ ' ' }
								{ attributes.sort === 'upcoming'
									? __( 'Upcoming', 'my-project' )
									: __( 'Recent', 'my-project' ) }
							</li>
						</ul>
					</div>
				</>
			);
		},

		// dynamic block: saved on server, so we don't save markup here
		save: function () {
			return null;
		},
	} );

} )( window.wp );
