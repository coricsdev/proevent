<?php
// pretty barebones header for now, we’ll refine once layout is clear.
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-gray-50' ); ?>>

<header class="border-b border-slate-200">
	<div class="max-w-6xl mx-auto flex items-center justify-between px-4 py-3">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="font-semibold text-lg">
			<?php bloginfo( 'name' ); ?>
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
