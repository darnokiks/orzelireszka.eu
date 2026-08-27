<?php
/**
 * Prosty formularz kontaktowy (bez wtyczek).
 * Wysyła wiadomość na adres e-mail administratora strony
 * (Ustawienia -> Ogólne -> Adres e-mail administratora).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function oir_handle_contact_form() {
	if ( ! isset( $_POST['oir_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['oir_contact_nonce'] ) ), 'oir_contact_form' ) ) {
		wp_die( esc_html__( 'Nieprawidłowe żądanie. Odśwież stronę i spróbuj ponownie.', 'orzel-i-reszka' ) );
	}

	$redirect = ! empty( $_POST['oir_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['oir_redirect'] ) ) : home_url( '/kontakt/' );

	// Honeypot - jeśli wypełnione przez bota, udajemy sukces i nic nie wysyłamy.
	if ( ! empty( $_POST['oir_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'oir_contact', 'success', $redirect ) );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( empty( $name ) || empty( $message ) || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'oir_contact', 'error', $redirect ) );
		exit;
	}

	$to      = get_option( 'admin_email' );
	$subject = sprintf( '[%s] Wiadomość ze strony od %s', get_bloginfo( 'name' ), $name );
	$body    = "Imię: {$name}\nE-mail: {$email}\n\nWiadomość:\n{$message}";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$sent = wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'oir_contact', $sent ? 'success' : 'error', $redirect ) );
	exit;
}
add_action( 'admin_post_nopriv_oir_contact', 'oir_handle_contact_form' );
add_action( 'admin_post_oir_contact', 'oir_handle_contact_form' );
