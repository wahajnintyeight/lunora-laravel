// Realtime product search suggestions for header and catalog search
// 2025-style: ES modules, fetch API, debouncing, accessibility-friendly

class RealtimeSearch {
    constructor(options = {}) {
        this.endpoint = options.endpoint;
        this.minChars = options.minChars ?? 2;
        this.debounceMs = options.debounceMs ?? 250;
        this.controllers = new Map();
        this.init();
    }

    init() {
        if (!this.endpoint) return;

        const inputs = document.querySelectorAll('[data-search-input="products"]');
        if (!inputs.length) return;

        inputs.forEach((input) => {
            const form = input.closest('form');
            const resultsContainer = form?.querySelector('[data-search-results="products"]');
            if (!resultsContainer) return;

            const instanceId = crypto.randomUUID ? crypto.randomUUID() : Math.random().toString(36).slice(2);

            let debounceTimer = null;

            input.setAttribute('autocomplete', 'off');
            input.setAttribute('role', 'combobox');
            input.setAttribute('aria-autocomplete', 'list');
            input.setAttribute('aria-expanded', 'false');
            resultsContainer.setAttribute('role', 'listbox');
            resultsContainer.setAttribute('aria-label', 'Search suggestions');

            const closeResults = () => {
                resultsContainer.classList.add('hidden');
                resultsContainer.innerHTML = '';
                input.setAttribute('aria-expanded', 'false');
            };

            const renderResults = (items = []) => {
                if (!items.length) {
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.add('hidden');
                    input.setAttribute('aria-expanded', 'false');
                    return;
                }

                const html = `
                    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg shadow-lg mt-1 max-h-80 overflow-y-auto text-sm">
                        <ul class="divide-y divide-gray-100 dark:divide-neutral-800">
                            ${items
                                .map(
                                    (item, index) => `
                                        <li>
                                            <a href="${item.url}"
                                               class="flex items-center gap-3 px-3 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 focus:bg-gray-100 dark:focus:bg-neutral-800 outline-none cursor-pointer"
                                               role="option"
                                               data-result-index="${index}"
                                            >
                                                <div class="flex-shrink-0 w-10 h-10 rounded-md bg-gray-100 dark:bg-neutral-800 overflow-hidden">
                                                    ${item.thumbnail_url
                                                        ? `<img src="${item.thumbnail_url}" alt="${item.name}" class="w-full h-full object-cover" loading="lazy" />`
                                                        : '<div class="w-full h-full flex items-center justify-center text-xs text-gray-400 dark:text-neutral-500">No image</div>'}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-gray-900 dark:text-neutral-100 truncate">${item.name}</p>
                                                    ${item.category
                                                        ? `<p class="text-xs text-gray-500 dark:text-neutral-400 truncate">${item.category}</p>`
                                                        : ''}
                                                </div>
                                                ${item.price
                                                    ? `<div class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">${item.price}</div>`
                                                    : ''}
                                            </a>
                                        </li>
                                    `
                                )
                                .join('')}
                        </ul>
                    </div>
                `;

                resultsContainer.innerHTML = html;
                resultsContainer.classList.remove('hidden');
                input.setAttribute('aria-expanded', 'true');
            };

            const fetchSuggestions = async (query) => {
                // Cancel previous request for this instance
                const existing = this.controllers.get(instanceId);
                if (existing) {
                    existing.abort();
                }

                const controller = new AbortController();
                this.controllers.set(instanceId, controller);

                const url = new URL(this.endpoint, window.location.origin);
                url.searchParams.set('q', query);

                try {
                    const response = await fetch(url.toString(), {
                        headers: {
                            Accept: 'application/json',
                        },
                        signal: controller.signal,
                    });

                    if (!response.ok) {
                        throw new Error(`Search request failed with status ${response.status}`);
                    }

                    const data = await response.json();
                    renderResults(data.products || []);
                } catch (error) {
                    if (error.name === 'AbortError') return;
                    console.error('Search suggestions error:', error);
                    closeResults();
                }
            };

            input.addEventListener('input', () => {
                const value = input.value.trim();

                if (debounceTimer) {
                    clearTimeout(debounceTimer);
                }

                if (value.length < this.minChars) {
                    closeResults();
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetchSuggestions(value);
                }, this.debounceMs);
            });

            input.addEventListener('focus', () => {
                if (input.value.trim().length >= this.minChars && resultsContainer.innerHTML.trim() !== '') {
                    resultsContainer.classList.remove('hidden');
                    input.setAttribute('aria-expanded', 'true');
                }
            });

            input.addEventListener('blur', () => {
                setTimeout(() => closeResults(), 150);
            });

            form?.addEventListener('submit', () => {
                closeResults();
            });
        });
    }
}

export function initRealtimeProductSearch() {
    const endpoint = document.querySelector('meta[name="search-suggestions-endpoint"]')?.content;
    if (!endpoint) return;

    new RealtimeSearch({
        endpoint,
        minChars: 2,
        debounceMs: 250,
    });
}
