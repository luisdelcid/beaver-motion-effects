(function (window, document) {
	'use strict';

	var gsap = window.gsap;
	var ScrollTrigger = window.ScrollTrigger;
	var config = window.BMEFrontend || {};
	var mobileQuery = window.matchMedia ? window.matchMedia('(max-width: 767px)') : null;
	var reducedMotionQuery = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;

	if (!gsap || !ScrollTrigger) {
		return;
	}

	gsap.registerPlugin(ScrollTrigger);

	function toFloat(value, fallback) {
		var parsed = parseFloat(value);
		return Number.isFinite(parsed) ? parsed : fallback;
	}

	function isYes(value) {
		value = String(value || '').toLowerCase();
		return value === 'yes' || value === 'true' || value === '1' || value === 'on';
	}

	function getPreset(element) {
		var preset = element.getAttribute('data-bme') || element.getAttribute('data-bme-effect') || 'fade-up';

		// Support the v0.1 placeholder format: data-bme="motion" plus data-bme-effect.
		if (preset === 'motion') {
			preset = element.getAttribute('data-bme-effect') || 'fade-up';
		}

		return preset;
	}

	function getFromVars(preset) {
		switch (preset) {
			case 'fade-down':
				return { opacity: 0, y: -40 };
			case 'fade-left':
				return { opacity: 0, x: 40 };
			case 'fade-right':
				return { opacity: 0, x: -40 };
			case 'scale-in':
				return { opacity: 0, scale: 0.92 };
			case 'blur-in':
				return { opacity: 0, filter: 'blur(12px)' };
			case 'parallax':
				return { y: 60 };
			case 'fade-up':
			default:
				return { opacity: 0, y: 40 };
		}
	}

	function getToVars(preset) {
		var vars = {
			opacity: 1,
			x: 0,
			y: 0,
			scale: 1
		};

		if (preset === 'blur-in') {
			vars.filter = 'blur(0px)';
		}

		return vars;
	}

	function shouldSkip(element) {
		if (config.isBuilderActive || document.body.classList.contains('fl-builder-edit')) {
			return true;
		}

		if (reducedMotionQuery && reducedMotionQuery.matches) {
			return true;
		}

		return isYes(element.getAttribute('data-bme-disable-mobile')) && mobileQuery && mobileQuery.matches;
	}

	function revealElement(element) {
		gsap.set(element, { clearProps: 'opacity,transform,filter' });
		element.dataset.bmeInitialized = 'skipped';
	}

	function animateElement(element) {
		if (element.dataset.bmeInitialized) {
			return;
		}

		if (shouldSkip(element)) {
			revealElement(element);
			return;
		}

		var preset = getPreset(element);
		var duration = toFloat(element.getAttribute('data-bme-duration'), 0.8);
		var delay = toFloat(element.getAttribute('data-bme-delay'), 0);
		var ease = element.getAttribute('data-bme-ease') || 'power2.out';
		var start = element.getAttribute('data-bme-start') || 'top 85%';
		var onceAttr = element.getAttribute('data-bme-once');
		var once = onceAttr === null ? true : isYes(onceAttr);
		var fromVars = getFromVars(preset);
		var toVars = getToVars(preset);

		element.dataset.bmeInitialized = 'true';

		toVars.duration = duration;
		toVars.delay = delay;
		toVars.ease = ease === 'none' ? 'none' : ease;
		toVars.scrollTrigger = {
			trigger: element,
			start: start,
			once: once
		};

		if (preset === 'parallax') {
			toVars.scrollTrigger.scrub = once ? false : true;
		}

		gsap.fromTo(element, fromVars, toVars);
	}

	function init() {
		var elements = document.querySelectorAll('[data-bme]');

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
