<?php
// simple footer, will probably pull company settings later.
?>
</main>

<footer class="border-t border-slate-200 mt-10">
	<div class="max-w-6xl mx-auto px-4 py-6 text-sm text-slate-500 flex justify-between gap-3 flex-wrap">
		<div>
			&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
		</div>

		<nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'flex gap-3',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
