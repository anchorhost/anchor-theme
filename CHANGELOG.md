# Changelog

All notable changes to Anchor Theme are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and
this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.0] — Unreleased

Initial release.

### Added

- Light/dark design token system with an explicit toggle that beats the OS
  setting, applied before first paint so there is no flash.
- Command palette (`⌘K` / `/`) with static navigation, dashboard preview,
  account link, snippet and action commands, plus live post/page search over
  the `anchor/v1/palette` REST route.
- Front page: hero with the "Needs attention" dashboard panel, headline stat
  band, the three arrangement cards, a tabbed dashboard preview console
  (fleet / security / terminal), infrastructure partners, quotes and a closing
  call to action.
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
