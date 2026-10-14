# Project notes

Marketing site + CMS for a web-development agency (PRD in Indonesian). Laravel 13, Blade,
Tailwind 4, MySQL in production (SQLite works locally/tests). UI text is Indonesian.

- Public routes: `routes/web.php`. `/{slug}` (SlugController) resolves Services, then Pages;
  keep it last. Reserved root slugs live in `Admin\Concerns\ManagesContent::RESERVED_SLUGS`.
- Content models share SEO columns (`App\Support\SeoColumns`) and traits in
  `app/Models/Concerns`: `HasSeo` (metadata), `HasPublicUrl` (auto 301 on slug change/delete),
  `FlushesSiteCache` (invalidates `App\Support\SiteCache`).
- Cached models must be allowlisted in `config/cache.php` `serializable_classes`.
- Settings (contact, hero copy, GA4…) are key/value rows: `App\Models\Setting::DEFAULTS`.
- CMS text is Markdown rendered by `App\Support\Markdown` (HTML escaped).
- React Bits components are islands: Blade renders the fallback, `resources/js/islands`
  mounts the component. Pass props with `data-props="{{ json_encode([...]) }}"` (not `@json`).
- Never seed fake portfolio items, testimonials, ratings, or contact details.
- Checks: `php artisan test`, `vendor/bin/pint --test`, `npm run build`.
