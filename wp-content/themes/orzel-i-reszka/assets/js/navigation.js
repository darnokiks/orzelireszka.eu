document.addEventListener( 'DOMContentLoaded', function () {
	var toggle = document.querySelector( '.oir-menu-toggle' );
	var nav = document.querySelector( '.oir-nav' );

	if ( toggle && nav ) {
		toggle.addEventListener( 'click', function () {
			var isOpen = nav.classList.toggle( 'oir-nav-open' );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );
	}

	// Rozwijanie podmenu po dotknięciu na urządzeniach mobilnych.
	document.querySelectorAll( '.oir-nav li.menu-item-has-children > a' ).forEach( function ( link ) {
		link.addEventListener( 'click', function ( e ) {
			if ( window.innerWidth > 880 ) {
				return;
			}
			var parent = link.parentElement;
			if ( ! parent.classList.contains( 'oir-open' ) ) {
				e.preventDefault();
				document.querySelectorAll( '.oir-nav li.oir-open' ).forEach( function ( el ) {
					if ( el !== parent ) {
						el.classList.remove( 'oir-open' );
					}
				} );
				parent.classList.add( 'oir-open' );
			}
		} );
	} );
} );
