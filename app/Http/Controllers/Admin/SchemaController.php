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
use App\Models\Schema;

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

        // Check if this is a listing page
        if ($id === 'listing') {
            $title = ucfirst($entity) . ' Listing Page';
            $record = null;

            $listingMap = [
                'blogs' => 'blogs-listing',
            ];

            $mappedEntity = $listingMap[$entity] ?? $entity;

            // Get schema from schemas table
            $schema = Schema::getSchema('listing', $mappedEntity);

            // Load countries and cities for bus tour schema
            $countriesCities = config('countries-cities');
            $currencies = config('currencies');
            $languages = config('languages');

            return view('admin.schema.index', compact('entity', 'id', 'record', 'title', 'schema', 'countriesCities', 'currencies', 'languages'));
        }

        // Regular entity record
        $record = $this->getEntityModel($entity, $id);
        $title = $this->getEntityTitle($entity, $id);

        // Get existing schema from schemas table
        $schema = Schema::getSchema($entity, $id, $schemaType);

        // Always load global Local Business schema from settings (not editable per page)
        $globalLocalBusinessJson = Setting::get('global_local_business_schema');
        $globalLocalBusiness = $globalLocalBusinessJson ? json_decode($globalLocalBusinessJson, true) : [];

        // Always use global Local Business schema
        if (isset($globalLocalBusiness['localBusiness'])) {
            $schema['localBusiness'] = $globalLocalBusiness['localBusiness'];
        }

        // Auto-populate tour-specific fields for tour entities
        if ($entity === 'tours' && $record) {
            // Auto-populate name and url for tour types (boat, bus, inner, water)
            $tourTypeFields = ['bus-trip', 'boat-trip', 'inner-page', 'water-activity'];
            foreach ($tourTypeFields as $field) {
                if (!isset($schema[$field]['name']) || empty($schema[$field]['name'])) {
                    $schema[$field]['name'] = $record->title ?? '';
                }
                if (!isset($schema[$field]['url']) || empty($schema[$field]['url'])) {
                    $schema[$field]['url'] = $record->detail_url ?? '';
                }
            }

            // Auto-populate FAQ from tour FAQs if schema FAQ is empty
            if ((!isset($schema['faq']['mainEntity']) || empty($schema['faq']['mainEntity'])) && $record->faqs && $record->faqs->isNotEmpty()) {
                $schema['faq']['mainEntity'] = $record->faqs->map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq->question ?? '',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq->answer ?? ''
                        ]
                    ];
                })->toArray();
            }
        }

        // Load countries and cities for bus tour schema
        $countriesCities = config('countries-cities');
        $currencies = config('currencies');
        $languages = config('languages');

        return view('admin.schema.index', compact('entity', 'id', 'record', 'title', 'schema', 'schemaType', 'countriesCities', 'currencies', 'languages'));
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

        // Get schema type for tours
        $schemaType = $request->get('type');

        // Check if this is a listing page
        if ($id === 'listing') {
            $listingMap = [
                'blogs' => 'blogs-listing',
            ];

            $mappedEntity = $listingMap[$entity] ?? $entity;

            // Save to schemas table
            Schema::saveSchema('listing', $mappedEntity, $schemaJson);

            return redirect()->back()->with('notify_success', 'Schema saved successfully');
        }

        // Regular entity record - save to schemas table
        Schema::saveSchema($entity, $id, $schemaJson, $schemaType);

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
