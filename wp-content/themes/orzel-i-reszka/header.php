<?php
/**
 * Nagłówek strony.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#oir-content"><?php esc_html_e( 'Przejdź do treści', 'orzel-i-reszka' ); ?></a>

<div class="oir-topbar">
	<div class="oir-container">
		<div class="oir-social">
			<a href="https://www.facebook.com/orzelireszka.eu" target="_blank" rel="noopener" aria-label="Facebook">FB</a>
			<a href="https://www.youtube.com/channel/UCzFrWcg2hs6ONzZb9jN0PAg" target="_blank" rel="noopener" aria-label="YouTube">YT</a>
		</div>
	</div>
</div>

<header class="oir-header">
	<div class="oir-header-inner">
		<div class="oir-logo">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="oir-logo-text">
					<?php bloginfo( 'name' ); ?>
					<small><?php bloginfo( 'description' ); ?></small>
				</a>
				<?php
			}
			?>
		</div>

		<button class="oir-menu-toggle" aria-expanded="false" aria-label="<?php esc_attr_e( 'Otwórz menu', 'orzel-i-reszka' ); ?>">☰</button>

		<nav class="oir-nav" aria-label="<?php esc_attr_e( 'Menu główne', 'orzel-i-reszka' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'oir-plain',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</header>
