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
        if (!this.dropdownRoot) return;

        this.productsUrl = this.dropdownRoot.getAttribute('data-catalog-products-url');
        this.categoriesEndpoint = document.querySelector('meta[name="categories-endpoint"]')?.content;

        if (!this.categoriesEndpoint) {
            console.warn('CatalogManager: No categories endpoint found');
            return;
        }

        this.fetchCategories();
        this.bindEvents();
    }

    async fetchCategories() {
        try {
            const response = await fetch(this.categoriesEndpoint, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('Failed to fetch categories');

            const data = await response.json();
            this.categories = data.categories || [];
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
        if (!category.children || category.children.length === 0) {
            return '';
        }

        // Group children into columns (max 3 columns)
        const columns = [[], [], []];
        category.children.forEach((child, index) => {
            columns[index % 3].push(child);
        });

        return columns.map(column => `
            <div class="flex flex-col gap-7 md:gap-10">
                ${column.map(child => `
                    <div class="space-y-7 md:space-y-10">
                        <div>
                            <span class="mb-2 flex justify-center items-center size-12 bg-gray-100 text-gray-800 rounded-xl dark:bg-neutral-800 dark:text-neutral-200">
                                ${this.getCategoryIcon(child.slug, 'size-6')}
                            </span>
                            <a href="${child.url}" class="block mb-3 font-medium text-sm text-gray-800 dark:text-neutral-200 hover:text-emerald-600 dark:hover:text-emerald-400">
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
                        </div>
                    </div>
                `).join('')}
            </div>
        `).join('');
    }

    renderEmptyState(category) {
        return `
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500 dark:text-neutral-400">
                    Browse all products in <a href="${category.url}" class="text-emerald-600 hover:underline">${category.name}</a>
                </p>
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
            if (typeof window.HSStaticMethods !== 'undefined') {
                window.HSStaticMethods.autoInit();
            }
            if (typeof window.HSTabs !== 'undefined') {
                window.HSTabs.autoInit();
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
