# Beaver Motion Effects

Beaver Motion Effects is an early WordPress plugin skeleton for adding GSAP-powered motion effects to Beaver Builder rows, columns, and modules.

## Status

Version `0.1.0` is intentionally minimal. It includes the plugin bootstrap, core service classes, frontend asset registration, basic data-attribute animation handling, and placeholders for Beaver Builder settings integration.

## Requirements

- WordPress 6.0 or newer
- PHP 7.4 or newer
- Beaver Builder for the future settings/render integration
- No Composer install is required for v0.1

## Installation

1. Copy or upload the `beaver-motion-effects` folder to `wp-content/plugins/`.
2. In WordPress admin, go to **Plugins**.
3. Activate **Beaver Motion Effects**.
4. Add markup with supported `data-bme` attributes or wait for the upcoming Beaver Builder settings integration.

Example test markup:

```html
<div data-bme="motion" data-bme-effect="fade-up" data-bme-duration="0.8" data-bme-delay="0" data-bme-ease="power2.out" data-bme-once="true">
	Animated content
</div>
```

## What is included

- Valid WordPress plugin bootstrap file: `beaver-motion-effects.php`
- Namespaced PHP classes under `LDC\BeaverMotionEffects`
- `BME_` constants and bootstrap helper
- Core classes:
  - `Plugin`
  - `Assets`
  - `Settings`
  - `Render`
  - `Sanitizer`
- GSAP and ScrollTrigger registration
- Frontend script that detects `[data-bme="motion"]` elements and runs simple scroll-triggered animations
- Basic security practices, including `ABSPATH` checks, escaping, and sanitization helpers

## Notes for future development

- Register Beaver Builder settings for rows, columns, and modules.
- Save and sanitize motion settings from Beaver Builder forms.
- Inject generated `data-bme` attributes into Beaver Builder frontend markup.
- Add automated tests and build tooling when the plugin grows beyond the v0.1 skeleton.

## Not included in v0.1

- Licensing
- Admin pages
- Premium features
- Composer requirements
- JavaScript build tooling
