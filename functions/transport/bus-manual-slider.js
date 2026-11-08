(function () {
	'use strict';

	function init() {
		document.querySelectorAll('[data-yeg-bus-manual]').forEach(function (manual) {
			var track = manual.querySelector('[data-manual-track]');
			if (!track) return;

			var previous = manual.querySelector('[data-manual-prev]');
			var next = manual.querySelector('[data-manual-next]');
			var counter = manual.querySelector('[data-manual-counter]');
			var total = track.children.length;
			var current = 0;
			var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

			function update() {
				if (!track.clientWidth) return;
				current = Math.max(0, Math.min(total - 1, Math.round(track.scrollLeft / track.clientWidth)));
				var label = (current + 1) + ' / ' + total;
				if (counter.textContent !== label) counter.textContent = label;
				previous.disabled = current === 0;
				next.disabled = current === total - 1;
			}

			function move(index, instant) {
				index = Math.max(0, Math.min(total - 1, index));
				track.scrollTo({
					left: index * track.clientWidth,
					behavior: instant || reducedMotion.matches ? 'auto' : 'smooth'
				});
			}

			previous.addEventListener('click', function () { move(current - 1); });
			next.addEventListener('click', function () { move(current + 1); });
			track.addEventListener('scroll', update, { passive: true });
			track.addEventListener('keydown', function (event) {
				var targets = { ArrowLeft: current - 1, ArrowRight: current + 1, Home: 0, End: total - 1 };
				if (Object.prototype.hasOwnProperty.call(targets, event.key)) {
					event.preventDefault();
					move(targets[event.key]);
				}
			});
			window.addEventListener('resize', function () { move(current, true); });
			manual.querySelector('[data-manual-controls]').hidden = false;
			update();
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());
