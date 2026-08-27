<?php
/**
 * Szablon strony głównej.
 * Cała treść (nagłówek, karty, sekcje) jest edytowalna w edytorze bloków
 * WordPressa: Strony -> Strona Główna -> Edytuj.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="oir-content">
	<div class="oir-container">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
