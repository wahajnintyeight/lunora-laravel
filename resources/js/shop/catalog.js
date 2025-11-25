// Catalog mega-menu with dynamic category loading
// Fetches categories from API and renders them in the dropdown

class CatalogManager {
    constructor() {
        this.dropdownRoot = null;
        this.categoriesEndpoint = null;
        this.productsUrl = null;
        this.categories = [];
        this.activeCategory = null;
        this.init();
    }

    init() {
        this.dropdownRoot = document.querySelector('[data-catalog-products-url]');
        if (!this.dropdownRoot) {
            console.warn('CatalogManager: No catalog dropdown found');
            return;
        }

        this.productsUrl = this.dropdownRoot.getAttribute('data-catalog-products-url');
        console.log('CatalogManager: Initialized with products URL', this.productsUrl);
        this.categoriesEndpoint = document.querySelector('meta[name="categories-endpoint"]')?.content;

        console.log('CatalogManager: Initialized with endpoint', this.categoriesEndpoint);

        if (!this.categoriesEndpoint) {
            console.warn('CatalogManager: No categories endpoint found');
            return;
        }

        this.fetchCategories();
        this.bindEvents();
    }

    async fetchCategories() {
        try {
            console.log('CatalogManager: Fetching categories from', this.categoriesEndpoint);
            const response = await fetch(this.categoriesEndpoint, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('Failed to fetch categories');

            const data = await response.json();
            this.categories = data.categories || [];
            console.log('CatalogManager: Loaded', this.categories.length, 'categories');
            this.renderCatalog();
        } catch (error) {
            console.error('CatalogManager: Error fetching categories', error);
        }
    }

    renderCatalog() {
        const sidebar = this.dropdownRoot.querySelector('#hs-catalog-sidebar-nav-tabs');
        const contentContainer = this.dropdownRoot.querySelector('.pe-4.sm\\:px-10');
        const mobileSelect = this.dropdownRoot.querySelector('#hs-catalog-sidebar-nav-select');

        if (sidebar && this.categories.length > 0) {
            this.renderSidebar(sidebar);
        }

        if (contentContainer && this.categories.length > 0) {
            this.renderContent(contentContainer);
        }

        if (mobileSelect && this.categories.length > 0) {
            this.renderMobileSelect(mobileSelect);
        }

        // Hide loading placeholders
        this.hideLoadingPlaceholders();

        // Re-initialize Preline tabs after rendering
        this.reinitializePreline();
    }

    renderSidebar(container) {
        const html = this.categories.map((category, index) => `
            <a class="hs-tab-active:bg-gray-100 dark:hs-tab-active:bg-neutral-800 py-2.5 px-2 w-full flex items-center gap-x-2 text-start font-medium text-sm bg-white text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800 ${index === 0 ? 'active' : ''}" 
               id="catalog-tab-item-${category.id}" 
               aria-selected="${index === 0 ? 'true' : 'false'}" 
               data-hs-tab="#catalog-tab-${category.id}" 
               aria-controls="catalog-tab-${category.id}" 
               role="tab" 
               href="#" 
               data-catalog-category="${category.slug}">
                ${this.getCategoryIcon(category.slug)}
                ${category.name}
                ${category.products_count ? `<span class="ms-auto text-xs text-gray-400">(${category.products_count})</span>` : ''}
                <svg class="shrink-0 size-3.5 ms-auto text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        `).join('');

        container.innerHTML = html;
    }

    hideLoadingPlaceholders() {
        // Hide sidebar loading placeholder
        const sidebarPlaceholder = this.dropdownRoot.querySelector('#catalog-loading-placeholder');
        if (sidebarPlaceholder) {
            sidebarPlaceholder.style.display = 'none';
        }

        // Hide content loading placeholder
        const contentPlaceholder = this.dropdownRoot.querySelector('#catalog-content-loading-placeholder');
        if (contentPlaceholder) {
            contentPlaceholder.style.display = 'none';
        }
    }

    renderContent(container) {
        const html = this.categories.map((category, index) => `
            <div id="catalog-tab-${category.id}" class="${index === 0 ? '' : 'hidden'}" role="tabpanel" aria-labelledby="catalog-tab-item-${category.id}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 md:gap-10">
                    ${this.renderCategoryChildren(category)}
                </div>
                ${category.children.length === 0 ? this.renderEmptyState(category) : ''}
            </div>
        `).join('');

        container.innerHTML = html;
    }

    renderCategoryChildren(category) {
        // Combine subcategories and products for display
        const items = [];

        // Add subcategories first
        if (category.children && category.children.length > 0) {
            category.children.forEach(child => {
                items.push({
                    type: 'subcategory',
                    data: child
                });
            });
        }

        // Add products if there are any
        if (category.products && category.products.length > 0) {
            category.products.forEach(product => {
                items.push({
                    type: 'product',
                    data: product
                });
            });
        }

        if (items.length === 0) {
            return '';
        }

        // Group items into columns (max 3 columns)
        const columns = [[], [], []];
        items.forEach((item, index) => {
            columns[index % 3].push(item);
        });

        return columns.map(column => `
            <div class="flex flex-col gap-4 md:gap-6">
                ${column.map(item => {
            if (item.type === 'subcategory') {
                const child = item.data;
                return `
                            <div class="space-y-3">
                                <div>
                                    ${child.image_url ? `
                                        <a href="${child.url}" class="mb-2 flex justify-center items-center size-16 bg-gray-100 rounded-xl overflow-hidden dark:bg-neutral-800 hover:opacity-80 transition-opacity">
                                            <img src="${child.image_url}" alt="${child.name}" class="w-full h-full object-cover">
                                        </a>
                                    ` : `
                                        <span class="mb-2 flex justify-center items-center size-12 bg-gray-100 text-gray-800 rounded-xl dark:bg-neutral-800 dark:text-neutral-200">
                                            ${this.getCategoryIcon(child.slug, 'size-6')}
                                        </span>
                                    `}
                                    <a href="${child.url}" class="block mb-2 font-semibold text-sm text-gray-800 dark:text-neutral-200 hover:text-[#f59e0b] dark:hover:text-emerald-400">
                                        ${child.name}
                                        ${child.products_count ? `<span class="text-xs text-gray-400 ml-1">(${child.products_count})</span>` : ''}
                                    </a>
                                    ${child.children && child.children.length > 0 ? `
                                        <div class="flex flex-col gap-y-1">
                                            ${child.children.map(grandchild => `
                                                <div>
                                                    <a class="text-[13px] text-gray-500 underline-offset-4 hover:text-gray-800 hover:underline focus:outline-hidden focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" 
                                                       href="${grandchild.url}">
                                                        ${grandchild.name}
                                                        ${grandchild.products_count ? `<span class="text-xs text-gray-400">(${grandchild.products_count})</span>` : ''}
                                                    </a>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                    ${child.products && child.products.length > 0 ? `
                                        <div class="mt-3 space-y-1">
                                            <div class="text-xs font-medium text-gray-600 dark:text-neutral-400 mb-2">Featured Products:</div>
                                            ${child.products.slice(0, 3).map(product => `
                                                <div class="flex items-center gap-2">
                                                    ${product.image_url ? `
                                                        <img src="${product.image_url}" alt="${product.name}" class="w-6 h-6 rounded object-cover">
                                                    ` : `
                                                        <div class="w-6 h-6 bg-gray-200 rounded dark:bg-neutral-700"></div>
                                                    `}
                                                    <a href="${product.url}" class="text-[13px] text-gray-600 hover:text-[#f59e0b] dark:text-neutral-400 dark:hover:text-emerald-400 truncate">
                                                        ${product.name}
                                                    </a>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
            } else {
                const product = item.data;
                return `
                            <div class="group">
                                <a href="${product.url}" class="block">
                                    ${product.image_url ? `
                                        <div class="mb-2 aspect-square bg-gray-100 rounded-lg overflow-hidden dark:bg-neutral-800 group-hover:opacity-80 transition-opacity">
                                            <img src="${product.image_url}" alt="${product.name}" class="w-full h-full object-cover">
                                        </div>
                                    ` : `
                                        <div class="mb-2 aspect-square bg-gray-100 rounded-lg flex items-center justify-center dark:bg-neutral-800">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    `}
                                    <h4 class="text-sm font-medium text-gray-800 dark:text-neutral-200 group-hover:text-[#f59e0b] dark:group-hover:text-emerald-400 line-clamp-2">
                                        ${product.name}
                                    </h4>
                                    <p class="text-sm font-semibold text-[#f59e0b] dark:text-emerald-400 mt-1">
                                        ${product.formatted_price}
                                    </p>
                                    ${product.is_featured ? `
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-emerald-100 text-emerald-800 rounded-full dark:bg-emerald-900 dark:text-emerald-200">
                                            Featured
                                        </span>
                                    ` : ''}
                                </a>
                            </div>
                        `;
            }
        }).join('')}
            </div>
        `).join('');
    }

    renderEmptyState(category) {
        return `
            <div class="col-span-3 text-center py-8">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-gray-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-neutral-400">
                        No products available in this category yet.
                    </p>
                    <a href="${category.url}" class="text-[#f59e0b] hover:underline text-sm">
                        Browse ${category.name} →
                    </a>
                </div>
            </div>
        `;
    }

    renderMobileSelect(select) {
        const options = this.categories.map(category =>
            `<option value="${category.slug}" data-catalog-category="${category.slug}">${category.name}</option>`
        ).join('');

        select.innerHTML = `<option value="">Select a category</option>${options}`;
    }

    getCategoryIcon(slug, sizeClass = 'size-4') {
        const icons = {
            'rings': `<svg class="shrink-0 ${sizeClass}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/></svg>`,
            'necklaces': `<svg class="shrink-0 ${sizeClass}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6"/></svg>`,
            'earrings': `<svg class="shrink-0 ${sizeClass}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>`,
            'bracelets': `<svg class="shrink-0 ${sizeClass}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="15" r="4"/><circle cx="18" cy="15" r="4"/></svg>`,
            'collections': `<svg class="shrink-0 ${sizeClass}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`,
            'default': `<svg class="shrink-0 ${sizeClass}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>`
        };

        return icons[slug] || icons['default'];
    }

    reinitializePreline() {
        // Re-initialize Preline UI tabs after dynamic content
        setTimeout(() => {
            try {
                if (typeof window.HSStaticMethods !== 'undefined') {
                    window.HSStaticMethods.autoInit();
                }
            } catch (error) {
                console.warn('CatalogManager: Error reinitializing Preline', error);
            }

            try {
                if (typeof window.HSTabs !== 'undefined') {
                    window.HSTabs.autoInit();
                }
            } catch (error) {
                console.warn('CatalogManager: Error reinitializing HSTabs', error);
            }
        }, 100);
    }

    bindEvents() {
        // Handle category clicks for navigation
        this.dropdownRoot.addEventListener('click', (event) => {
            const categoryLink = event.target.closest('[data-catalog-category]');
            if (!categoryLink) return;

            const category = categoryLink.getAttribute('data-catalog-category');
            if (!category) return;

            // If it's a tab trigger, don't navigate - let Preline handle it
            if (categoryLink.hasAttribute('data-hs-tab')) {
                return;
            }

            // Allow Ctrl/Cmd+click to open in new tab
            if (event.button === 0 && !event.metaKey && !event.ctrlKey) {
                event.preventDefault();
                this.navigateToCategory(category);
            }
        });

        // Mobile select change
        const mobileSelect = document.getElementById('hs-catalog-sidebar-nav-select');
        if (mobileSelect) {
            mobileSelect.addEventListener('change', () => {
                const selectedOption = mobileSelect.options[mobileSelect.selectedIndex];
                const category = selectedOption?.getAttribute('data-catalog-category');
                if (category) {
                    this.navigateToCategory(category);
                }
            });
        }
    }

    navigateToCategory(categorySlug) {
        try {
            const url = new URL(this.productsUrl, window.location.origin);
            url.searchParams.set('category', categorySlug);
            window.location.href = url.toString();
        } catch (e) {
            console.error('Failed to build catalog filter URL', e);
        }
    }
}

export function initCatalogFilter() {
    new CatalogManager();
}
