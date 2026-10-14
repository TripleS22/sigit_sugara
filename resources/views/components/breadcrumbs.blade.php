@props(['items' => [], 'dark' => false])
@if (count($items) > 1)
    <nav aria-label="Breadcrumb" {{ $attributes }}>
        <ol class="flex flex-wrap items-center gap-1.5 text-sm {{ $dark ? 'text-white/70' : 'text-slate-500' }}">
            @foreach ($items as $crumb)
                <li class="flex items-center gap-1.5">
                    @if (! $loop->last)
                        <a href="{{ $crumb['url'] }}" class="{{ $dark ? 'hover:text-white' : 'hover:text-brand-700' }}">{{ $crumb['name'] }}</a>
                        <span aria-hidden="true">/</span>
                    @else
                        <span aria-current="page" class="{{ $dark ? 'text-white' : 'text-slate-800' }} line-clamp-1">{{ $crumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
