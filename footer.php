<?php
// File: wp-content/themes/ProEvent/footer.php

$company_settings = array();
if ( function_exists( 'proevent_company_get_settings' ) ) {
	$company_settings = proevent_company_get_settings();
}

$brand_color = ! empty( $company_settings['brand_color'] ) ? $company_settings['brand_color'] : '#2563eb';

$social_links = array(
	'facebook'  => array(
		'label' => __( 'Facebook', 'my-project' ),
		'url'   => ! empty( $company_settings['facebook'] ) ? $company_settings['facebook'] : '',
	),
	'instagram' => array(
		'label' => __( 'Instagram', 'my-project' ),
		'url'   => ! empty( $company_settings['instagram'] ) ? $company_settings['instagram'] : '',
	),
	'twitter'   => array(
		'label' => __( 'X / Twitter', 'my-project' ),
		'url'   => ! empty( $company_settings['twitter'] ) ? $company_settings['twitter'] : '',
	),
	'linkedin'  => array(
		'label' => __( 'LinkedIn', 'my-project' ),
		'url'   => ! empty( $company_settings['linkedin'] ) ? $company_settings['linkedin'] : '',
	),
);

$has_social = false;
foreach ( $social_links as $item ) {
	if ( ! empty( $item['url'] ) ) {
		$has_social = true;
		break;
	}
}
?>
</main>

<footer class="proevent-footer bg-white/90">
	<div class="proevent-footer-inner">

		<div class="order-2 md:order-1">
			&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
		</div>

		<?php if ( $has_social ) : ?>
			<div class="proevent-footer-social order-1 md:order-2">
				<span class="text-[11px] uppercase tracking-wide text-slate-400">
					<?php esc_html_e( 'Follow us', 'my-project' ); ?>
				</span>

				<?php foreach ( $social_links as $social ) : ?>
					<?php if ( empty( $social['url'] ) ) : continue; endif; ?>
					<a
						href="<?php echo esc_url( $social['url'] ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="text-xs font-medium hover:underline"
						style="color: <?php echo esc_attr( $brand_color ); ?>;"
					>
						<?php echo esc_html( $social['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<nav class="order-3">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'proevent-footer-menu',
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
