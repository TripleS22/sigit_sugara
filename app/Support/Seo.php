<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Str;

/**
 * Metadata for one public page: title, description, canonical, Open Graph,
 * robots directives, breadcrumbs and JSON-LD blocks. Rendered by <x-seo>.
 */
class Seo
{
    public ?string $description = null;

    public ?string $canonical = null;

    public ?string $ogTitle = null;

    public ?string $ogDescription = null;

    public ?string $image = null;

    public string $type = 'website';

    public bool $index = true;

    public bool $follow = true;

    /** @var array<int, array{name: string, url: string}> */
    public array $breadcrumbs = [];

    /** @var array<int, array<string, mixed>> */
    public array $schemas = [];

    public function __construct(public string $title = '') {}

    public static function make(string $title, ?string $description = null): self
    {
        $seo = new self($title);
        $seo->description = $description;

        return $seo;
    }

    public function description(?string $description): self
    {
        $this->description = $description ? Str::limit(trim($description), 160) : $this->description;

        return $this;
    }

    public function canonical(?string $url): self
    {
        $this->canonical = $url;

        return $this;
    }

    public function image(?string $path): self
    {
        if ($path) {
            $this->image = Str::startsWith($path, ['http://', 'https://']) ? $path : asset('storage/'.ltrim($path, '/'));
        }

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function noindex(bool $follow = true): self
    {
        $this->index = false;
        $this->follow = $follow;

        return $this;
    }

    /** @param  array<string, string>  $trail  label => url, excluding "Beranda" */
    public function breadcrumbs(array $trail): self
    {
        $this->breadcrumbs = [['name' => 'Beranda', 'url' => url('/')]];
        foreach ($trail as $name => $url) {
            $this->breadcrumbs[] = ['name' => $name, 'url' => $url];
        }

        return $this;
    }

    /** @param  array<string, mixed>  $schema */
    public function schema(array $schema): self
    {
        $this->schemas[] = $schema;

        return $this;
    }

    public function fullTitle(): string
    {
        $site = Setting::get('site_name');

        if ($this->title === '' || $this->title === $site) {
            return $site;
        }

        return Str::contains($this->title, $site) ? $this->title : $this->title.' | '.$site;
    }

    public function canonicalUrl(): string
    {
        return $this->canonical ?: url()->current();
    }

    public function robots(): string
    {
        return ($this->index ? 'index' : 'noindex').','.($this->follow ? 'follow' : 'nofollow');
    }

    /** @return array<int, array<string, mixed>> */
    public function jsonLd(): array
    {
        $blocks = [$this->organization()];

        if (count($this->breadcrumbs) > 1) {
            $blocks[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($this->breadcrumbs)->values()->map(fn ($crumb, $i) => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $crumb['name'],
                    'item' => $crumb['url'],
                ])->all(),
            ];
        }

        return array_merge($blocks, $this->schemas);
    }

    /** Organization data comes only from real settings; empty fields are omitted. */
    public static function organization(): array
    {
        $sameAs = array_values(array_filter([
            Setting::get('instagram_url'),
            Setting::get('linkedin_url'),
            Setting::get('facebook_url'),
        ]));

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => Setting::get('site_name'),
            'url' => url('/'),
            'logo' => Setting::get('logo') ? asset('storage/'.Setting::get('logo')) : null,
            'email' => Setting::get('contact_email') ?: null,
            'telephone' => Setting::get('whatsapp_number') ? '+'.Setting::get('whatsapp_number') : null,
            'address' => Setting::get('address') ? [
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('address'),
                'addressCountry' => 'ID',
            ] : null,
            'sameAs' => $sameAs ?: null,
        ]);
    }
}
