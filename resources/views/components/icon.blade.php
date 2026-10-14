@props(['name', 'class' => 'size-5'])
@php
    $paths = [
        'check' => '<path d="M5 13l4 4L19 7"/>',
        'x' => '<path d="M6 18L18 6M6 6l12 12"/>',
        'arrow-right' => '<path d="M5 12h14M13 5l7 7-7 7"/>',
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'chevron-down' => '<path d="M19 9l-7 7-7-7"/>',
        'building' => '<path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1M9 13h1M9 17h1M14 9h1M14 13h1M14 17h1"/>',
        'store' => '<path d="M3 9l1.5-5h15L21 9M3 9v11h18V9M3 9h18M9 20v-6h6v6"/>',
        'code' => '<path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>',
        'briefcase' => '<path d="M3 7h18v13H3zM8 7V4h8v3M3 13h18"/>',
        'academic' => '<path d="M12 4L2 9l10 5 10-5-10-5zM6 11.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5"/>',
        'rocket' => '<path d="M5 15c-1.5 1.5-2 5-2 5s3.5-.5 5-2M14 4c3-1 6-1 6-1s0 3-1 6l-7 7-5-5 7-7zM9 10l-4 1-2 3 4 1M14 15l-1 4-3 2-1-4"/>',
        'search' => '<path d="M11 19a8 8 0 100-16 8 8 0 000 16zM21 21l-4.3-4.3"/>',
        'chat' => '<path d="M21 12a8 8 0 01-11.6 7.1L4 20l1-4.6A8 8 0 1121 12z"/>',
        'clock' => '<path d="M12 7v5l3 2M12 21a9 9 0 100-18 9 9 0 000 18z"/>',
        'mail' => '<path d="M4 6h16v12H4zM4 7l8 6 8-6"/>',
        'map' => '<path d="M12 21s-7-6.3-7-11a7 7 0 1114 0c0 4.7-7 11-7 11zM12 12a2 2 0 100-4 2 2 0 000 4z"/>',
        'sparkles' => '<path d="M12 3l1.8 4.7L18 9.5l-4.2 1.8L12 16l-1.8-4.7L6 9.5l4.2-1.8L12 3zM19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8L19 15z"/>',
        'shield' => '<path d="M12 3l8 3v6c0 4.5-3.4 8.3-8 9-4.6-.7-8-4.5-8-9V6l8-3z"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v5H5V6h5"/>',
    ];
@endphp
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] ?? $paths['sparkles'] !!}</svg>
