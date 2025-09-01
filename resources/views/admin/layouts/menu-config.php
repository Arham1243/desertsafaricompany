<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'bx bxs-home',
        'route' => route('admin.dashboard'),
    ],
    [
        'title' => 'Blogs | News',
        'icon' => 'bx bx-news',
        'submenu' => [
            [
                'title' => 'Blogs',
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
        'title' => 'Locations',
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
                'title' => 'Authors',
                'icon' => 'bx bxs-group',
                'submenu' => [
                    [
                        'title' => 'Authors',
                        'icon' => 'bx bx-list-ul',
                        'route' => route('admin.tour-authors.index'),
                    ],
                    [
                        'title' => 'Recovery',
                        'icon' => 'bx bx-refresh',
                        'route' => route('admin.recovery.index', ['resource' => 'tour-authors']),
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
                'title' => 'Detail Popups',
                'icon' => 'bx bx-message-square-detail',
                'route' => route('admin.tour-popups.index'),
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
            [
                'title' => 'Recovery',
                'icon' => 'bx bx-refresh',
                'route' => route('admin.recovery.index', ['resource' => 'popups']),
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
        'title' => 'Orders',
        'icon' => 'bx bx-dollar',
        'route' => route('admin.bookings.index'),
    ],
    [
        'title' => 'Settings',
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
                'route' => route('admin.settings.edit', ['resource' => 'tour-inner']),
            ],
            [
                'title' => 'Social Media',
                'icon' => 'bx bx-share-alt',
                'route' => route('admin.settings.edit', ['resource' => 'social-media']),
            ],
            [
                'title' => 'Payment',
                'icon' => 'bx bx-credit-card',
                'route' => route('admin.settings.edit', ['resource' => 'payment']),
            ],
            [
                'title' => 'News',
                'icon' => 'bx bx-news',
                'route' => route('admin.settings.edit', ['resource' => 'news']),
                'status' => 'pending',
            ],
            [
                'title' => 'Blogs',
                'icon' => 'bx bx-pencil',
                'route' => route('admin.settings.edit', ['resource' => 'blogs']),
                'status' => 'pending',
            ],
            [
                'title' => 'Style',
                'icon' => 'bx bx-palette',
                'route' => route('admin.settings.edit', ['resource' => 'style']),
            ],
            [
                'title' => 'Booking',
                'icon' => 'bx bx-book',
                'route' => route('admin.settings.edit', ['resource' => 'booking']),
                'status' => 'pending',
            ],
            [
                'title' => 'Email',
                'icon' => 'bx bx-envelope',
                'route' => route('admin.settings.edit', ['resource' => 'email']),
            ],
            [
                'title' => 'Online Chat',
                'icon' => 'bx bx-chat',
                'route' => route('admin.settings.edit', ['resource' => 'online_chat']),
            ],
            [
                'title' => 'User',
                'icon' => 'bx bx-user',
                'route' => route('admin.settings.edit', ['resource' => 'user']),
                'status' => 'pending',
            ],
            [
                'title' => 'Advanced',
                'icon' => 'bx bx-wrench',
                'route' => route('admin.settings.edit', ['resource' => 'advanced']),
                'status' => 'pending',
            ],
        ],
    ],
];
