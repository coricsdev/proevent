# ProEvent Theme

Custom WordPress event theme built from scratch for the MVP workflow.
Includes Gutenberg blocks, Tailwind, REST API, and optional dev tooling.

---

##  Features Overview

**Core CMS**
 - Custom Post Type: `event`
 - Taxonomy: `event-category`
 - Custom meta fields: date, time, location, registration URL
 - Company Settings page: logo, brand color, social links
 - Brand color exported as `--proevent-brand` CSS variable

 **Front-end**
- Tailwind CSS (CLI build, no CDN)
- Clean responsive layout from 320px up
- Grid + single template for Events
- Automatic WebP preference + lazy-loading for images
- Speculative loading (Speculation Rules API)

**Gutenberg Blocks**
- Hero with CTA (title, text, CTA button, background image + dark overlay)
- Event Grid Block (limit, sort, category filter — dynamic PHP render)

**REST API**
- `/wp-json/proevent/v1/next` - Returns the 5 nearest upcoming events as JSON.

**Dev Tooling**
- Tailwind CLI build
- Docker Compose local WP environment
- GitHub Actions CI (Tailwind build + PHPCS)
- Storybook pattern library
 
---

## Getting Started

1. Clone theme
    ```
    git clone https://github.com/coricsdev/proevent.git
    cd ProEvent
    ```
    > This repo contains only the theme, not full WordPress.

2. Start WordPress via Docker

    This project includes a minimal Docker setup for local development.
    ```
    docker compose up -d
    ```
    Open: `http://localhost:8080`

    Complete WordPress install → Activate ProEvent under Appearance → Themes.

3. Install Node packages

    Tailwind + Storybook live here:
    `npm install`

4. Tailwind build commands

    Regular build:
    `npm run build`

    Watch mode (during development):
    `npm run watch`

    Source file → `assets/css/tailwind.css`

    Output file → `assets/css/main.css`

    Note: `main.css` is ignored in git, it’s generated.

---

## Architectural Decisions

### Only theme-level logic ###

No external plugins or frameworks.
Gutenberg blocks use WP’s global React runtime — no JSX, no Webpack.

### Dynamic brand color (CSS variable) ###

The Company Settings page stores a brand color.
The theme injects:
```
:root {
  --proevent-brand: #xxxxxx;
}
```

Tailwind maps this to:
```
colors: {
  primary: "var(--proevent-brand)"
}

```

Utility classes like `bg-primary` and `text-primary` reflect the saved admin color instantly.


### WebP image preference ###

The theme swaps WordPress image URLs to `.webp ` when a sibling exists:

```
my-photo.jpg  
my-photo.webp  ← served instead
```

Uploads work exactly the same — just upload WebP for the best results.

### Lazy-loading everywhere ###

Images rendered via:

- `the_post_thumbnail()`
- `wp_get_attachment_image()`
- get `loading="lazy"` forced via a filter.


### Gutenberg blocks written without a build step ###

Blocks are written in plain JS via:

- `wp.blocks`
- `wp.element.createElement`
- `wp.blockEditor`
- `wp.components`

This keeps the theme zero-build and keeps all block logic editable inside `assets/js/blocks.js`