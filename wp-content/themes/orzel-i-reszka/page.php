<?php
/**
 * Domyślny szablon dla zwykłych stron (Strona Główna, Działalność, O Stowarzyszeniu, itd.).
 * Treść pochodzi w całości z edytora WordPressa (Strony -> Edytuj).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="oir-content" class="oir-page-content">
	<div class="oir-container oir-narrow">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<?php if ( ! is_front_page() ) : ?>
					<h1><?php the_title(); ?></h1>
				<?php endif; ?>
				<div class="entry-content-inner">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
