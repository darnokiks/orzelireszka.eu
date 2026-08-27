<?php
/**
 * Szablon strony Kontakt.
 * Dane teleadresowe i tekst wstępu można edytować normalnie w edytorze
 * (Strony -> Kontakt -> Edytuj) - ta część nad formularzem to the_content().
 * Sam formularz i dane rejestrowe (KRS/NIP/REGON) można zmienić poniżej w kodzie
 * albo, jeśli wolisz w pełni bez kodu, przenieść je do treści strony w edytorze.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$notice = isset( $_GET['oir_contact'] ) ? sanitize_text_field( wp_unslash( $_GET['oir_contact'] ) ) : '';
?>

<main id="oir-content" class="oir-page-content">
	<div class="oir-container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<h1><?php the_title(); ?></h1>
			<div class="entry-content-inner"><?php the_content(); ?></div>
			<?php
		endwhile;
		?>

		<div class="oir-contact-grid">
			<div class="oir-contact-info">
				<div class="oir-contact-item">
					<h4><?php esc_html_e( 'Odwiedź nas', 'orzel-i-reszka' ); ?></h4>
					<p>Stowarzyszenie Orzeł i Reszka<br>26-600 Radom, ul. Niedziałkowskiego 33</p>
				</div>
				<div class="oir-contact-item">
					<h4><?php esc_html_e( 'Godziny pracy', 'orzel-i-reszka' ); ?></h4>
					<p>Pn&ndash;Pt 11.00&ndash;17.00</p>
				</div>
				<div class="oir-contact-item">
					<h4><?php esc_html_e( 'Zadzwoń do nas', 'orzel-i-reszka' ); ?></h4>
					<p><a href="tel:+48602466382">+48 602 466 382</a></p>
				</div>
				<div class="oir-contact-item">
					<h4><?php esc_html_e( 'Napisz do nas', 'orzel-i-reszka' ); ?></h4>
					<p><a href="mailto:kontakt@orzelireszka.eu">kontakt@orzelireszka.eu</a></p>
				</div>

				<div class="oir-legal">
					KRS: 0000741286<br>
					NIP: 7962977322<br>
					REGON: 367030293
				</div>
			</div>

			<div class="oir-contact-form-wrap">
				<h3><?php esc_html_e( 'Wyślij wiadomość do nas', 'orzel-i-reszka' ); ?></h3>

				<?php if ( 'success' === $notice ) : ?>
					<div class="oir-form-notice success"><?php esc_html_e( 'Dziękujemy! Wiadomość została wysłana.', 'orzel-i-reszka' ); ?></div>
				<?php elseif ( 'error' === $notice ) : ?>
					<div class="oir-form-notice error"><?php esc_html_e( 'Nie udało się wysłać wiadomości. Sprawdź dane i spróbuj ponownie.', 'orzel-i-reszka' ); ?></div>
				<?php endif; ?>

				<form class="oir-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="oir_contact">
					<input type="hidden" name="oir_redirect" value="<?php echo esc_url( get_permalink() ); ?>">
					<?php wp_nonce_field( 'oir_contact_form', 'oir_contact_nonce' ); ?>
					<p class="oir-hp-field" aria-hidden="true">
						<label for="oir_website"><?php esc_html_e( 'Zostaw to pole puste', 'orzel-i-reszka' ); ?></label>
						<input type="text" id="oir_website" name="oir_website" tabindex="-1" autocomplete="off">
					</p>

					<p>
						<label for="oir-name"><?php esc_html_e( 'Imię', 'orzel-i-reszka' ); ?></label>
						<input type="text" id="oir-name" name="name" placeholder="<?php esc_attr_e( 'Twoje imię', 'orzel-i-reszka' ); ?>" required>
					</p>
					<p>
						<label for="oir-email"><?php esc_html_e( 'E-mail', 'orzel-i-reszka' ); ?></label>
						<input type="email" id="oir-email" name="email" placeholder="<?php esc_attr_e( 'Adres e-mail', 'orzel-i-reszka' ); ?>" required>
					</p>
					<p>
						<label for="oir-message"><?php esc_html_e( 'Wiadomość', 'orzel-i-reszka' ); ?></label>
						<textarea id="oir-message" name="message" placeholder="<?php esc_attr_e( 'Treść wiadomości', 'orzel-i-reszka' ); ?>" required></textarea>
					</p>
					<p>
						<button type="submit" class="oir-btn"><?php esc_html_e( 'Wyślij', 'orzel-i-reszka' ); ?></button>
					</p>
				</form>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
