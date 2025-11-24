<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Page;
use App\Models\Blog;
use App\Models\News;
use App\Models\City;
use App\Models\Country;
use App\Models\TourCategory;
use App\Models\Setting;

class SchemaController extends Controller
{

    public function index($entity, $id, Request $request)
    {
        // Get schema type from request (for tours)
        $schemaType = $request->get('type');
        if ($entity === 'tours' && !isset($schemaType) && empty($schemaType)) {
            return redirect()->route('admin.tours.edit', $id)->with('notify_error', 'Schema type is required for tours')->with('activeTab', 'seo');
        }

        if ($entity === 'tours' && $schemaType) {
            $tour = $this->getEntityModel('tours', $id);
            $tour->update(['schema_type' => $schemaType]);
        }

        // Check if this is a listing page (saved in settings table)
        if ($id === 'listing') {
            $title = ucfirst($entity) . ' Listing Page';
            $record = null;

            $map = [
                'blogs' => 'blog',
            ];

            $listingMap = [
                'blogs' => 'blogs-listing',
            ];

            $entityName = $map[$entity] ?? $entity;
            $settingKey = $entityName . '_seo_schema';
            $schemaJson = Setting::get($settingKey);
            $schema = $schemaJson ? json_decode($schemaJson, true) ?? [] : [];
            $entity = $listingMap[$entity] ?? $entity;


            // Load countries and cities for bus tour schema
            $countriesCities = config('countries-cities');
            $currencies = config('currencies');

            return view('admin.schema.index', compact('entity', 'id', 'record', 'title', 'schema', 'countriesCities', 'currencies'));
        }

        // Regular entity record
        $record = $this->getEntityModel($entity, $id);
        $title = $this->getEntityTitle($entity, $id);

        // Get existing schema from SEO table
        $seo = $record->seo;
        $schema = [];
        if ($seo && $seo->schema) {
            $schema = json_decode($seo->schema, true) ?? [];
        }

        // Load countries and cities for bus tour schema
        $countriesCities = config('countries-cities');
        $currencies = config('currencies');

        return view('admin.schema.index', compact('entity', 'id', 'record', 'title', 'schema', 'schemaType', 'countriesCities', 'currencies'));
    }

    public function save(Request $request, $entity, $id)
    {
        // Check if schema_graph exists (from bus tour @graph format)
        if ($request->has('schema_graph') && !empty($request->input('schema_graph'))) {
            // schema_graph is already JSON string, just use it directly
            $schemaJson = $request->input('schema_graph');
        } else {
            // Get schema data from request (old format)
            $schemaData = $request->input('schema', []);
            // Convert to JSON
            $schemaJson = json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        // Check if this is a listing page (save in settings table)
        if ($id === 'listing') {
            $map = [
                'blogs' => 'blog',
                'news' => 'news',
            ];

            $entityName = $map[$entity] ?? $entity;
            $settingKey = $entityName . '_seo_schema';
            Setting::set($settingKey, $schemaJson, $entityName . '_seo');

            return redirect()->back()->with('notify_success', 'Schema saved successfully');
        }

        // Regular entity record - save in seos table
        $record = $this->getEntityModel($entity, $id);
        $record->seo()->updateOrCreate(
            ['seoable_id' => $id, 'seoable_type' => get_class($record)],
            ['schema' => $schemaJson]
        );

        return redirect()->back()->with('notify_success', 'Schema saved successfully');
    }

    protected function getEntityModel($entity, $id)
    {
        $map = [
            'tours' => Tour::class,
            'pages' => Page::class,
            'cities' => City::class,
            'countries' => Country::class,
            'tour-categories' => TourCategory::class,
            'blogs' => Blog::class,
            'news' => News::class,
        ];

        if (!isset($map[$entity])) abort(404);

        return $map[$entity]::findOrFail($id);
    }

    protected function getEntityTitle($entity, $id)
    {
        $model = $this->getEntityModel($entity, $id);

        $titleMap = [
            'tours' => 'title',
            'pages' => 'title',
            'cities' => 'name',
            'countries' => 'name',
            'tour-categories' => 'name',
            'blogs' => 'title',
            'news' => 'title',
        ];

        $key = $titleMap[$entity] ?? 'title';

        return $model->$key ?? '';
    }
}
