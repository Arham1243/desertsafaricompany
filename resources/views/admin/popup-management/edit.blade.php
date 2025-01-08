@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.popups.edit', $item) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.popups.update', $item->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Popup Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Title <span class="text-danger">*</span> :</label>
                                        <input type="text" name="title" class="field"
                                            value="{{ old('title', $item->title) }}" placeholder="" data-error="Title">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Content <span class="text-danger">*</span> :</label>
                                        <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                            {{ old('content', $item->content) }}
                                        </textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title title--sm">Include URLs :</label>

                                        <div class="repeater-table" x-data="{
                                            urls: {{ $item->included_pages ? json_encode(json_decode($item->included_pages, true)) : '[]' }}
                                        }">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Url</th>
                                                        <th class="text-end" scope="col">Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(url, index) in urls" :key="index">
                                                        <tr>
                                                            <td>
                                                                <div class="input-group flex-nowrap">
                                                                    <span
                                                                        class="input-group-text">{{ url('/') }}/</span>
                                                                    <input name="included_pages[]" type="text"
                                                                        class="field" x-model="url">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button type="button" :disabled="urls.length <= 1"
                                                                    class="delete-btn ms-auto delete-btn--static"
                                                                    @click="urls.length > 1 && urls.splice(index, 1)">
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                            <button type="button" class="themeBtn ms-auto" @click="urls.push('')">
                                                Add <i class="bx bx-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="seo-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Publish</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="active"
                                            {{ $item->status == 'active' ? 'checked' : '' }} value="active">
                                        <label class="form-check-label" for="active">
                                            active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            {{ $item->status == 'inactive' ? 'checked' : '' }} value="inactive">
                                        <label class="form-check-label" for="inactive">
                                            inactive
                                        </label>
                                    </div>
                                    <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
