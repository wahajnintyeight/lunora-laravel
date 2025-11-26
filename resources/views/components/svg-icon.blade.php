@props([
    'name',
    'class' => 'w-5 h-5',
    'strokeWidth' => 2,
])

@php
    $icons = [
        'search' => '<path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />',
        'filter' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />',
        'close' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />',
        'chevron-right' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />',
        'chevron-left' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />',
        'arrow-left' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 15l-6-6m0 0l6-6m-6 6h18" />',
        'category' => '<path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />',
        'shopping-cart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
        // Contact page icons
        'map-pin' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3" stroke-linecap="round" stroke-linejoin="round"/>',
        'phone' => '<path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'mail' => '<rect width="20" height="16" x="2" y="4" rx="2" stroke-linecap="round" stroke-linejoin="round"/><path stroke-linecap="round" stroke-linejoin="round" d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
        'clock' => '<circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>',
        // Social media icons
        'facebook' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
        'instagram' => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5" stroke-linecap="round" stroke-linejoin="round"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5" stroke-linecap="round" stroke-linejoin="round"/>',
        'twitter' => '<path stroke-linecap="round" stroke-linejoin="round" d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>',
        'pinterest' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12c0 4.42 2.75 8.2 6.64 9.7-.09-.84-.17-2.13.03-3.04l1.56-6.6s-.39-.78-.39-1.94c0-1.81 1.05-3.17 2.36-3.17 1.11 0 1.65.83 1.65 1.83 0 1.12-.71 2.8-1.08 4.35-.31 1.31.66 2.38 1.95 2.38 2.34 0 4.14-2.47 4.14-6.04 0-3.16-2.28-5.37-5.54-5.37-3.77 0-5.99 2.83-5.99 5.75 0 1.12.43 2.32.97 2.98.11.13.12.25.09.38l-.4 1.55c-.04.18-.14.22-.33.13-1.24-.58-2.02-2.4-2.02-3.86 0-3.15 2.29-6.04 6.6-6.04 3.47 0 6.16 2.52 6.16 5.88 0 3.45-2.17 6.22-5.29 6.22-1.03 0-2.01-.54-2.34-1.28l-.64 2.44c-.23.9-.85 2.03-1.27 2.72C8.5 21.29 10.17 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/>',
    ];
    
    $iconPath = $icons[$name] ?? '';
    $viewBox = in_array($name, ['search', 'category']) ? '0 0 20 20' : '0 0 24 24';
    $fill = in_array($name, ['search', 'category']) ? 'currentColor' : 'none';
@endphp

<svg 
    class="{{ $class }} flex-shrink-0" 
    fill="{{ $fill }}" 
    stroke="currentColor" 
    viewBox="{{ $viewBox }}" 
    stroke-width="{{ $strokeWidth }}"
    aria-hidden="true"
    {{ $attributes }}>
    {!! $iconPath !!}
</svg>

