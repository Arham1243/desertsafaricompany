@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.settings.index') }}
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
                    <form action="{{ route('admin.settings.update', ['resource' => 'header-menu']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="title">Header Menu</label>
                                </div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm">Main menu items</label>
                                            <div x-data="menuRepeater()" x-init="initSortable()" class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" scope="col">Order</th>
                                                            <th scope="col">Menu</th>
                                                            <th class="text-center" scope="col">Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <input type="hidden" value="[]" name="header_menu">
                                                    <tbody x-ref="sortableBody">
                                                        <template x-for="(item, index) in items" :key="index">
                                                            <tr>
                                                                <td class="middle-align">
                                                                    <div class="order-menu text-center"><i
                                                                            class='bx-sm bx bx-menu'></i></div>
                                                                    <input type="hidden"
                                                                        :name="`header_menu[${index}][order]`"
                                                                        :value="index + 1" x-ref="orderInput">
                                                                </td>
                                                                <td>
                                                                    <div class="p-3">
                                                                        <div class="form-fields">
                                                                            <label class="title">Menu <span class="ms-1"
                                                                                    x-text="`#${index + 1}`"></span></label>
                                                                            <input :name="`header_menu[${index}][name]`"
                                                                                type="text" class="field"
                                                                                x-model="item.name">
                                                                        </div>
                                                                        <div class="form-fields">
                                                                            <label class="title">URL <span class="ms-1"
                                                                                    x-text="`#${index + 1}`"></span></label>
                                                                            <input :name="`header_menu[${index}][url]`"
                                                                                type="text" class="field"
                                                                                x-model="item.url">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="middle-align">
                                                                    <button type="button"
                                                                        class="delete-btn mx-auto delete-btn--static"
                                                                        @click="removeItem(index)">
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                                <div class="mt-2">
                                                    <template x-if="items.length < 5">
                                                        <button type="button" class="themeBtn ms-auto" @click="addItem()">
                                                            Add <i class="bx bx-plus"></i>
                                                        </button>
                                                    </template>
                                                    <template x-if="items.length >= 5">
                                                        <p class="text-danger text-end mt-2">Header only supports 5 menu
                                                            items
                                                        </p>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('is_enabled_login_button') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">Login Button Settings</div>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="is_enabled_login_button" :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox" id="enable-login_button"
                                            x-model="enabled">
                                        <label class="form-check-label" for="enable-login_button"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box__body" x-show="enabled" x-transition>
                                <div class="row">
                                    @if ((int) $settings->get('is_registration_enabled') === 0)
                                        <p class="text-danger mb-2">
                                            You need to enable user registration to show the login button.
                                            Check it here:
                                            <a href="{{ route('admin.settings.edit', ['resource' => 'user']) }}"
                                                class="custom-link" target="_blank" style="font: inherit;">
                                                User Settings
                                            </a>
                                        </p>
                                    @endif


                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Login Buttton
                                                <a href="{{ asset('admin/assets/images/header-login-button.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Button Text & Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="header-color-picker" data-color-picker></label>
                                                <input id="header-color-picker" type="hidden"
                                                    name="login_button_text_color" data-color-picker-input
                                                    value="{{ $settings->get('login_button_text_color') ?? '#f5f5ff' }}"
                                                    data-error="Heading Color" inputmode="text">

                                                <input type="text" name="login_button_text"
                                                    value="{{ $settings->get('login_button_text') ?? 'Login or Signup' }}"
                                                    maxlength="15">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Background Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="login-button-bg-color-picker" data-color-picker></label>
                                                <input id="login-button-bg-color-picker" type="text"
                                                    data-color-picker-input name="login_button_bg_color"
                                                    value="{{ $settings->get('login_button_bg_color') ?? '#1c4d99' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button style=" position: sticky; bottom: 1rem; " class="themeBtn ms-auto ">Save Changes <i
                                class="bx bx-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php
        $header_menu = getSortedHeaderMenu($settings->get('header_menu'));
    @endphp
@endsection
@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        function menuRepeater() {
            return {
                items: @js($header_menu ?: [['name' => '', 'url' => '']]),
                addItem() {
                    this.items.push({
                        name: '',
                        url: ''
                    });
                    this.$nextTick(() => this.updateOrderFields());
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                    this.$nextTick(() => this.updateOrderFields());
                },
                initSortable() {
                    const el = this.$refs.sortableBody;
                    new Sortable(el, {
                        animation: 200,
                        handle: '.order-menu',
                        onEnd: () => this.updateOrderFields()
                    });
                },
                updateOrderFields() {
                    this.$refs.sortableBody.querySelectorAll('tr').forEach((tr, idx) => {
                        const input = tr.querySelector('input[type="hidden"]');
                        if (input) input.value = idx + 1;
                    });
                }
            }
        }
    </script>
@endpush
