# Changelog

All notable changes to Anchor Theme are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and
this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.4] — 2026-08-24

### Added

- Theme toggle: click still switches light and dark only. Right-click
  opens System / Light / Dark, and the button shows a half-filled
  circle while following the OS. Preference is stored in the same
  `captaincore-theme` key the dashboard uses, so a pick in /account/
  is the pick on the marketing site (and the other way around).

### Fixed

- Gravity Forms 3.0 renders the submit as a `<button>` element instead
  of `<input type="submit">`, which left it browser-default styled.
  The global form-button rules now also target `.gform_button` by
  class, so both markups get the designed button.

## [1.0.3] — 2026-08-23

### Changed

- Homepage fleet preview uses Austin's own sites instead of fictional
  customer domains. Visits are last-7-day Fathom totals (refreshed in the
  background). Open site (and the domain name) open the live URL in a new tab.
  Core / theme / plugin meta is staged for the filter demo (five on the
  latest core, one a version behind) so + Filter stays a clean product
  preview rather than a dump of live installs. A window-image SVG sits
  under every screenshot thumb so a missing or 404 image never shows the
  browser's broken-image glyph. Pinning matches the console: a chip strip
  (pin icon, name pill, ✕) above the table instead of a 📌 after the domain.
  The Terminal tab is the Activity dock: idle prompt, @ Select target over
  the preview sites, Cookbook recipes that fill the input, and Run that
  streams against the chosen environments (no fake --sites=all). Target and
  cookbook pickers are position:fixed so they are not clipped by the
  console card. dismissed.fyi was swapped for wpregistry.io. Network
  status (footer and command palette) now points at
  https://status.anchor.host.

## [1.0.2] — 2026-08-22

### Added

- Brand page: social-avatar download group — the mark padded for circular
  crops (white, navy and transparent variants).

## [1.0.1] — 2026-08-22

### Changed

- Contact form: Name + Email pair up on one row via Gravity Forms ready
  classes (`gf_left_half` / `gf_right_half`); all legacy-markup fields now
  fill their container instead of GF's partial size widths.

## [1.0.0] — 2026-08-22

Initial release.

### Added

- Hosting plan calculator layout (`calculator`): pick a base plan, stack
  add-on steppers, live receipt with billing-cycle switching. Pricing stays
  sourced from `anchor_plans()` / `anchor_addon_rates()`.
- Private brand page layout (`brand`): logo previews, downloadable asset kit
  (marks, tile, outlined lockups, zip), palette documentation, type specimens
  and usage rules.
- WooCommerce theme support; Woo pages render through the default prose
  layout.

- Light/dark design token system with an explicit toggle that beats the OS
  setting, applied before first paint so there is no flash.
- Gravity Forms (legacy markup) styling mapped to the theme tokens — inputs,
  labels, radios, sections, validation states and the submit button follow
  the light/dark scheme on the subscribe and contact forms.
- Security documentation layout (`security-docs`): the full defense-in-depth
  reference as card sections plus alerting and schedule tables, kept in
  `anchor_security_docs()`. The marketing security page links to it.
- Command palette (`⌘K` / `/`) with static navigation, dashboard preview,
  account link, snippet and action commands, plus live post/page search over
  the `anchor/v1/palette` REST route.
- Front page: hero with the "Needs attention" dashboard panel in its all-clear
  state plus a "Handled for you" activity feed (mirroring the CaptainCore v3
  home screen — hands-off means the to-do list reads zero), headline stat
  band, the three arrangement cards, a tabbed dashboard preview console
  (fleet / security / terminal), infrastructure partners, quotes and a closing
  call to action.
- Homepage FAQ accordion (native `details`/`summary`, no JS) with FAQPage
  JSON-LD. Copy lives in `anchor_faq()`: email (Fastmail), who answers,
  infrastructure, free migrations, what's included, agencies vs one site,
  the bus factor plan, leaving with your sites, updates and backups, pricing.
- Plans page: pricing cards with a monthly/quarterly/yearly switch, a
  "size it yourself" calculator that picks the cheapest covering plan, add-on
  rates and the plan-includes grid.
- Blog: featured latest post, card grid, pagination and a subscribe action.
- Single post: centred header, hero image, prose column, author box and a
  category-matched "Keep reading" section.
- About, Security and Contact page layouts, each still rendering editor content.
- Page layout picker in the block editor sidebar, with slug-based fallback.
- Filterable content model in `inc/content.php` for every piece of copy.
- Editor styles mirroring the front-end prose column.
- `--ab-*` token bridge so Anchor Blocks follows the theme's colour scheme.
- Self-updater reading `manifest.json` from the GitHub repository.
