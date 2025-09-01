@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.settings.index') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Footer Quick Links</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @include('admin.settings.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <form action="{{ route('admin.settings.update', ['resource' => 'footer-quick-links']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div x-data="footerLinksRepeater()" class="form-box">
                            <div class="form-box__body">
                                <input type="hidden" name="footer_config" value="">
                                <template x-for="(block, blockIndex) in blocks" :key="blockIndex">
                                    <div class="mb-4 p-3 border rounded">
                                        <div class="form-fields d-flex justify-content-between align-items-center mb-2">
                                            <label class="title title--sm" x-text="`Block #${blockIndex + 1}`"></label>
                                            <button type="button" class="delete-btn delete-btn--static"
                                                @click="removeBlock(blockIndex)"><i class='bx bxs-trash-alt'></i></button>
                                        </div>


                                        <div class="form-fields mb-3">
                                            <label class="title text-dark">Heading</label>
                                            <input type="text" class="field"
                                                :name="`footer_config[blocks][${blockIndex}][heading]`"
                                                x-model="block.heading" placeholder="Block Heading">
                                        </div>

                                        <div class="form-fields mb-2">
                                            <label class="title text-dark">Block Type</label>
                                            <select :name="`footer_config[blocks][${blockIndex}][type]`"
                                                x-model="block.type" class="field">
                                                <option value="links">Links</option>
                                                <option value="image">Image</option>
                                            </select>
                                        </div>

                                        <template x-if="block.type === 'links'">
                                            <div class="form-fields">
                                                <label class="title">Links</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Label</th>
                                                                <th scope="col">URL</th>
                                                                <th class="text-center" scope="col">Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <template x-for="(link, linkIndex) in block.links"
                                                                :key="linkIndex">
                                                                <tr>
                                                                    <td class="p-4">
                                                                        <input type="text" class="field"
                                                                            placeholder="Label"
                                                                            :name="`footer_config[blocks][${blockIndex}][links][${linkIndex}][label]`"
                                                                            x-model="link.label">
                                                                    </td>
                                                                    <td class="p-4">
                                                                        <input type="text" class="field"
                                                                            placeholder="URL"
                                                                            :name="`footer_config[blocks][${blockIndex}][links][${linkIndex}][url]`"
                                                                            x-model="link.url">
                                                                    </td>
                                                                    <td class="text-center middle-align">
                                                                        <button type="button"
                                                                            class="delete-btn mx-auto delete-btn--static"
                                                                            @click="removeLink(blockIndex, linkIndex)">
                                                                            <i class='bx bxs-trash-alt'></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                    <div class="mt-2">
                                                        <button type="button" class="themeBtn ms-auto"
                                                            @click="addLink(blockIndex)">
                                                            Add Link <i class="bx bx-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>


                                        <!-- Image block -->
                                        <div x-show="block.type === 'image'" class="mt-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-fields">
                                                        <label class="title">Upload Image</label>
                                                        <div class="upload">
                                                            <div class="upload-box-wrapper">
                                                                <div class="upload-box" :class="!block.image ? 'show' : ''">
                                                                    <input type="file"
                                                                        :name="`footer_config[blocks][${blockIndex}][image]`"
                                                                        class="d-none" accept="image/*"
                                                                        :id="`footer_config[blocks][${blockIndex}][image]`"
                                                                        @change="onFileChange($event, blockIndex)">
                                                                    <div class="upload-box__placeholder"><i
                                                                            class='bx bxs-image'></i></div>
                                                                    <label
                                                                        :for="`footer_config[blocks][${blockIndex}][image]`"
                                                                        class="upload-box__btn themeBtn">Upload
                                                                        Image</label>
                                                                </div>
                                                                <div class="upload-box__img"
                                                                    :class="block.image ? 'show' : ''">
                                                                    <button type="button" class="delete-btn "
                                                                        @click="removeImage(blockIndex)">
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </button>
                                                                    <img :src="block.imagePreview ||
                                                                        '{{ asset('admin/assets/images/loading.webp') }}'"
                                                                        class="imgFluid">
                                                                    <input type="hidden"
                                                                        :name="`footer_config[blocks][${blockIndex}][image]`"
                                                                        :value="block.imagePreview">
                                                                    <input type="text" class="field mt-1"
                                                                        placeholder="Alt Text"
                                                                        :name="`footer_config[blocks][${blockIndex}][alt_text]`"
                                                                        x-model="block.alt_text">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" class="themeBtn ms-auto mt-3" @click="addBlock()">Add Block <i
                                        class="bx bx-plus"></i></button>
                            </div>
                        </div>

                        <button style=" position: sticky; bottom: 1rem; " class="themeBtn ms-auto">Save Changes <i
                                class="bx bx-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $default_footer_config = [
        [
            'type' => 'links',
            'heading' => '',
            'links' => [['label' => '', 'url' => '']],
            'image' => null,
            'alt_text' => '',
        ],
    ];
    $footerBlocks = $settings->get('footer_config')
        ? json_decode($settings->get('footer_config'), true)['blocks'] ?? []
        : [];
@endphp

@push('js')
    <script>
        function footerLinksRepeater() {
            return {
                blocks: @json($footerBlocks).map(block => {
                    if (block.type === 'image' && block.image) {
                        block.imagePreview = block.image;
                        block.image = block.image;
                    } else {
                        block.imagePreview = null;
                        block.image = null;
                    }
                    return block;
                }),

                addBlock() {
                    this.blocks.push({
                        type: 'links',
                        heading: '',
                        links: [{
                            label: '',
                            url: ''
                        }],
                        image: null,
                        imagePreview: null,
                        alt_text: ''
                    });
                },
                removeBlock(index) {
                    this.blocks.splice(index, 1);
                },
                addLink(blockIndex) {
                    const block = this.blocks[blockIndex];

                    if (!block.links) {
                        block.links = [];
                    }

                    block.links.push({
                        label: '',
                        url: ''
                    });
                },
                removeLink(blockIndex, linkIndex) {
                    this.blocks[blockIndex].links.splice(linkIndex, 1);
                },
                onFileChange(event, blockIndex) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.blocks[blockIndex].image = file;
                    const reader = new FileReader();
                    reader.onload = e => this.blocks[blockIndex].imagePreview = e.target.result;
                    reader.readAsDataURL(file);
                },
                removeImage(blockIndex) {
                    this.blocks[blockIndex].image = null;
                    this.blocks[blockIndex].imagePreview = null;
                    this.blocks[blockIndex].alt_text = '';
                }
            }
        }
    </script>
@endpush
