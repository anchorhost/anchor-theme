# Changelog

All notable changes to Anchor Theme are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and
this project adheres to [Semantic Versioning](https://semver.org/).

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
