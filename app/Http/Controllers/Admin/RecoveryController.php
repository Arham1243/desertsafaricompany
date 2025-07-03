<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\City;
use App\Models\Country;
use App\Models\News;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\TourAttribute;
use App\Models\TourAuthor;
use App\Models\TourCategory;
use Illuminate\Support\Facades\Redirect;

class RecoveryController extends Controller
{
    private $columnsConfig = [
        'blogs' => [
            'title' => 'Title',
            'category' => 'Category',
            'author' => 'Author',
            'deleted_at' => 'Deleted On',
        ],
        'news' => [
            'title' => 'Title',
            'category' => 'Category',
            'author' => 'Author',
            'deleted_at' => 'Deleted On',
        ],
        'countries' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'deleted_at' => 'Deleted On',
        ],
        'cities' => [
            'name' => 'Name',
            'country' => 'Country',
            'slug' => 'Slug',
            'deleted_at' => 'Deleted On',
        ],
        'tours' => [
            'title' => 'Title',
            'deleted_at' => 'Deleted On',
        ],
        'tour-categories' => [
            'name' => 'Name',
            'deleted_at' => 'Deleted On',
        ],
        'tour-authors' => [
            'name' => 'Name',
            'deleted_at' => 'Deleted On',
        ],
        'tour-attributes' => [
            'name' => 'Name',
            'deleted_at' => 'Deleted On',
        ],
        'pages' => [
            'title' => 'Title',
            'deleted_at' => 'Deleted On',
        ],
        'testimonials' => [
            'title' => 'Title',
            'deleted_at' => 'Deleted On',
        ],
    ];

    public function index($resource)
    {
        switch ($resource) {
            case 'blogs':
                $items = Blog::onlyTrashed()->get();
                break;
            case 'news':
                $items = News::onlyTrashed()->get();
                break;
            case 'countries':
                $items = Country::onlyTrashed()->get();
                break;
            case 'cities':
                $items = City::onlyTrashed()->get();
                break;
            case 'tours':
                $items = Tour::onlyTrashed()->get();
                $primaryColumn = 'id';
                break;
            case 'tour-categories':
                $items = TourCategory::onlyTrashed()->get();
                break;
            case 'tour-authors':
                $items = TourAuthor::onlyTrashed()->get();
                break;
            case 'tour-attributes':
                $items = TourAttribute::onlyTrashed()->get();
                $primaryColumn = 'id';
                break;
            case 'pages':
                $items = Page::onlyTrashed()->get();
                $primaryColumn = 'id';
                break;
            case 'testimonials':
                $items = Testimonial::onlyTrashed()->get();
                $primaryColumn = 'id';
                break;
            default:
                return Redirect::back()->with('notify_error', 'Resource not found.');
        }
        $primaryColumn = isset($primaryColumn) ? $primaryColumn : null;
        $columns = $this->columnsConfig[$resource] ?? [];
        $data = compact('items', 'resource', 'columns', 'primaryColumn');

        return view('admin.recovery-management.list')->with('title', 'Recovery')->with($data);
    }
}
