@props(['product'])

<div class="space-y-4">
    @if($product->images->count() > 0)
        <!-- Main Image Display - Mobile Optimized -->
        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden relative touch-gallery" data-touch-gallery>
            <img id="main-image" 
                 src="{{ $product->images->where('is_primary', true)->first()?->medium_url ?? $product->images->first()->medium_url }}" 
                 alt="{{ $product->images->where('is_primary', true)->first()?->alt_text ?? $product->name }}"
                 class="w-full h-full object-cover cursor-zoom-in touch-target"
                 onclick="openImageModal(this.src, this.alt)">
            
            @if($product->images->count() > 1)
                <!-- Mobile Navigation Arrows -->
                <button type="button" 
                        onclick="navigateMainImage(-1)"
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black sm:hidden touch-target"
                        aria-label="Previous image">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <button type="button" 
                        onclick="navigateMainImage(1)"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black sm:hidden touch-target"
                        aria-label="Next image">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                
                <!-- Mobile Image Indicators -->
                <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1 sm:hidden">
                    @foreach($product->images->sortBy('sort_order') as $image)
                        <button type="button" 
                                onclick="changeMainImageByIndex({{ $loop->index }})"
                                class="w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-75 focus:outline-none focus:ring-1 focus:ring-white {{ $loop->first ? 'bg-opacity-100' : '' }}"
                                data-gallery-indicator
                                aria-label="View image {{ $loop->iteration }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if($product->images->count() > 1)
            <!-- Thumbnail Navigation - Mobile Optimized -->
            <div class="hidden sm:grid grid-cols-4 gap-2 sm:grid-cols-6 lg:grid-cols-4">
                @foreach($product->images->sortBy('sort_order') as $image)
                    <button type="button" 
                            class="touch-target aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-transparent hover:border-emerald-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 {{ $loop->first ? 'border-emerald-500' : '' }}"
                            onclick="changeMainImage('{{ $image->medium_url }}', '{{ $image->alt_text }}', this, {{ $loop->index }})"
                            aria-label="View image {{ $loop->iteration }}">
                        <img src="{{ $image->thumbnail_url }}" 
                             alt="{{ $image->alt_text }}"
                             class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
            
            <!-- Mobile Horizontal Scroll Thumbnails -->
            <div class="sm:hidden">
                <div class="flex space-x-2 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($product->images->sortBy('sort_order') as $image)
                        <button type="button" 
                                class="touch-target flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden border-2 border-transparent hover:border-emerald-500 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 {{ $loop->first ? 'border-emerald-500' : '' }}"
                                onclick="changeMainImage('{{ $image->medium_url }}', '{{ $image->alt_text }}', this, {{ $loop->index }})"
                                aria-label="View image {{ $loop->iteration }}">
                            <img src="{{ $image->thumbnail_url }}" 
                                 alt="{{ $image->alt_text }}"
                                 class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Image Counter -->
        @if($product->images->count() > 1)
            <div class="text-center">
                <span class="text-sm text-gray-500">
                    <span id="current-image">1</span> of {{ $product->images->count() }} images
                </span>
            </div>
        @endif
    @else
        <!-- Placeholder when no images -->
        <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="mt-2 text-sm text-gray-500">No image available</p>
            </div>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="image-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-black bg-opacity-75" onclick="closeImageModal()"></div>
        
        <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 id="modal-image-title" class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                <button type="button" onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="text-center">
                <img id="modal-image" 
                     src="" 
                     alt="" 
                     class="max-w-full max-h-96 mx-auto rounded-lg">
            </div>

            @if($product->images->count() > 1)
                <!-- Modal Navigation -->
                <div class="flex items-center justify-center gap-4 mt-6">
                    <button type="button" 
                            id="prev-btn"
                            onclick="navigateImage(-1)"
                            class="p-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <span id="modal-counter" class="text-sm text-gray-500">1 of {{ $product->images->count() }}</span>
                    
                    <button type="button" 
                            id="next-btn"
                            onclick="navigateImage(1)"
                            class="p-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentImageIndex = 0;
const images = @json($product->images->sortBy('sort_order')->values()->map(function($image) {
    return [
        'url' => $image->url,
        'medium_url' => $image->medium_url,
        'thumbnail_url' => $image->thumbnail_url,
        'alt_text' => $image->alt_text
    ];
}));

function changeMainImage(src, alt, thumbnail, index = null) {
    const mainImage = document.getElementById('main-image');
    mainImage.src = src;
    mainImage.alt = alt;
    
    // Update active thumbnail
    document.querySelectorAll('[onclick*="changeMainImage"]').forEach(btn => {
        btn.classList.remove('border-emerald-500');
        btn.classList.add('border-transparent');
    });
    thumbnail.classList.remove('border-transparent');
    thumbnail.classList.add('border-emerald-500');
    
    // Update current index
    if (index !== null) {
        currentImageIndex = index;
    } else {
        const thumbnails = Array.from(document.querySelectorAll('[onclick*="changeMainImage"]'));
        currentImageIndex = thumbnails.indexOf(thumbnail);
    }
    
    updateImageCounter();
    updateMobileIndicators();
}

function changeMainImageByIndex(index) {
    if (index >= 0 && index < images.length) {
        currentImageIndex = index;
        const image = images[index];
        const mainImage = document.getElementById('main-image');
        mainImage.src = image.medium_url;
        mainImage.alt = image.alt_text;
        
        // Update thumbnails
        document.querySelectorAll('[onclick*="changeMainImage"]').forEach((btn, i) => {
            if (i === index) {
                btn.classList.remove('border-transparent');
                btn.classList.add('border-emerald-500');
            } else {
                btn.classList.remove('border-emerald-500');
                btn.classList.add('border-transparent');
            }
        });
        
        updateImageCounter();
        updateMobileIndicators();
    }
}

function navigateMainImage(direction) {
    if (images.length <= 1) return;
    
    let newIndex = currentImageIndex + direction;
    
    if (newIndex < 0) {
        newIndex = images.length - 1;
    } else if (newIndex >= images.length) {
        newIndex = 0;
    }
    
    changeMainImageByIndex(newIndex);
}

function updateMobileIndicators() {
    const indicators = document.querySelectorAll('[data-gallery-indicator]');
    indicators.forEach((indicator, index) => {
        if (index === currentImageIndex) {
            indicator.classList.remove('bg-opacity-50');
            indicator.classList.add('bg-opacity-100');
        } else {
            indicator.classList.remove('bg-opacity-100');
            indicator.classList.add('bg-opacity-50');
        }
    });
}

function updateImageCounter() {
    const counter = document.getElementById('current-image');
    if (counter) {
        counter.textContent = currentImageIndex + 1;
    }
}

function openImageModal(src, alt) {
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    const modalTitle = document.getElementById('modal-image-title');
    
    modalImage.src = src;
    modalImage.alt = alt;
    modalTitle.textContent = alt || '{{ $product->name }}';
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    updateModalNavigation();
}

function closeImageModal() {
    const modal = document.getElementById('image-modal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function navigateImage(direction) {
    if (images.length <= 1) return;
    
    currentImageIndex += direction;
    
    if (currentImageIndex < 0) {
        currentImageIndex = images.length - 1;
    } else if (currentImageIndex >= images.length) {
        currentImageIndex = 0;
    }
    
    const image = images[currentImageIndex];
    const modalImage = document.getElementById('modal-image');
    const modalTitle = document.getElementById('modal-image-title');
    
    modalImage.src = image.url;
    modalImage.alt = image.alt_text;
    modalTitle.textContent = image.alt_text || '{{ $product->name }}';
    
    // Update main gallery
    const mainImage = document.getElementById('main-image');
    mainImage.src = image.medium_url;
    mainImage.alt = image.alt_text;
    
    // Update thumbnail selection
    const thumbnails = document.querySelectorAll('[onclick*="changeMainImage"]');
    thumbnails.forEach((thumb, index) => {
        if (index === currentImageIndex) {
            thumb.classList.remove('border-transparent');
            thumb.classList.add('border-emerald-500');
        } else {
            thumb.classList.remove('border-emerald-500');
            thumb.classList.add('border-transparent');
        }
    });
    
    updateImageCounter();
    updateModalNavigation();
}

function updateModalNavigation() {
    const modalCounter = document.getElementById('modal-counter');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    if (modalCounter) {
        modalCounter.textContent = `${currentImageIndex + 1} of ${images.length}`;
    }
    
    if (prevBtn) {
        prevBtn.disabled = images.length <= 1;
    }
    
    if (nextBtn) {
        nextBtn.disabled = images.length <= 1;
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('image-modal');
    if (!modal.classList.contains('hidden')) {
        if (e.key === 'ArrowLeft') {
            navigateImage(-1);
        } else if (e.key === 'ArrowRight') {
            navigateImage(1);
        } else if (e.key === 'Escape') {
            closeImageModal();
        }
    }
});

// Touch/swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

document.addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', function(e) {
    const modal = document.getElementById('image-modal');
    if (!modal.classList.contains('hidden')) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            // Swipe left - next image
            navigateImage(1);
        } else {
            // Swipe right - previous image
            navigateImage(-1);
        }
    }
}
</script>
@endpush