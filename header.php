<?php
// File: wp-content/themes/ProEvent/header.php

$company_settings = array();
if ( function_exists( 'proevent_company_get_settings' ) ) {
	$company_settings = proevent_company_get_settings();
}

$company_logo = ! empty( $company_settings['logo'] ) ? $company_settings['logo'] : '';
$brand_color  = ! empty( $company_settings['brand_color'] ) ? $company_settings['brand_color'] : '#2563eb';

// not going crazy with inline styles, just a bit of color hook
$header_border_style = 'border-bottom: 3px solid ' . esc_attr( $brand_color ) . ';';

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		$company = function_exists('proevent_company_get_settings')
			? proevent_company_get_settings()
			: array();

		$brand = ! empty( $company['brand_color'] )
			? $company['brand_color']
			: '#2563eb';
		?>
		<style>
			:root {
				--proevent-brand: <?php echo esc_attr( $brand ); ?>;
			}
		</style>
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-slate-50' ); ?>>

<header class="bg-white/95 backdrop-blur border-b border-slate-200 sticky top-0 z-40" style="<?php echo esc_attr( $header_border_style ); ?>">
	<div class="proevent-shell flex items-center justify-between py-3 md:py-4 gap-4">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2 min-w-0">
			<?php if ( $company_logo ) : ?>
				<img
					src="<?php echo esc_url( $company_logo ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					class="h-8 w-auto md:h-9"
					loading="lazy"
				/>
			<?php else : ?>
				<span class="font-semibold text-lg md:text-xl truncate" style="color: <?php echo esc_attr( $brand_color ); ?>;">
					<?php bloginfo( 'name' ); ?>
				</span>
			<?php endif; ?>
		</a>

		<nav class="proevent-header-nav">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex gap-4',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<!-- super simple mobile hint; we could do a full menu later -->
		<div class="proevent-header-nav--mobile text-xs text-slate-500">
			<?php esc_html_e( 'Menu', 'my-project' ); ?>
		</div>

	</div>
</header>

<main class="min-h-screen">
