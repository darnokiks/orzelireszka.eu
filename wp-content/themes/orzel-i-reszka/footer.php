<?php
/**
 * Stopka strony.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer class="oir-footer">
		<div class="oir-container">
			<div>
				<strong><?php bloginfo( 'name' ); ?></strong><br>
				<?php esc_html_e( 'Projekt i wykonanie: Stowarzyszenie Orzeł i Reszka', 'orzel-i-reszka' ); ?>
			</div>
			<div class="oir-social">
				<a href="https://www.facebook.com/orzelireszka.eu" target="_blank" rel="noopener" aria-label="Facebook">FB</a>
				<a href="https://www.youtube.com/channel/UCzFrWcg2hs6ONzZb9jN0PAg" target="_blank" rel="noopener" aria-label="YouTube">YT</a>
			</div>
		</div>
		<div class="oir-footer-bottom">
			&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Wszelkie prawa zastrzeżone.', 'orzel-i-reszka' ); ?>
		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
