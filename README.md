# ProEvent Theme

Custom WordPress event theme built from scratch for the MVP workflow.
Includes Gutenberg blocks, Tailwind, REST API, and optional dev tooling.

---


## Folder Structure
```
ProEvent/
├── assets/
│   ├── css/
│   │   ├── tailwind.css     (source)
│   │   └── main.css         (generated)
│   ├── js/
│   │   └── blocks.js        (Gutenberg blocks)
├── template files...
├── docker-compose.yml
├── .github/workflows/ci.yml
├── .storybook/
├── stories/
├── package.json
├── tailwind.config.js
├── postcss.config.js
├── README.md
└── functions.php
```

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

### Only theme-level logic 

No external plugins or frameworks.
Gutenberg blocks use WP’s global React runtime — no JSX, no Webpack.

### Dynamic brand color (CSS variable)

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


### WebP image preference

The theme swaps WordPress image URLs to `.webp ` when a sibling exists:

```
my-photo.jpg  
my-photo.webp  ← served instead
```

Uploads work exactly the same — just upload WebP for the best results.

### Lazy-loading everywhere

Images rendered via:

- `the_post_thumbnail()`
- `wp_get_attachment_image()`
- get `loading="lazy"` forced via a filter.


### Gutenberg blocks written without a build step

Blocks are written in plain JS via:

- `wp.blocks`
- `wp.element.createElement`
- `wp.blockEditor`
- `wp.components`

This keeps the theme zero-build and keeps all block logic editable inside `assets/js/blocks.js`

### REST Endpoint is theme-owned

`/wp-json/proevent/v1/next`
5 nearest events by date.
Useful for widgets, mobile apps, or external usage.


### Speculative loading
Theme adds a tiny Speculation Rules script:

- Pre-renders event pages likely to be clicked
- Zero impact on unsupported browsers
- Noticeable speed-up between homepage → event detail

---

## Gutenberg Blocks

1. Hero with CTA

    - Title
    - Text
    - CTA label + URL
    - Optional background image
    - Optional dark overlay
    - Uses brand color for CTA button

2. Event Grid

    - Limit
    - Category filter
    - Sort mode (Upcoming / Recent)
    - Dynamically rendered via PHP
    - Uses event CPT + meta fields

Add these via block inserter → “ProEvent” category.

---

## REST API

### Get upcoming events
`GET /wp-json/proevent/v1/next`

Response example:
```
[
  {
    "id": 43,
    "title": "Design Meetup 2025",
    "permalink": "https://example.test/event/design-meetup-2025/",
    "date": "2025-03-12",
    "time": "18:00",
    "location": "Main Hall",
    "registerUrl": "https://example.test/register",
    "excerpt": "Short event description..."
  }
]
```

## Docker Setup (Theme-Only Repo)

- WordPress core installed inside a Docker volume
- Your theme folder mounted into `/wp-content/themes/ProEvent`
- Database via MySQL 5.7
- Accessible at `http://localhost:8080`

Bring it up:
`docker compose up -d`

Shut down:
`docker compose down`


## GitHub Actions (CI)

Located in:
`.github/workflows/ci.yml`

Runs on push/PR:

- `npm install`
- `npm run build` (Tailwind)
- PHP 8.1 with PHPCS (WordPress standards)

Keeps your theme clean + buildable.


## Storybook (Pattern Library)

Install dependencies:
`npm install`

Run Storybook:
`npm run storybook`

Visit:
`http://localhost:6006`


Useful for documenting UI patterns such as:

- Event Card
- Hero CTA layout
- Buttons / color system

> Generated output (storybook-static/) is ignored in git.


## Development Notes
- All templates use semantic HTML and Tailwind utilities
- Front-end intentionally lightweight—no animation libs, no heavy JS
- Lazy-loaded images + WebP = good Lighthouse score
- “Speculative loading” gives near-instant navigation on supported browsers
- Gutenberg blocks maintain zero-build simplicity (no Babel/Webpack)
