<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use App\Models\Page;
use App\Models\Popup;
use App\Models\Section;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\TourAttribute;
use App\Models\TourAuthor;
use App\Models\TourCategory;
use App\Models\TourDetailPopup;
use App\Models\TourReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BulkActionController extends Controller
{
    public function handle(Request $request, $resource)
    {
        $action = $request->input('bulk_actions');
        $selectedIds = $request->input('bulk_select', []);
        if (empty($selectedIds)) {
            return Redirect::back()->with('notify_error', 'No items selected for the bulk action.');
        }

        $isParent = false;
        switch ($resource) {
            case 'blogs':
                $modelClass = Blog::class;
                $column = 'id';
                $redirectRoute = 'admin.blogs.index';
                break;
            case 'blogs-tags':
                $modelClass = BlogTag::class;
                $column = 'id';
                $redirectRoute = 'admin.blogs-tags.index';
                break;
            case 'blogs-categories':
                $modelClass = BlogCategory::class;
                $column = 'id';
                $redirectRoute = 'admin.blogs-categories.index';
                $isParent = true;
                break;
            case 'news':
                $modelClass = News::class;
                $column = 'id';
                $redirectRoute = 'admin.news.index';
                break;
            case 'news-tags':
                $modelClass = NewsTag::class;
                $column = 'id';
                $redirectRoute = 'admin.news-tags.index';
                break;
            case 'news-categories':
                $modelClass = NewsCategory::class;
                $column = 'id';
                $redirectRoute = 'admin.news-categories.index';
                $isParent = true;
                break;
            case 'countries':
                $modelClass = Country::class;
                $column = 'id';
                $redirectRoute = 'admin.countries.index';
                break;
            case 'cities':
                $modelClass = City::class;
                $column = 'id';
                $redirectRoute = 'admin.cities.index';
                break;
            case 'tours':
                $modelClass = Tour::class;
                $column = 'id';
                $redirectRoute = 'admin.tours.index';
                break;
            case 'tour-categories':
                $modelClass = TourCategory::class;
                $column = 'id';
                $redirectRoute = 'admin.tour-categories.index';
                $isParent = true;
                break;
            case 'tour-authors':
                $modelClass = TourAuthor::class;
                $column = 'id';
                $redirectRoute = 'admin.tour-authors.index';
                break;
            case 'tour-popups':
                $modelClass = TourDetailPopup::class;
                $column = 'id';
                $redirectRoute = 'admin.tour-popups.index';
                break;
            case 'tour-attributes':
                $modelClass = TourAttribute::class;
                $column = 'id';
                $redirectRoute = 'admin.tour-attributes.index';
                break;
            case 'tour-reviews':
                $modelClass = TourReview::class;
                $column = 'id';
                $redirectRoute = 'admin.tour-reviews.index';
                break;
            case 'pages':
                $modelClass = Page::class;
                $column = 'id';
                $redirectRoute = 'admin.pages.index';
                break;
            case 'sections':
                $modelClass = Section::class;
                $column = 'id';
                $redirectRoute = 'admin.sections.index';
                break;
            case 'testimonials':
                $modelClass = Testimonial::class;
                $column = 'id';
                $redirectRoute = 'admin.testimonials.index';
                break;
            case 'popups':
                $modelClass = Popup::class;
                $column = 'id';
                $redirectRoute = 'admin.popups.index';
                break;
            case 'coupons':
                $modelClass = Coupon::class;
                $column = 'id';
                $redirectRoute = 'admin.coupons.index';
                break;
            default:
                return Redirect::back()->with('notify_error', 'Resource not found.');
        }

        return $this->handleBulkActions($modelClass, $column, $action, $selectedIds, $redirectRoute, $isParent);
    }

    protected function handleBulkActions($modelClass, $idColumn, $action, $selectedIds, $redirectRoute, $isParent = false)
    {
        switch ($action) {
            case 'delete':
                $modelClass::whereIn($idColumn, $selectedIds)->each(function ($model) use ($modelClass, $isParent) {
                    if ($isParent) {
                        $modelClass::where('parent_category_id', $model->id)
                            ->update(['parent_category_id' => null]);
                    }

                    $model->delete();
                });
                break;
            case 'draft':
                $modelClass::whereIn($idColumn, $selectedIds)->update(['status' => 'draft']);
                break;
            case 'publish':
                $modelClass::whereIn($idColumn, $selectedIds)->update(['status' => 'publish']);
                break;
            case 'restore':
                $modelClass::withTrashed()->whereIn($idColumn, $selectedIds)->each(function ($model) {
                    $model->restore();
                });
                break;
            case 'active':
                $modelClass::whereIn($idColumn, $selectedIds)->update(['status' => 'active']);
                break;
            case 'inactive':
                $modelClass::whereIn($idColumn, $selectedIds)->update(['status' => 'inactive']);
                break;
            case 'permanent_delete':
                $modelClass::onlyTrashed()->whereIn($idColumn, $selectedIds)->each(function ($model) use ($modelClass, $isParent) {
                    if ($isParent) {
                        $modelClass::where('parent_category_id', $model->id)
                            ->update(['parent_category_id' => null]);
                    }

                    $model->forceDelete();
                });
                break;
            default:
                break;
        }

        return redirect()->route($redirectRoute)->with('notify_success', 'Bulk action performed successfully!');
    }
}
