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
                'title' => 'Time Categories',
                'icon' => 'bx bx-time',
                'submenu' => [
                    [
                        'title' => 'Time Categories',
                        'icon' => 'bx bx-list-ul',
                        'route' => route('admin.tour-times.index'),
                    ],
                    [
                        'title' => 'Recovery',
                        'icon' => 'bx bx-refresh',
                        'route' => route('admin.recovery.index', ['resource' => 'tour-times']),
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
        ],
    ],
];
