document.addEventListener('DOMContentLoaded', function () {

	/* Nawigacja mobilna */
	var toggle = document.querySelector('.menu-toggle');
	var nav = document.querySelector('.main-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var isOpen = nav.classList.toggle('open-mobile');
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});
	}
	document.querySelectorAll('.main-nav li.has-children > a').forEach(function (link) {
		link.addEventListener('click', function (e) {
			if (window.innerWidth > 900) { return; }
			var parent = link.parentElement;
			if (!parent.classList.contains('open')) {
				e.preventDefault();
				document.querySelectorAll('.main-nav li.open').forEach(function (el) {
					if (el !== parent) { el.classList.remove('open'); }
				});
				parent.classList.add('open');
			}
		});
	});

	/* Karuzela kafelków na stronie głównej */
	var carousel = document.querySelector('.tile-carousel');
	if (carousel) {
		var tiles = Array.prototype.slice.call(carousel.querySelectorAll('.tile'));
		var current = tiles.findIndex(function (t) { return t.classList.contains('is-active'); });
		if (current === -1) { current = 0; tiles[0].classList.add('is-active'); }
		var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var timer = null;

		function show(index) {
			tiles[current].classList.remove('is-active');
			current = index;
			tiles[current].classList.add('is-active');
		}
		function next() { show((current + 1) % tiles.length); }
		function start() {
			if (reduceMotion || tiles.length < 2) { return; }
			stop();
			timer = window.setInterval(next, 3800);
		}
		function stop() { if (timer) { window.clearInterval(timer); timer = null; } }

		/* Kafelek reaguje na najechanie myszką / focus klawiaturą - podświetla się
		   od razu (tak jak aktywny w karuzeli) i wstrzymuje automatyczne obracanie. */
		tiles.forEach(function (tile, index) {
			tile.addEventListener('mouseenter', function () { stop(); show(index); });
			tile.addEventListener('focus', function () { stop(); show(index); });
		});
		carousel.addEventListener('mouseleave', start);
		carousel.addEventListener('focusout', start);
		start();
	}

	/* Formularz kontaktowy - wysyłka bez przeładowania strony */
	var form = document.querySelector('.contact-form');
	if (form) {
		var notice = document.getElementById('form-notice');
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var data = new FormData(form);
			var submitBtn = form.querySelector('button[type=submit]');
			submitBtn.disabled = true;
			fetch(form.getAttribute('action'), { method: 'POST', body: data, headers: { 'X-Requested-With': 'fetch' } })
				.then(function (r) { return r.json(); })
				.then(function (json) {
					notice.style.display = 'block';
					notice.className = 'form-notice ' + (json.ok ? 'success' : 'error');
					notice.textContent = json.message;
					if (json.ok) { form.reset(); }
				})
				.catch(function () {
					notice.style.display = 'block';
					notice.className = 'form-notice error';
					notice.textContent = 'Nie udało się wysłać wiadomości. Spróbuj ponownie później lub zadzwoń.';
				})
				.finally(function () { submitBtn.disabled = false; });
		});
	}
});
