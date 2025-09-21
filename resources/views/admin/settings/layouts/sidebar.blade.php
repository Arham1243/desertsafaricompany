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
            'label' => 'Tour ',
            'children' => [
                [
                    'route' => 'tour-inner',
                    'label' => 'Inner page',
                ],
                [
                    'route' => 'tour-category',
                    'label' => 'Category Page',
                ],
            ],
        ],
        [
            'route' => 'social-media',
            'icon' => 'bx-share-alt',
            'label' => 'Social Media',
        ],
        [
            'route' => 'payment',
            'icon' => 'bx-credit-card',
            'label' => 'Payment',
        ],
        [
            'route' => 'news',
            'icon' => 'bx-news',
            'label' => 'News',
        ],
        [
            'route' => 'contact-us',
            'icon' => 'bx-phone',
            'label' => 'Contact Page',
        ],
        [
            'route' => 'blogs',
            'icon' => 'bx-pencil',
            'label' => 'Blogs',
        ],
        [
            'route' => 'style',
            'icon' => 'bx-palette',
            'label' => 'Style',
        ],
        [
            'route' => 'header-menu',
            'icon' => 'bx bx-menu',
            'label' => 'Header Menu Items',
        ],
        [
            'route' => 'footer-quick-links',
            'icon' => 'bx bx-link',
            'label' => 'Footer Quick Links',
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
        [
            'route' => 'user',
            'icon' => 'bx-user',
            'label' => 'User',
        ],
        [
            'route' => 'advanced',
            'icon' => 'bx-wrench',
            'label' => 'Advanced',
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
                @php
                    $isChildActive =
                        isset($item['children']) &&
                        collect($item['children'])->contains(function ($child) {
                            return Route::is('admin.settings.edit') && request()->route('resource') === $child['route'];
                        });
                @endphp

                @if (isset($item['children']))
                    <li class="settings-item custom-dropdown {{ $isChildActive ? 'open' : '' }}">
                        <a href="javascript:void(0)" class="settings-item__link custom-dropdown__active">
                            <div class="info d-flex align-items-center gap-2">
                                <i class="bx {{ $item['icon'] }}"></i> {{ $item['label'] }}
                            </div>
                            <div class="icon" style="font-size:1.25rem;">
                                <i class="bx bx-chevron-down"></i>
                            </div>
                        </a>
                        <div class="custom-dropdown__values">
                            <ul class="values-wrapper">
                                @foreach ($item['children'] as $child)
                                    <li class="custom-dropdown custom-dropdown--sub">
                                        <a href="{{ route('admin.settings.edit', ['resource' => $child['route']]) }}"
                                            class="settings-item__link {{ Route::is('admin.settings.edit') && request()->route('resource') == $child['route'] ? 'active' : '' }}">
                                            <div class="info d-flex align-items-center gap-2"> <i class="bx bx-cog"></i>
                                                {{ $child['label'] }}</div>

                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="settings-item">
                        <a href="{{ route('admin.settings.edit', ['resource' => $item['route']]) }}"
                            class="settings-item__link {{ Route::is('admin.settings.edit') && request()->route('resource') == $item['route'] ? 'active' : '' }}">
                            <i class="bx {{ $item['icon'] }}"></i> {{ $item['label'] }}
                            @if (isset($item['status']) && $item['status'] === 'pending')
                                <span
                                    class="badge badge-sm rounded-pill bg-warning d-inline-block ms-auto">pending</span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
