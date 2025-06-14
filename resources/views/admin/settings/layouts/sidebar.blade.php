@php
    $settingsItems = [
        [
            'route' => 'general',
            'icon' => 'bx-cog',
            'label' => 'General Settings',
        ],
        [
            'route' => 'tour',
            'icon' => 'bx-world',
            'label' => 'Tour',
        ],
        [
            'route' => 'news',
            'icon' => 'bx-news',
            'label' => 'News',
            'status' => 'pending',
        ],
        [
            'route' => 'blog',
            'icon' => 'bx-pencil',
            'label' => 'Blog',
            'status' => 'pending',
        ],
        [
            'route' => 'media',
            'icon' => 'bx-image',
            'label' => 'Media',
            'status' => 'pending',
        ],
        [
            'route' => 'style',
            'icon' => 'bx-palette',
            'label' => 'Style',
            'status' => 'pending',
        ],
        [
            'route' => 'review',
            'icon' => 'bx-star',
            'label' => 'Review',
            'status' => 'pending',
        ],
        [
            'route' => 'booking',
            'icon' => 'bx-book',
            'label' => 'Booking',
            'status' => 'pending',
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
            'status' => 'pending',
        ],
        [
            'route' => 'enquiry',
            'icon' => 'bx-message-square-dots',
            'label' => 'Enquiry',
            'status' => 'pending',
        ],
        [
            'route' => 'sms',
            'icon' => 'bx-message',
            'label' => 'SMS',
            'status' => 'pending',
        ],
        [
            'route' => 'email',
            'icon' => 'bx-envelope',
            'label' => 'Email',
            'status' => 'pending',
        ],
        [
            'route' => 'online_chat',
            'icon' => 'bx-chat',
            'label' => 'Online Chat',
            'status' => 'pending',
        ],
        [
            'route' => 'user',
            'icon' => 'bx-user',
            'label' => 'User',
            'status' => 'pending',
        ],
        [
            'route' => 'vendor',
            'icon' => 'bx-store',
            'label' => 'Vendor',
            'status' => 'pending',
        ],
        [
            'route' => 'advanced',
            'icon' => 'bx-wrench',
            'label' => 'Advanced',
            'status' => 'pending',
        ],
        [
            'route' => 'mobile_app',
            'icon' => 'bx-mobile-alt',
            'label' => 'Mobile App',
            'status' => 'pending',
        ],
        [
            'route' => 'invoice',
            'icon' => 'bx-file',
            'label' => 'Invoice',
            'status' => 'pending',
        ],
        [
            'route' => 'quotations',
            'icon' => 'bx-file',
            'label' => 'Quotations',
            'status' => 'pending',
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
                        @if (isset($item['status']) && $item['status'] === 'pending')
                            <span class="badge badge-sm rounded-pill bg-warning d-inline-block ms-auto">pending</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
