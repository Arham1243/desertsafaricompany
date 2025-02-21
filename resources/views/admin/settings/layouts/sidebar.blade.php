@php
    $settingsItems = [
        // General Settings
        [
            'route' => 'general',
            'icon' => 'bx-cog',
            'label' => 'General Settings',
        ],

        // Content Settings
        [
            'route' => 'tour',
            'icon' => 'bx-world',
            'label' => 'Tour',
        ],
        [
            'route' => 'news',
            'icon' => 'bx-news',
            'label' => 'News',
        ],
        [
            'route' => 'blog',
            'icon' => 'bx-pencil',
            'label' => 'Blog',
        ],
        [
            'route' => 'media',
            'icon' => 'bx-image',
            'label' => 'Media',
        ],
        [
            'route' => 'style',
            'icon' => 'bx-palette',
            'label' => 'Style',
        ],
        [
            'route' => 'review',
            'icon' => 'bx-star',
            'label' => 'Review',
        ],

        // Booking and Payment
        [
            'route' => 'booking',
            'icon' => 'bx-book',
            'label' => 'Booking',
        ],
        [
            'route' => 'payment',
            'icon' => 'bx-credit-card',
            'label' => 'Payment',
        ],
        [
            'route' => 'voucher',
            'icon' => 'bx-gift',
            'label' => 'Voucher',
        ],

        // Communication Settings
        [
            'route' => 'enquiry',
            'icon' => 'bx-message-square-dots',
            'label' => 'Enquiry',
        ],
        [
            'route' => 'sms',
            'icon' => 'bx-message',
            'label' => 'SMS',
        ],
        [
            'route' => 'email',
            'icon' => 'bx-envelope',
            'label' => 'Email',
        ],
        [
            'route' => 'online_chat',
            'icon' => 'bx-chat',
            'label' => 'Online Chat',
        ],

        // User and Vendor Management
        [
            'route' => 'user',
            'icon' => 'bx-user',
            'label' => 'User',
        ],
        [
            'route' => 'vendor',
            'icon' => 'bx-store',
            'label' => 'Vendor',
        ],

        // Advanced and Technical Settings
        [
            'route' => 'advanced',
            'icon' => 'bx-wrench',
            'label' => 'Advanced',
        ],
        [
            'route' => 'mobile_app',
            'icon' => 'bx-mobile-alt',
            'label' => 'Mobile App',
        ],
        [
            'route' => 'invoice',
            'icon' => 'bx-file',
            'label' => 'Invoice',
        ],
        [
            'route' => 'quotations',
            'icon' => 'bx-file',
            'label' => 'Quotations',
        ],
        [
            'route' => 'social_media',
            'icon' => 'bx-share-alt',
            'label' => 'Social Media',
        ],
    ];

@endphp


<div class="form-box">
    <div class="form-box__header">
        <div class="title"><i class='bx bx-cog'></i>Settings </div>
    </div>
    <div class="form-box__body p-0">
        <ul class="settings">
            @foreach ($settingsItems as $item)
                <li class="settings-item">
                    <a href="{{ route('admin.settings.edit', ['resource' => $item['route']]) }}"
                        class="settings-item__link {{ Route::is('admin.settings.edit') && request()->route('resource') == $item['route'] ? 'active' : '' }}">
                        <i class="bx {{ $item['icon'] }}"></i> {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
