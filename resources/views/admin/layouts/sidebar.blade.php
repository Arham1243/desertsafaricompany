@php
    $menuItems = [
        [
            'title' => 'Dashboard',
            'icon' => 'bx bxs-home',
            'route' => route('admin.dashboard'),
        ],
        [
            'title' => 'Blog | News',
            'icon' => 'bx bx-news',
            'submenu' => [
                [
                    'title' => 'Blog',
                    'icon' => 'bx bxl-blogger',
                    'submenu' => [
                        [
                            'title' => 'Blogs',
                            'icon' => 'bx bx-list-ul',
                            'route' => route('admin.blogs.index'),
                        ],
                        [
                            'title' => 'Add Blog',
                            'icon' => 'bx bx-plus',
                            'route' => route('admin.blogs.create'),
                        ],
                        [
                            'title' => 'Categories',
                            'icon' => 'bx bx-box',
                            'route' => route('admin.blogs-categories.index'),
                        ],
                        [
                            'title' => 'Tags',
                            'icon' => 'bx bx-tag',
                            'route' => route('admin.blogs-tags.index'),
                        ],
                        [
                            'title' => 'Recovery',
                            'icon' => 'bx bx-refresh',
                            'route' => route('admin.recovery.index', ['resource' => 'blogs']),
                        ],
                    ],
                ],
                [
                    'title' => 'News',
                    'icon' => 'bx bxs-news',
                    'submenu' => [
                        [
                            'title' => 'News',
                            'icon' => 'bx bx-list-ul',
                            'route' => route('admin.news.index'),
                        ],
                        [
                            'title' => 'Add News',
                            'icon' => 'bx bx-plus',
                            'route' => route('admin.news.create'),
                        ],
                        [
                            'title' => 'Categories',
                            'icon' => 'bx bx-box',
                            'route' => route('admin.news-categories.index'),
                        ],
                        [
                            'title' => 'Tags',
                            'icon' => 'bx bx-tag',
                            'route' => route('admin.news-tags.index'),
                        ],
                        [
                            'title' => 'Recovery',
                            'icon' => 'bx bx-refresh',
                            'route' => route('admin.recovery.index', ['resource' => 'news']),
                        ],
                    ],
                ],
            ],
        ],
        [
            'title' => 'Page',
            'icon' => 'bx bx-file',
            'submenu' => [
                [
                    'title' => 'Pages',
                    'icon' => 'bx bx-list-ul',
                    'route' => route('admin.pages.index'),
                ],
                [
                    'title' => 'Add Page',
                    'icon' => 'bx bx-plus',
                    'route' => route('admin.pages.create'),
                ],
                [
                    'title' => 'Recovery',
                    'icon' => 'bx bx-refresh',
                    'route' => route('admin.recovery.index', ['resource' => 'pages']),
                ],
            ],
        ],
        [
            'title' => 'Location',
            'icon' => 'bx bx-map',
            'submenu' => [
                [
                    'title' => 'Countries',
                    'icon' => 'bx bx-flag',
                    'submenu' => [
                        [
                            'title' => 'Countries',
                            'icon' => 'bx bx-list-ul',
                            'route' => route('admin.countries.index'),
                        ],
                        [
                            'title' => 'Add Country',
                            'icon' => 'bx bx-plus',
                            'route' => route('admin.countries.create'),
                        ],
                        [
                            'title' => 'Recovery',
                            'icon' => 'bx bx-refresh',
                            'route' => route('admin.recovery.index', ['resource' => 'countries']),
                        ],
                    ],
                ],
                [
                    'title' => 'Cities',
                    'icon' => 'bx bx-buildings',
                    'submenu' => [
                        [
                            'title' => 'Cities',
                            'icon' => 'bx bx-list-ul',
                            'route' => route('admin.cities.index'),
                        ],
                        [
                            'title' => 'Add City',
                            'icon' => 'bx bx-plus',
                            'route' => route('admin.cities.create'),
                        ],
                        [
                            'title' => 'Recovery',
                            'icon' => 'bx bx-refresh',
                            'route' => route('admin.recovery.index', ['resource' => 'cities']),
                        ],
                    ],
                ],
            ],
        ],
        [
            'title' => 'Tours',
            'icon' => 'bx bx-world',
            'submenu' => [
                [
                    'title' => 'Tours',
                    'icon' => 'bx bx-world',
                    'submenu' => [
                        [
                            'title' => 'Tours',
                            'icon' => 'bx bx-list-ul',
                            'route' => route('admin.tours.index'),
                        ],
                        [
                            'title' => 'Add Tour',
                            'icon' => 'bx bx-plus',
                            'route' => route('admin.tours.create'),
                        ],
                        [
                            'title' => 'Recovery',
                            'icon' => 'bx bx-refresh',
                            'route' => route('admin.recovery.index', ['resource' => 'tours']),
                        ],
                    ],
                ],
                [
                    'title' => 'Categories',
                    'icon' => 'bx bx-category',
                    'submenu' => [
                        [
                            'title' => 'Categories',
                            'icon' => 'bx bx-list-ul',
                            'route' => route('admin.tour-categories.index'),
                        ],
                        [
                            'title' => 'Recovery',
                            'icon' => 'bx bx-refresh',
                            'route' => route('admin.recovery.index', ['resource' => 'tour-categories']),
                        ],
                    ],
                ],
                [
                    'title' => 'Attributes',
                    'icon' => 'bx bx-check-circle',
                    'submenu' => [
                        [
                            'title' => 'Attributes',
                            'icon' => 'bx bx-list-ul',
                            'route' => route('admin.tour-attributes.index'),
                        ],
                        [
                            'title' => 'Recovery',
                            'icon' => 'bx bx-refresh',
                            'route' => route('admin.recovery.index', ['resource' => 'tour-attributes']),
                        ],
                    ],
                ],
                [
                    'title' => 'Availability',
                    'icon' => 'bx bx-calendar',
                    'route' => route('admin.tour-availability.index'),
                ],
                [
                    'title' => 'Booking Calendar',
                    'icon' => 'bx bx-calendar-check',
                    'route' => route('admin.tour-bookings.index'),
                ],
            ],
        ],
        [
            'title' => 'Popup',
            'icon' => 'bx bx-message-square',
            'submenu' => [
                [
                    'title' => 'Popups',
                    'icon' => 'bx bx-list-ul',
                    'route' => route('admin.popups.index'),
                ],
                [
                    'title' => 'Add Popup',
                    'icon' => 'bx bx-plus',
                    'route' => route('admin.popups.create'),
                ],
            ],
        ],
        [
            'title' => 'Coupon',
            'icon' => 'bx bx-gift',
            'submenu' => [
                [
                    'title' => 'Coupons',
                    'icon' => 'bx bx-list-ul',
                    'route' => route('admin.coupons.index'),
                ],
                [
                    'title' => 'Add Coupon',
                    'icon' => 'bx bx-plus',
                    'route' => route('admin.coupons.create'),
                ],
            ],
        ],
        [
            'title' => 'Reviews',
            'icon' => 'bx bx-comment-detail',
            'submenu' => [
                [
                    'title' => 'All Reviews',
                    'icon' => 'bx bx-list-ul',
                    'route' => route('admin.tour-reviews.index'),
                ],
                [
                    'title' => 'Approved',
                    'icon' => 'bx bx-check',
                    'route' => route('admin.tour-reviews.approved'),
                ],
                [
                    'title' => 'Rejected',
                    'icon' => 'bx bx-x',
                    'route' => route('admin.tour-reviews.rejected'),
                ],
            ],
        ],
        [
            'title' => 'Media',
            'icon' => 'bx bx-image',
            'status' => 'pending',
            'submenu' => [
                [
                    'title' => 'Video',
                    'icon' => 'bx bxs-videos',
                    'submenu' => [
                        [
                            'title' => 'Used',
                            'icon' => 'bx bx-video',
                            'route' => 'javascript:void(0)',
                        ],
                        [
                            'title' => 'Un-Used',
                            'icon' => 'bx bx-video-off',
                            'route' => 'javascript:void(0)',
                        ],
                    ],
                ],
                [
                    'title' => 'Pictures',
                    'icon' => 'bx bx-photo-album',
                    'submenu' => [
                        [
                            'title' => 'Used',
                            'icon' => 'bx bxs-image',
                            'route' => 'javascript:void(0)',
                        ],
                        [
                            'title' => 'Un-Used',
                            'icon' => 'bx bx-image',
                            'route' => 'javascript:void(0)',
                        ],
                    ],
                ],
            ],
        ],
        [
            'title' => 'Payout',
            'icon' => 'bx bx-dollar',
            'status' => 'pending',
            'submenu' => [
                [
                    'title' => 'Vendor Payment',
                    'icon' => 'bx bx-credit-card-alt',
                    'submenu' => [
                        [
                            'title' => 'Invoice',
                            'icon' => 'bx bx-receipt',
                            'route' => 'javascript:void(0)',
                        ],
                        [
                            'title' => 'Commissions',
                            'icon' => 'bx bx-credit-card',
                            'route' => 'javascript:void(0)',
                        ],
                    ],
                ],
                [
                    'title' => 'Generator',
                    'icon' => 'bx bxs-store-alt',
                    'submenu' => [
                        [
                            'title' => 'Invoice',
                            'icon' => 'bx bx-receipt',
                            'route' => 'javascript:void(0)',
                        ],
                        [
                            'title' => 'Quotation',
                            'icon' => 'bx bxs-quote-alt-left',
                            'route' => 'javascript:void(0)',
                        ],
                        [
                            'title' => 'Voucher',
                            'icon' => 'bx bx-money-withdraw',
                            'route' => 'javascript:void(0)',
                        ],
                    ],
                ],
            ],
        ],
        [
            'title' => 'Themes',
            'icon' => 'bx bx-paint',
            'route' => 'javascript:void(0)',
            'status' => 'pending',
        ],
        [
            'title' => 'Setting',
            'icon' => 'bx bx-cog',
            'submenu' => [
                [
                    'title' => 'General',
                    'icon' => 'bx bx-cog',
                    'route' => route('admin.settings.edit', ['resource' => 'general']),
                ],

                [
                    'title' => 'Tour',
                    'icon' => 'bx bx-map',
                    'route' => route('admin.settings.edit', ['resource' => 'tour']),
                ],
                [
                    'title' => 'News',
                    'icon' => 'bx bx-news',
                    'route' => route('admin.settings.edit', ['resource' => 'news']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Blog',
                    'icon' => 'bx bx-pencil',
                    'route' => route('admin.settings.edit', ['resource' => 'blog']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Media',
                    'icon' => 'bx bx-image',
                    'route' => route('admin.settings.edit', ['resource' => 'media']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Style',
                    'icon' => 'bx bx-palette',
                    'route' => route('admin.settings.edit', ['resource' => 'style']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Review',
                    'icon' => 'bx bx-star',
                    'route' => route('admin.settings.edit', ['resource' => 'review']),
                    'status' => 'pending',
                ],

                [
                    'title' => 'Booking',
                    'icon' => 'bx bx-book',
                    'route' => route('admin.settings.edit', ['resource' => 'booking']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Payment',
                    'icon' => 'bx bx-credit-card',
                    'route' => route('admin.settings.edit', ['resource' => 'payment']),
                ],
                [
                    'title' => 'Voucher',
                    'icon' => 'bx bx-gift',
                    'route' => route('admin.settings.edit', ['resource' => 'voucher']),
                    'status' => 'pending',
                ],

                [
                    'title' => 'Enquiry',
                    'icon' => 'bx bx-message-square-dots',
                    'route' => route('admin.settings.edit', ['resource' => 'enquiry']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'SMS',
                    'icon' => 'bx bx-message',
                    'route' => route('admin.settings.edit', ['resource' => 'sms']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Email',
                    'icon' => 'bx bx-envelope',
                    'route' => route('admin.settings.edit', ['resource' => 'email']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Online Chat',
                    'icon' => 'bx bx-chat',
                    'route' => route('admin.settings.edit', ['resource' => 'online_chat']),
                    'status' => 'pending',
                ],

                [
                    'title' => 'User',
                    'icon' => 'bx bx-user',
                    'route' => route('admin.settings.edit', ['resource' => 'user']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Vendor',
                    'icon' => 'bx bx-store',
                    'route' => route('admin.settings.edit', ['resource' => 'vendor']),
                    'status' => 'pending',
                ],

                [
                    'title' => 'Advanced',
                    'icon' => 'bx bx-wrench',
                    'route' => route('admin.settings.edit', ['resource' => 'advanced']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Mobile App',
                    'icon' => 'bx bx-mobile-alt',
                    'route' => route('admin.settings.edit', ['resource' => 'mobile_app']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Invoice',
                    'icon' => 'bx bx-file',
                    'route' => route('admin.settings.edit', ['resource' => 'invoice']),
                    'status' => 'pending',
                ],
                [
                    'title' => 'Quotations',
                    'icon' => 'bx bx-file',
                    'route' => route('admin.settings.edit', ['resource' => 'quotations']),
                    'status' => 'pending',
                ],

                [
                    'title' => 'Social Media',
                    'icon' => 'bx bx-share-alt',
                    'route' => route('admin.settings.edit', ['resource' => 'social_media']),
                    'status' => 'pending',
                ],
            ],
        ],
    ];
@endphp

@php
    $headerLogo = $settings->get('header_logo');
    $headerLogoAltText = $settings->get('header_logo_alt_text');
@endphp


<div class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-header">
        <div class="sidebar-header__icon">
            <img src='{{ asset($headerLogo ?? 'admin/assets/images/placeholder-logo.png') }}'
                alt='{{ $headerLogoAltText ?? 'logo' }}' class="imgFluid">
        </div>
    </a>
    <ul class="sidebar-nav">
        @foreach ($menuItems as $item)
            @php
                $isItemActive = Request::url() === ($item['route'] ?? '');
                $hasActiveSubmenu =
                    isset($item['submenu']) &&
                    array_filter($item['submenu'], function ($submenu) {
                        return Request::url() === ($submenu['route'] ?? '') ||
                            (isset($submenu['submenu']) &&
                                array_filter($submenu['submenu'], function ($subSubMenu) {
                                    return Request::url() === ($subSubMenu['route'] ?? '');
                                }));
                    });
                $isOpen = $isItemActive || $hasActiveSubmenu;
            @endphp
            <li class="{{ isset($item['submenu']) ? ($isOpen ? 'custom-dropdown open' : 'custom-dropdown') : '' }}">
                <a href="{{ $item['route'] ?? 'javascript:void(0)' }}"
                    class="{{ isset($item['submenu']) ? 'custom-dropdown__active' : '' }} {{ $isItemActive ? 'active' : '' }}">
                    <div class="info">
                        <i class="{{ $item['icon'] }}"></i>
                        {{ $item['title'] }}
                    </div>
                    @if (isset($item['status']) && $item['status'] === 'pending')
                        <span class="badge badge-sm rounded-pill bg-warning ms-3">pending</span>
                    @endif
                    @if (isset($item['submenu']))
                        <div class="icon"><i class='bx bx-chevron-down'></i></div>
                    @endif
                </a>
                @if (isset($item['submenu']))
                    <div class="custom-dropdown__values {{ $isOpen ? 'open' : '' }}">
                        <ul class="values-wrapper">
                            @foreach ($item['submenu'] as $submenu)
                                @php
                                    $isSubmenuActive = Request::url() === ($submenu['route'] ?? '');
                                    $isSubOpen =
                                        $isSubmenuActive ||
                                        (isset($submenu['submenu']) &&
                                            array_filter($submenu['submenu'], function ($subSubMenu) {
                                                return Request::url() === ($subSubMenu['route'] ?? '');
                                            }));
                                @endphp
                                <li class="custom-dropdown custom-dropdown--sub {{ $isSubOpen ? 'open' : '' }}">
                                    <a href="{{ $submenu['route'] ?? 'javascript:void(0)' }}"
                                        class="{{ isset($submenu['submenu']) ? 'custom-dropdown__active' : '' }} {{ $isSubmenuActive ? 'active' : '' }}">
                                        <div class="info">
                                            <i class="{{ $submenu['icon'] }}"></i> {{ $submenu['title'] }}
                                        </div>
                                        @if (isset($submenu['status']) && $submenu['status'] === 'pending')
                                            <span class="badge badge-sm rounded-pill bg-warning">pending</span>
                                        @endif
                                        @if (isset($submenu['submenu']))
                                            <div class="icon"><i class='bx bx-chevron-down'></i></div>
                                        @endif
                                    </a>
                                    @if (isset($submenu['submenu']))
                                        <div class="custom-dropdown__values {{ $isSubOpen ? 'open' : '' }}">
                                            <ul class="values-wrapper">
                                                @foreach ($submenu['submenu'] as $subSubMenu)
                                                    @php
                                                        $isSubSubmenuActive =
                                                            Request::url() === ($subSubMenu['route'] ?? '');
                                                    @endphp
                                                    <li>
                                                        <a class="{{ $isSubSubmenuActive ? 'active' : '' }}"
                                                            href="{{ $subSubMenu['route'] ?? 'javascript:void(0)' }}">
                                                            <div class="info">
                                                                <i class="{{ $subSubMenu['icon'] }}"></i>
                                                                {{ $subSubMenu['title'] }}
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</div>
