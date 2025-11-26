@props([
    'variant' => 'primary', // primary, secondary, outline, ghost, danger, success, gold
    'size' => 'md', // sm, md, lg
    'type' => 'button', // button, submit, reset
    'href' => null, // If provided, renders as <a> tag
    'icon' => null, // Icon name from svg-icon component
    'iconPosition' => 'left', // left, right
    'fullWidth' => false,
    'loading' => false,
    'disabled' => false,
    'onclick' => null, // For onclick handlers
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    // Size classes
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm min-h-[36px] rounded-lg',
        'md' => 'px-4 py-2.5 text-base min-h-[44px] rounded-lg',
        'lg' => 'px-6 py-3.5 text-lg min-h-[52px] rounded-xl',
    ];
    
    // Variant classes
    $variantClasses = [
        'primary' => 'bg-gradient-to-r from-[#881337] to-[#78350f] text-white hover:from-[#9f1239] hover:to-[#92400e] focus:ring-[#f59e0b] shadow-lg hover:shadow-xl',
        'secondary' => 'bg-[#f1f5f9] text-[#334155] hover:bg-[#e2e8f0] focus:ring-[#64748b] border border-[#e2e8f0] hover:border-[#cbd5e1]',
        'outline' => 'bg-transparent text-[#881337] border-2 border-[#881337] hover:bg-[#881337] hover:text-white focus:ring-[#881337]',
        'ghost' => 'bg-transparent text-[#334155] hover:bg-[#f1f5f9] focus:ring-[#64748b]',
        'danger' => 'bg-[#dc2626] text-white hover:bg-[#b91c1c] focus:ring-[#dc2626] shadow-lg hover:shadow-xl',
        'success' => 'bg-[#059669] text-white hover:bg-[#047857] focus:ring-[#059669] shadow-lg hover:shadow-xl',
        'gold' => 'bg-[#f59e0b] text-white hover:bg-[#d97706] focus:ring-[#f59e0b] shadow-lg hover:shadow-xl',
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
    
    if ($fullWidth) {
        $classes .= ' w-full';
    }
    
    if ($loading) {
        $classes .= ' cursor-wait';
    }
    
    $tag = $href ? 'a' : 'button';
    $attributes = $attributes->merge([
        'class' => $classes,
        'disabled' => $disabled || $loading,
    ]);
    
    if ($href) {
        $attributes = $attributes->merge(['href' => $href]);
    } else {
        $attributes = $attributes->merge(['type' => $type]);
        if ($onclick) {
            $attributes = $attributes->merge(['onclick' => $onclick]);
        }
    }
@endphp

<{{ $tag }} {{ $attributes }}>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon && $iconPosition === 'left')
        <x-svg-icon :name="$icon" class="w-4 h-4 mr-2" />
    @endif
    
    <span {{ $loading ? 'class=opacity-0' : '' }}>
        {{ $slot }}
    </span>
    
    @if($icon && $iconPosition === 'right' && !$loading)
        <x-svg-icon :name="$icon" class="w-4 h-4 ml-2" />
    @endif
</{{ $tag }}>

