<?php
// File: wp-content/themes/ProEvent/header.php

// moved company settings logic here so header can use logo + brand color

$company_settings = array();
if ( function_exists( 'proevent_company_get_settings' ) ) {
	$company_settings = proevent_company_get_settings();
}

$company_logo  = ! empty( $company_settings['logo'] ) ? $company_settings['logo'] : '';
$brand_color   = ! empty( $company_settings['brand_color'] ) ? $company_settings['brand_color'] : '#2563eb';

// tiny inline style – not trying to overdo it
$header_border_style = 'border-bottom: 3px solid ' . esc_attr( $brand_color ) . ';';

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-gray-50' ); ?>>

<header class="border-b border-slate-200" style="<?php echo esc_attr( $header_border_style ); ?>">
	<div class="max-w-6xl mx-auto flex items-center justify-between px-4 py-3">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2">
			<?php if ( $company_logo ) : ?>
				<img
					src="<?php echo esc_url( $company_logo ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					class="h-8 w-auto"
					loading="lazy"
				/>
			<?php else : ?>
				<span class="font-semibold text-lg" style="color: <?php echo esc_attr( $brand_color ); ?>;">
					<?php bloginfo( 'name' ); ?>
				</span>
			<?php endif; ?>
		</a>

		<nav class="text-sm">
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
	</div>
</header>

<main class="min-h-screen">
