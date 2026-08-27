<?php
/**
 * Lista wpisów - używana m.in. jako strona "Aktualności"
 * (ustawiona w Ustawienia -> Czytanie -> Strona wpisów).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="oir-content" class="oir-page-content">
	<div class="oir-container">
		<?php if ( is_home() && ! is_front_page() ) : ?>
			<h1><?php single_post_title(); ?></h1>
		<?php elseif ( is_search() ) : ?>
			<h1><?php printf( esc_html__( 'Wyniki wyszukiwania dla: %s', 'orzel-i-reszka' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		<?php endif; ?>

		<form role="search" method="get" class="oir-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="max-width:420px;margin-bottom:30px;display:flex;gap:8px;">
			<label class="screen-reader-text" for="oir-s"><?php esc_html_e( 'Szukaj', 'orzel-i-reszka' ); ?></label>
			<input type="search" id="oir-s" name="s" placeholder="<?php esc_attr_e( 'Szukaj…', 'orzel-i-reszka' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" style="flex:1;padding:10px 14px;border:1px solid var(--oir-line);border-radius:8px;">
			<button type="submit" class="oir-btn"><?php esc_html_e( 'Szukaj', 'orzel-i-reszka' ); ?></button>
		</form>

		<?php if ( have_posts() ) : ?>
			<div class="oir-posts">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'oir-post-card' ); ?>>
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large' ); ?>
							<?php else : ?>
								<div style="aspect-ratio:16/10;background:var(--oir-cream);"></div>
							<?php endif; ?>
						</a>
						<div class="oir-post-body">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<div class="oir-pagination">
				<?php
				echo paginate_links(
					array(
						'prev_text' => __( '&laquo; Poprzednie', 'orzel-i-reszka' ),
						'next_text' => __( 'Następne &raquo;', 'orzel-i-reszka' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Nie ma jeszcze żadnych wpisów. Dodaj pierwszy wpis w panelu: Wpisy -> Dodaj nowy.', 'orzel-i-reszka' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
