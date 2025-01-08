@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.pages.edit', $page) }}
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit Page: {{ isset($title) ? $title : '' }}</h3>
                            <div class="permalink">
                                <div class="title">Permalink:</div>
                                <div class="title">
                                    <div class="full-url">{{ buildUrl(url('/'), 'page/') }}</div>
                                    <input value="{{ $page->slug ?? '#' }}" type="button" class="link permalink-input"
                                        data-field-id="slug">
                                    <input type="hidden" id="slug" value="{{ $page->slug ?? '#' }}" name="slug">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('admin.pages.page-builder', $page->id) }}" class="themeBtn">Page Builder</a>
                            <a href="{{ buildUrl(url('/'), 'page', $page->slug) }}?viewer=admin" target="_blank"
                                class="themeBtn">View
                                Page</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Page Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Title <span class="text-danger">*</span> :</label>
                                        <input type="text" name="title" class="field"
                                            value="{{ old('title', $page->title) }}" placeholder="New Blog"
                                            data-error="Title">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-fields">
                                        <label class="title">Content <span class="text-danger">*</span> :</label>
                                        <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                            {!! old('content', $page->content) !!}
                                        </textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <x-seo-options :seo="$seo ?? null" :resource="'page'" :slug="$page->slug" />
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
                                        <input class="form-check-input" type="radio" name="status" id="publish"
                                            value="publish"
                                            {{ old('status', $page->status ?? '') == 'publish' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="draft"
                                            value="draft"
                                            {{ old('status', $page->status ?? '') == 'draft' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="draft">
                                            Draft
                                        </label>
                                    </div>
                                    <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Header / footer Style </div>
                                </div>
                                @php
                                    $headerStyles = ['normal'];
                                    $footerStyles = ['normal'];
                                @endphp
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Header <span class="text-danger">*</span> :</label>
                                        <select name="header_style" class="field" data-error="Header Style">
                                            <option value="" selected>Select</option>
                                            @foreach ($headerStyles as $headerStyle)
                                                <option value="{{ $headerStyle }}"
                                                    {{ $page->header_style == $headerStyle ? 'selected' : '' }}>
                                                    {{ $headerStyle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('header_style')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Footer <span class="text-danger">*</span> :</label>
                                        <select name="footer_style" class="field" data-error="Footer Style">
                                            <option value="" selected>Select</option>
                                            @foreach ($footerStyles as $footerStyle)
                                                <option value="{{ $footerStyle }}"
                                                    {{ $page->footer_style == $footerStyle ? 'selected' : '' }}>
                                                    {{ $footerStyle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('footer_style')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
