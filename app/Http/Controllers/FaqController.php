<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\Seo;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __invoke(): View
    {
        $faqs = Faq::query()->published()->get()->groupBy('group');

        $seo = Seo::make('FAQ: Pertanyaan Seputar Jasa Pembuatan Website')
            ->description('Jawaban tentang harga, domain, hosting, maintenance, kepemilikan source code, revisi, dan layanan untuk pelajar.')
            ->canonical(route('faq'))
            ->breadcrumbs(['FAQ' => route('faq')]);

        $seo->schema([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->flatten()->map(fn (Faq $faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
            ])->values()->all(),
        ]);

        return view('faq', ['groups' => $faqs, 'seo' => $seo]);
    }
}
