@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tours.create') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Settings</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @include('admin.settings.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <form action="{{ route('admin.settings.update', ['resource' => 'tour']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $selectedBannerStyle = $settings->get('banner_style');
                            $perks = $settings->get('perks');
                        @endphp
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Banner Settings</div>
                            </div>
                            <div class="form-box__body">
                                <div class="d-flex align-items-center justify-content-center gap-5 px-4">
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" name="banner_style" id="style-1"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-1' ? 'checked' : '') : '' }}
                                            value="style-1" checked />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-1">Style
                                            1
                                            <a href="{{ asset('admin/assets/images/banner-styles/1.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" id="style-2" name="banner_style"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-2' ? 'checked' : '') : '' }}
                                            value="style-2" />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-2">Style
                                            2
                                            <a href="{{ asset('admin/assets/images/banner-styles/2.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" id="style-3" name="banner_style"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-3' ? 'checked' : '') : '' }}
                                            value="style-3" />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-3">Style
                                            3
                                            <a href="{{ asset('admin/assets/images/banner-styles/3.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" id="style-4" name="banner_style"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-4' ? 'checked' : '') : '' }}
                                            value="style-4" />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-4">Style
                                            4
                                            <a href="{{ asset('admin/assets/images/banner-styles/4.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Why Book With Us?</div>
                            </div>
                            <div class="form-box__body">
                                <div class="form-fields">
                                    <div x-data="featuresManager">
                                        <div class="repeater-table">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">
                                                            <div class="d-flex align-items-center gap-2">
                                                                Icon:
                                                                <a class="p-0 nav-link" href="//boxicons.com"
                                                                    target="_blank">boxicons</a>
                                                            </div>
                                                        </th>
                                                        <th scope="col">
                                                            <div class="d-flex align-items-center gap-2">
                                                                Icon Color:
                                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                    target="_blank">Color Codes</a>
                                                            </div>
                                                        </th>
                                                        <th scope="col">Title</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(feature, index) in features" :key="index">
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <input type="text" class="field"
                                                                        :name="`perks[${index}][icon]`"
                                                                        x-model="feature.icon"
                                                                        @input="$el.nextElementSibling.className = feature.icon"
                                                                        placeholder="Enter icon class">
                                                                    <i style="font-size: 1.5rem"
                                                                        :class="` ${feature.icon}  `" data-preview-icon></i>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="field color-picker" data-color-picker-container>
                                                                    <label :for="`icon-color-picker-${index}`"
                                                                        data-color-picker></label>
                                                                    <input type="text" data-color-picker-input
                                                                        :id="`icon-color-picker-${index}`"
                                                                        :name="`perks[${index}][icon_color]`"
                                                                        x-model="feature.icon_color"
                                                                        placeholder="Enter color code">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" :name="`perks[${index}][title]`"
                                                                    x-model="feature.title" class="field" maxlength="50"
                                                                    placeholder="Enter title">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="delete-btn ms-auto delete-btn--static"
                                                                    @click="removeFeature(index)">
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                            <button type="button" class="themeBtn ms-auto" @click="addFeature">
                                                Add <i class="bx bx-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="themeBtn ms-auto ">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function featuresManager() {
            return {
                features: @if ($perks)
                    @js(json_decode($perks))
                @else
                    [{
                        icon: '',
                        icon_color: '',
                        title: '',
                    }]
                @endif ,
                addFeature() {
                    this.features.push({
                        icon: '',
                        icon_color: '',
                        title: '',
                    });
                    this.$nextTick(() => {
                        document.querySelectorAll("[data-color-picker-container]").forEach(el => {
                            InitializeColorPickers(el);
                        });
                    });
                },
                removeFeature(index) {
                    this.features.splice(index, 1);
                }
            };
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
@endpush
