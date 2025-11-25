@php
    $types = ['success', 'error', 'warning', 'info'];
    $hasAlerts = false;
    foreach ($types as $t) {
        if (session($t)) { $hasAlerts = true; break; }
    }
@endphp

@if ($hasAlerts)
<div 
    x-data="{ open: true }" 
    x-init="setTimeout(() => open = false, 5000)"
    x-show="open"
    x-transition.opacity.scale
    class="fixed inset-x-0 top-2 z-[110] flex justify-center px-3 sm:inset-auto sm:top-4 sm:right-4 sm:left-auto sm:justify-end sm:px-0 pointer-events-none">
    <div class="pointer-events-auto w-full max-w-sm rounded-xl shadow-lg border border-[#f59e0b]/40 bg-[#450a0a] text-gold-100 overflow-hidden">
        @foreach ($types as $type)
            @if (session($type))
                @php
                    $iconBg = [
                        'success' => 'bg-emerald-500',
                        'error' => 'bg-red-500',
                        'warning' => 'bg-amber-500',
                        'info' => 'bg-sky-500',
                    ][$type];
                    $label = ucfirst($type);
                @endphp
                <div class="flex items-start gap-3 px-4 py-3 text-sm">
                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $iconBg }} text-white">
                        @if ($type === 'success')
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293A1 1 0 006.293 10.7l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @elseif ($type === 'error')
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92C18.08 14.92 17.11 16 15.83 16H4.17c-1.28 0-2.25-1.08-1.493-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v3a1 1 0 01-1 1z" clip-rule="evenodd" />
                            </svg>
                        @elseif ($type === 'warning')
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92C18.08 14.92 17.11 16 15.83 16H4.17c-1.28 0-2.25-1.08-1.493-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v3a1 1 0 01-1 1z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 114 4a8 8 0 0114 6zm-7-4a1 1 0 00-.894.553L9 8v4a1 1 0 001 1h1a1 1 0 100-2v-2.382l.447-.894A1 1 0 0011 6zM10 15a1.25 1.25 0 100 2.5A1.25 1.25 0 0010 15z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold tracking-wide text-gold-200">{{ $label }}</p>
                        <p class="mt-0.5 leading-snug text-gold-100">{{ session($type) }}</p>
                    </div>
                    <button type="button" @click="open = false" class="mt-0.5 text-gold-300 hover:text-gold-100 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif
