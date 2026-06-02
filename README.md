# Beaver Motion Effects

Beaver Motion Effects is an early WordPress plugin skeleton for adding GSAP-powered motion effects to Beaver Builder rows, columns, and modules.

## Status

Version `0.1.0` is intentionally minimal. It includes the plugin bootstrap, core service classes, frontend asset registration, Beaver Builder editor controls, basic data-attribute animation handling, and safe defaults.

## Requirements

- WordPress 6.0 or newer
- PHP 7.4 or newer
- Beaver Builder
- No Composer install is required for v0.1

## Installation

1. Copy or upload the `beaver-motion-effects` folder to `wp-content/plugins/`.
2. In WordPress admin, go to **Plugins**.
3. Activate **Beaver Motion Effects**.
4. Open a page with Beaver Builder and add motion effects from the row, column, or module settings panel.

## How to add effects in Beaver Builder

1. Open the page in **Beaver Builder**.
2. Open the settings for a **row**, **column**, or **module**.
3. Go to the **Motion** tab.
4. Set **Enable motion effect** to **Yes**.
5. Choose an **Effect** such as Fade Up, Fade In, Slide Left, Slide Right, or Zoom In.
6. Adjust **Duration**, **Delay**, **Easing**, and **Animate once** as needed.
7. Save the element and publish the Beaver Builder layout.

The plugin writes safe `data-bme` attributes to the selected Beaver Builder element on the front end. The frontend script detects those attributes and runs the GSAP animation when the element scrolls into view.

## Example generated markup

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
- Beaver Builder Motion tab for rows, columns, and modules
- GSAP and ScrollTrigger registration
- Frontend script that detects `[data-bme="motion"]` elements and runs simple scroll-triggered animations
- Basic security practices, including `ABSPATH` checks, escaping, and sanitization helpers

## Notes for future development

- Add richer responsive controls and preview behavior.
- Add automated tests and build tooling when the plugin grows beyond the v0.1 skeleton.
- Consider local GSAP assets or dependency options for production distribution.

## Not included in v0.1

- Licensing
- Admin pages
- Premium features
- Composer requirements
- JavaScript build tooling
