@extends('admin.layouts.main')

@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-attributes.index') }}
            <div class="section-content mt-2 mb-3">
                <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
            </div>
            <div class="row">
                <div class="col-md-4">
                    @include('admin.tours.attributes.add')
                </div>
                <div class="col-md-8">
                    @include('admin.tours.attributes.list')
                </div>
            </div>
        </div>
    </div>
@endsection
