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
                [
                    'route' => 'tour-search',
                    'label' => 'Search Page',
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
            'status' => 'pending',
        ],
        [
            'route' => 'blog',
            'icon' => 'bx-pencil',
            'label' => 'Blog',
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
            'route' => 'enquiry',
            'icon' => 'bx-message-square-dots',
            'label' => 'Enquiry',
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
            'route' => 'advanced',
            'icon' => 'bx-wrench',
            'label' => 'Advanced',
            'status' => 'pending',
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
