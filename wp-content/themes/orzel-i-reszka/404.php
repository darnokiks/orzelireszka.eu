<?php
/**
 * Strona błędu 404.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="oir-content" class="oir-page-content">
	<div class="oir-container oir-narrow" style="text-align:center;">
		<h1><?php esc_html_e( 'Nie znaleziono strony', 'orzel-i-reszka' ); ?></h1>
		<p><?php esc_html_e( 'Strona, której szukasz, nie istnieje lub została przeniesiona.', 'orzel-i-reszka' ); ?></p>
		<p><a class="oir-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Wróć na stronę główną', 'orzel-i-reszka' ); ?></a></p>
	</div>
</main>

<?php
get_footer();
