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

- Clone theme
```
git clone <your-repo-url> ProEvent
cd ProEvent
```
> This repo contains only the theme, not full WordPress.
