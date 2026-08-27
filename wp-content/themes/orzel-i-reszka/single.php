<?php
/**
 * Pojedynczy wpis (Aktualności).
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
				<p><a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ? get_post_type_archive_link( 'post' ) : home_url( '/aktualnosci/' ) ); ?>">&larr; <?php esc_html_e( 'Wróć do aktualności', 'orzel-i-reszka' ); ?></a></p>
				<h1><?php the_title(); ?></h1>
				<p>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</p>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="oir-featured-image"><?php the_post_thumbnail( 'large' ); ?></div>
				<?php endif; ?>
				<div class="entry-content-inner">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
