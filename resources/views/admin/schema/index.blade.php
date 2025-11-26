@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.schema.index', $entity, $id, $record) }}
            <form action="{{ route('admin.schema.save', ['entity' => $entity, 'id' => $id]) }}" method="POST"
                enctype="multipart/form-data" id="validation-form">
                @method('POST')
                @csrf
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading mb-0">Edit Schema: {{ isset($title) ? $title : '' }}</h3>
                        </div>
                        <button type="submit" class="themeBtn ms-auto">Save Changes<i class='bx bx-check'></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-wrapper">
                            @switch($entity)
                                @case('pages')
                                    @include('admin.schema.types.pages')
                                @break

                                @case('countries')
                                    @include('admin.schema.types.pages')
                                @break

                                @case('cities')
                                    @include('admin.schema.types.pages')
                                @break

                                @case('tour-categories')
                                    @include('admin.schema.types.pages')
                                @break

                                @case('tours')
                                    @include('admin.schema.types.tours')
                                @break

                                @case('blogs-listing')
                                    @include('admin.schema.types.pages')
                                @break

                                @case('blogs')
                                    @include('admin.schema.types.blogs')
                                @break

                                @case('news')
                                    @include('admin.schema.types.news')
                                @break

                                @default
                                    <div class="alert alert-info">
                                        Schema editor not available for this entity type.
                                    </div>
                            @endswitch
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
