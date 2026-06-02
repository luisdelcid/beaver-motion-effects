(function (window, document) {
	'use strict';

	var gsap = window.gsap;
	var ScrollTrigger = window.ScrollTrigger;
	var config = window.BMEFrontend || {};

	if (!gsap || !ScrollTrigger) {
		return;
	}

	gsap.registerPlugin(ScrollTrigger);

	function toFloat(value, fallback) {
		var parsed = parseFloat(value);
		return Number.isFinite(parsed) ? parsed : fallback;
	}

	function getStartVars(effect) {
		switch (effect) {
			case 'fade-in':
				return { opacity: 0 };
			case 'slide-left':
				return { opacity: 0, x: 50 };
			case 'slide-right':
				return { opacity: 0, x: -50 };
			case 'zoom-in':
				return { opacity: 0, scale: 0.92 };
			case 'fade-up':
			default:
				return { opacity: 0, y: 30 };
		}
	}

	function animateElement(element) {
		if (element.dataset.bmeInitialized === 'true') {
			return;
		}

		var effect = element.getAttribute('data-bme-effect') || 'fade-up';
		var duration = toFloat(element.getAttribute('data-bme-duration'), 0.8);
		var delay = toFloat(element.getAttribute('data-bme-delay'), 0);
		var ease = element.getAttribute('data-bme-ease') || 'power2.out';
		var once = element.getAttribute('data-bme-once') !== 'false';
		var fromVars = getStartVars(effect);

		element.dataset.bmeInitialized = 'true';

		gsap.fromTo(
			element,
			fromVars,
			{
				duration: duration,
				delay: delay,
				ease: ease,
				opacity: 1,
				x: 0,
				y: 0,
				scale: 1,
				scrollTrigger: {
					trigger: element,
					start: 'top 85%',
					once: once
				}
			}
		);
	}

	function init() {
		var elements = document.querySelectorAll('[data-bme="motion"]');

		if (config.isDebug && window.console) {
			window.console.info('Beaver Motion Effects initialized', elements.length);
		}

		elements.forEach(animateElement);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})(window, document);
