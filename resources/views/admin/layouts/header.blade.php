@php
    $menuItems = require resource_path('views/admin/layouts/menu-config.php');
@endphp

<div class="col-12">
    <div class="dashboard-header-wrapper">
        <div class="row g-0">
            <div class="col-md-9">
                <div class="dashboard-header">
                    <div class="row g-0 justify-content-between">
                        <div class="col-md-4">
                            <div class="global-heading-wrapper">
                                <div id="sidebarToggle" class="sidebar-toggle"><i class='bx bx-menu'></i></div>
                                <h2>Dashboard</h2>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="header-actions-wrapper d-flex align-items-center justify-content-end gap-3">
                                <!-- Global Search Bar -->
                                <div class="global-search-wrapper">
                                    <div class="search-input-container">
                                        <input type="text" id="globalSearch" class="global-search-input"
                                            placeholder="Search..." autocomplete="off">
                                        <i class='bx bx-search search-icon'></i>
                                    </div>
                                    <div class="search-dropdown" id="searchDropdown">
                                        <div class="search-results" id="searchResults">
                                            <!-- Search results will be populated here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Notification Icon -->
                                <div class="notifi-icon">
                                    <i class='bx bxs-bell'></i>
                                    <div class="notification-count">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-header  d-flex justify-content-end p-0">
                    <div class="user-profile dropdown">
                        <div class="name">
                            <div class="name1">{{ Auth::guard('admin')->user()->email }}</div>
                            <div class="role">{{ Auth::guard('admin')->user()->name }}</div>
                        </div>
                        <div class="user-image-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                            role="button">
                            <i class='bx bxs-user-circle'></i>
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Global Search Functionality
        class GlobalSearch {
            constructor() {
                this.searchInput = document.getElementById('globalSearch');
                this.searchDropdown = document.getElementById('searchDropdown');
                this.searchResults = document.getElementById('searchResults');
                this.menuItems = [];
                this.currentFocusIndex = -1;
                this.debounceTimer = null;

                if (this.searchInput) {
                    this.init();
                }
            }

            init() {
                this.extractMenuItems();
                this.bindEvents();
            }

            extractMenuItems() {
                // Extract menu items from the sidebar PHP array
                const menuData = @json($menuItems ?? []);
                this.menuItems = this.flattenMenuItems(menuData);
            }

            flattenMenuItems(items, parentPath = '') {
                let flatItems = [];

                items.forEach(item => {
                    // Skip items without routes or with pending status
                    if (item.route && item.route !== 'javascript:void(0)' && (!item.status || item
                            .status !== 'pending')) {
                        const currentPath = parentPath ? `${parentPath} > ${item.title}` : item
                            .title;

                        flatItems.push({
                            title: item.title,
                            icon: item.icon,
                            route: item.route,
                            path: currentPath,
                            searchText: currentPath.toLowerCase()
                        });
                    }

                    // Recursively process submenu items
                    if (item.submenu && Array.isArray(item.submenu)) {
                        const currentPath = parentPath ? `${parentPath} > ${item.title}` : item
                            .title;
                        flatItems = flatItems.concat(this.flattenMenuItems(item.submenu,
                            currentPath));
                    }
                });

                return flatItems;
            }

            bindEvents() {
                // Input events
                this.searchInput.addEventListener('input', (e) => {
                    this.handleSearch(e.target.value);
                });

                this.searchInput.addEventListener('focus', () => {
                    if (this.searchInput.value.trim()) {
                        this.showDropdown();
                    }
                });

                this.searchInput.addEventListener('blur', (e) => {
                    // Delay hiding to allow clicking on results
                    setTimeout(() => {
                        this.hideDropdown();
                    }, 150);
                });

                // Keyboard navigation
                this.searchInput.addEventListener('keydown', (e) => {
                    this.handleKeyNavigation(e);
                });

                // Click outside to close
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.global-search-wrapper')) {
                        this.hideDropdown();
                    }
                });
            }

            handleSearch(query) {
                // Clear previous timer
                if (this.debounceTimer) {
                    clearTimeout(this.debounceTimer);
                }

                // Debounce search
                this.debounceTimer = setTimeout(() => {
                    this.performSearch(query);
                }, 200);
            }

            performSearch(query) {
                const trimmedQuery = query.trim().toLowerCase();

                if (trimmedQuery.length === 0) {
                    this.hideDropdown();
                    return;
                }

                const results = this.menuItems.filter(item =>
                    item.searchText.includes(trimmedQuery)
                );

                this.displayResults(results, trimmedQuery);
                this.showDropdown();
            }

            displayResults(results, query) {
                this.currentFocusIndex = -1;

                if (results.length === 0) {
                    this.searchResults.innerHTML = `
                    <div class="search-no-results">
                        <i class='mb-2 bx bx-search bx-md' style="color: var(--color-primary);"></i>
                        <p>No results found for "${query}"</p>
                    </div>
                `;
                    return;
                }

                // Group results by main category
                const groupedResults = this.groupResultsByCategory(results);
                let html = '';

                Object.keys(groupedResults).forEach(category => {
                    if (Object.keys(groupedResults).length > 1) {
                        html += `<div class="search-category-header">${category}</div>`;
                    }

                    groupedResults[category].forEach((item, index) => {
                        const highlightedTitle = this.highlightMatch(item.title, query);
                        const highlightedPath = this.highlightMatch(item.path, query);

                        html += `
                        <a href="${item.route}" class="search-result-item" data-index="${index}">
                            <div class="search-result-icon">
                                <i class="${item.icon}"></i>
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">${highlightedTitle}</div>
                                <div class="search-result-path">${highlightedPath}</div>
                            </div>
                        </a>
                    `;
                    });
                });

                this.searchResults.innerHTML = html;
            }

            groupResultsByCategory(results) {
                const grouped = {};

                results.forEach(item => {
                    const pathParts = item.path.split(' > ');
                    const mainCategory = pathParts[0] || 'Other';

                    if (!grouped[mainCategory]) {
                        grouped[mainCategory] = [];
                    }
                    grouped[mainCategory].push(item);
                });

                return grouped;
            }

            highlightMatch(text, query) {
                if (!query) return text;

                const regex = new RegExp(`(${this.escapeRegex(query)})`, 'gi');
                return text.replace(regex, '<mark style="background: #fff3cd; padding: 0;">$1</mark>');
            }

            escapeRegex(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            handleKeyNavigation(e) {
                const resultItems = this.searchResults.querySelectorAll('.search-result-item');

                if (resultItems.length === 0) return;

                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        this.currentFocusIndex = Math.min(this.currentFocusIndex + 1, resultItems.length -
                            1);
                        this.updateFocus(resultItems);
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        this.currentFocusIndex = Math.max(this.currentFocusIndex - 1, -1);
                        this.updateFocus(resultItems);
                        break;

                    case 'Enter':
                        e.preventDefault();
                        if (this.currentFocusIndex >= 0 && resultItems[this.currentFocusIndex]) {
                            resultItems[this.currentFocusIndex].click();
                        }
                        break;

                    case 'Escape':
                        this.hideDropdown();
                        this.searchInput.blur();
                        break;
                }
            }

            updateFocus(resultItems) {
                // Remove previous focus
                resultItems.forEach(item => item.classList.remove('focused'));

                // Add focus to current item
                if (this.currentFocusIndex >= 0 && resultItems[this.currentFocusIndex]) {
                    resultItems[this.currentFocusIndex].classList.add('focused');
                    resultItems[this.currentFocusIndex].scrollIntoView({
                        block: 'nearest',
                        behavior: 'smooth'
                    });
                }
            }

            showDropdown() {
                this.searchDropdown.classList.add('show');
            }

            hideDropdown() {
                this.searchDropdown.classList.remove('show');
                this.currentFocusIndex = -1;
            }
        }

        // Initialize the global search
        new GlobalSearch();
    });
</script>
