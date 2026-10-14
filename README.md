# Website Jasa Pembuatan Website & Digital Solutions

Marketing website + CMS built from the PRD (v1.0): service landing pages, transparent
pricing, portfolio, SEO blog, lead form, WhatsApp integration, conversion tracking,
and an admin dashboard. Laravel 13, Blade, Tailwind CSS 4, MySQL/MariaDB, with
animated [React Bits](https://reactbits.dev) components as progressive enhancement.

## Quick start

```bash
composer install
npm install && npm run build
cp .env.example .env && php artisan key:generate
# set DB_* in .env (or DB_CONNECTION=sqlite for local), then:
php artisan migrate --seed      # prints the admin password if ADMIN_PASSWORD is empty
php artisan storage:link
php artisan serve               # http://localhost:8000, CMS at /admin
```

Run the tests with `php artisan test` and check formatting with `vendor/bin/pint --test`.

## What is included (PRD §2.1 MVP)

| Area | Where |
|---|---|
| Landing page, services, student page | `/`, `/jasa-pembuatan-website*`, `/jasa-website-pelajar*` (CMS: Layanan) |
| Pricing (business + student) with comparison table | `/paket`, `/paket/{slug}` (CMS: Paket harga, features editable per row) |
| Portfolio / case studies | `/portofolio` (hidden from home & sitemap until a real project is published) |
| Blog with categories, scheduling, internal search (noindex) | `/blog` |
| Lead form with validation, honeypot, time-trap, rate limit, consent | `/kontak` → CMS: Leads (status pipeline, CSV export) |
| WhatsApp CTA with page/package specific message | `x-wa-button`, falls back to the contact form while no number is set |
| Conversion tracking (WhatsApp, package CTA, service views, leads, UTM) | `resources/js/tracking.js`, `/track`, Dashboard funnel |
| GA4 (consent bar) + Search Console verification | CMS: Pengaturan |
| SEO: per-item meta, canonical, OG, robots, JSON-LD, breadcrumbs, sitemap, robots.txt, 301 redirects, 404 | `App\Support\Seo`, `x-seo`, `SeoController`, `HandleRedirects` |
| Roles | `admin` (settings, users) and `editor` (content, leads) |

Content is written in Markdown; raw HTML is escaped so CMS content cannot inject scripts.
Changing the slug of published content, or deleting it, records a 301 redirect automatically.

## React Bits

Pages are fully rendered by Blade (good for SEO and no-JS visitors). Elements marked
`data-island="Name"` are enhanced by a React Bits component when they scroll into view —
see `resources/js/islands/index.jsx`. React and each component are code-split, so the
entry script is ~7 KB, and decorative motion is skipped for `prefers-reduced-motion`.

Components used (source copied into `resources/js/reactbits/`, as the shadcn registry
does): Aurora, BlurText, RotatingText, ShinyText, CountUp, SpotlightCard, LogoLoop.
To add more, run `npx shadcn@latest add @react-bits/<Name>-JS-TW` (registry configured
in `components.json`), then register the component in the islands registry.

## Deploying to shared hosting

1. Build assets locally (`npm run build`) and upload the project; point the domain's
   document root to `public/` (or move `public/` contents and adjust paths).
2. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://…` (HTTPS is forced
   when `APP_URL` starts with `https://`), DB and MAIL settings in `.env`.
3. `php artisan migrate --force --seed`, `php artisan storage:link`,
   `php artisan config:cache route:cache view:cache`.
4. In the CMS: set the WhatsApp number, contact email, GA4 ID and Search Console code,
   then submit `/sitemap.xml` in Search Console. Use the launch checklist on the dashboard.
5. Schedule database + `storage/app/public` backups and test a restore (PRD §12.14).

## Before launch

- Replace placeholder copy (brand name "Sugara Digital", About page) with real details.
- Have the privacy policy and terms reviewed (UU PDP No. 27/2022).
- Publish only real portfolio items and testimonials; the CMS asks for confirmation.
