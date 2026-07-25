# Anchor Theme

The marketing and blog theme behind [anchor.host](https://anchor.host). A classic
(PHP template) theme built from a Claude Design source, with light/dark design
tokens, a command palette, a live plan calculator and a dashboard preview
console.

## Requirements

- WordPress 6.0+
- PHP 8.0+

## Structure

```
anchor-theme/
├── app/Updater.php              self-updater (reads manifest.json from GitHub)
├── assets/
│   ├── css/theme.css            the whole design system
│   ├── css/editor.css           block editor mirror of the prose column
│   ├── icons/*.svg              masked illustrations + partner logos
│   └── js/
│       ├── app.js               theme toggle, mobile nav, console tabs, palette
│       ├── calculator.js        plans page pricing + sliders
│       └── editor-layout.js     page layout picker in the editor sidebar
├── inc/
│   ├── setup.php                theme supports, menus, image sizes, page meta
│   ├── enqueue.php              assets + the no-FOUC colour scheme script
│   ├── content.php              every piece of marketing copy, filterable
│   ├── template-tags.php        icons, meta, money, quote(), nav helpers
│   ├── nav-walker.php           flat pill nav
│   ├── palette.php              static commands + /anchor/v1/palette search
│   └── blocks.php               editor integration + Anchor Blocks token bridge
├── template-parts/
│   ├── home/                    hero, statband, arrangement, console, infra, quotes, cta
│   ├── content/                 featured, card, related
│   ├── pages/                   plans, about, security, contact, default
│   └── palette.php              command palette shell
└── *.php                        front-page, home, single, page, archive, search, 404
```

## Design tokens

Everything is driven by CSS custom properties defined three times in
`assets/css/theme.css`: once on `:root` (light), once inside
`@media (prefers-color-scheme: dark)`, and once each on `html[data-theme="light"]`
and `html[data-theme="dark"]` so an explicit choice always beats the OS setting.

The saved choice is applied by an inline script in `<head>` (see
`inc/enqueue.php`) so the page never renders in the wrong scheme and flips.

## Content model

All marketing copy lives in `inc/content.php` as filterable arrays. Change copy
there, or override from a plugin:

```php
add_filter( 'anchor_stats', function ( $stats ) {
	$stats[0]['value'] = '3,000+';
	return $stats;
} );
```

Pages pick their layout from the `_anchor_page_layout` post meta (set in the
editor sidebar under "Anchor layout"), falling back to matching by slug —
`plans`, `about`, `security` and `contact` work with no configuration.

## Page layouts

| Layout | What it renders |
|---|---|
| `default` | Title + editor content in the prose column |
| `plans` | Pricing cards, billing cycle switch, calculator, add-ons |
| `about` | Split hero with the captain illustration + info cards |
| `security` | Split hero with the lighthouse + the six security cards |
| `contact` | Details column + form (editor content, or the designed fallback) |

Each of `about`, `security`, `plans` and `contact` renders the page's editor
content too, so a layout never locks copy away from the editor.

## Anchor Blocks

The companion [Anchor Blocks](https://github.com/anchorhost/anchor-blocks)
plugin styles its blocks against its own `--ab-*` variables. `inc/blocks.php`
maps those onto the theme tokens so the blocks follow the light/dark scheme
without the plugin needing to know about the theme.

## Commands

- `⌘K` / `Ctrl+K` — toggle the command palette
- `/` — open the palette
- `↑ ↓` — move, `↵` — run, `esc` — close

Static commands are defined in `inc/palette.php`; post and page results come
live from the `anchor/v1/palette` REST route.

## License

MIT © Austin Ginder
