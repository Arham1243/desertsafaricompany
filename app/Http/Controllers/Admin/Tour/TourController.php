<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Tour;
use App\Models\TourAttribute;
use App\Models\TourCategory;
use App\Models\TourDetail;
use App\Models\TourFaq;
use App\Models\TourItinerary;
use App\Models\TourPricing;
use App\Models\User;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class TourController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    public function index()
    {
        $tours = Tour::with(['category'])->latest()->get();

        return view('admin.tours.tours-management.list', compact('tours'))->with('title', 'All Tours');
    }

    public function create()
    {

        $categories = TourCategory::where('status', 'publish')->latest()->get();

        $tours = Tour::all();
        $users = User::where('is_active', 1)->get();
        $attributes = TourAttribute::where('status', 'active')
            ->latest()->get();

        $cities = City::where('status', 'publish')->get();
        $data = compact(
            'categories',
            'cities',
            'attributes',
            'tours',
            'users'
        );

        return view('admin.tours.tours-management.add', $data)->with('title', 'Add New Tour');
    }

    public function store(Request $request)
    {
        $general = $request->input('tour.general', []);
        $badge = $request->input('tour.badge', []);
        $statusTab = $request->input('tour.status', []);
        $availabilityData = $request->input('tour.availability', []);
        $pricing = $request->input('tour.pricing', []);
        $location = $request->input('tour.location', []);
        $itineraryExperience = $request->input('itinerary_experience', []);

        $slugText = ! empty($general['slug']) ? $general['slug'] : $general['title'];
        $slug = $this->createSlug($slugText, 'tours');

        $inclusions = ! empty($general['inclusions']) && ! in_array(null, $general['inclusions'], true)
            ? json_encode(array_filter($general['inclusions'], fn ($value) => $value !== null))
            : null;

        $exclusions = ! empty($general['exclusions']) && ! in_array(null, $general['exclusions'], true)
            ? json_encode(array_filter($general['exclusions'], fn ($value) => $value !== null))
            : null;

        $features = ! empty($general['features']) && ! in_array(null, $general['features'], true)
            ? json_encode(array_filter($general['features'], fn ($value) => $value !== null))
            : null;

        $relatedTours = ! empty($request->input('related_tour_ids')) ? json_encode($request->input('related_tour_ids')) : null;
        $extraPrices = ! empty($pricing['extra_price']) ? json_encode($pricing['extra_price']) : null;
        $discounts = ! empty($pricing['discount']) ? json_encode($pricing['discount']) : null;
        $availabilityOpenHours = ! empty($availabilityData['open_hours']) ? json_encode($availabilityData['open_hours']) : null;
        $badge = ! empty($request->input('tour.badge')) ? json_encode($request->input('tour.badge')) : null;

        $tour = Tour::create([
            'title' => $general['title'] ?? null,
            'slug' => $slug ?? null,
            'badge' => $badge ?? null,
            'content' => $general['content'] ?? null,
            'category_id' => $general['category_id'] ?? null,
            'banner_image_alt_text' => $request->input('banner_image_alt_text'),
            'featured_image_alt_text' => $request->input('featured_image_alt_text'),
            'promotional_image_alt_text' => $request->input('promotional_image_alt_text'),
            'banner_type' => $general['banner_type'] ?? null,
            'video_link' => $general['video_link'] ?? null,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'features' => $features,
            'status' => $statusTab['status'],
            'author_id' => $statusTab['author_id'],
            'is_featured' => $statusTab['is_featured'] ?? 0,
            'featured_state' => $statusTab['featured_state'] ?? null,
            'ical_import_url' => $statusTab['ical_import_url'] ?? null,
            'ical_export_url' => $statusTab['ical_export_url'] ?? null,
            'related_tour_ids' => $relatedTours,
            'is_fixed_date' => $availabilityData['is_fixed_date'] ?? 0,
            'is_open_hours' => $availabilityData['is_open_hours'] ?? 0,
            'is_fixed_date' => $availabilityData['is_fixed_date'] ?? 0,
            'start_date' => $availabilityData['start_date'],
            'end_date' => $availabilityData['end_date'] ?? null,
            'last_booking_date' => $availabilityData['last_booking_date'],
            'regular_price' => $pricing['regular_price'] ?? null,
            'sale_price' => $pricing['sale_price'] ?? null,
            'is_person_type_enabled' => $pricing['is_person_type_enabled'] ?? 0,
            'price_type' => isset($pricing['is_person_type_enabled']) && $pricing['is_person_type_enabled'] == 1 ? $pricing['price_type'] : null,
            'is_extra_price_enabled' => $pricing['is_extra_price_enabled'] ?? 0,
            'extra_prices' => $extraPrices ?? null,
            'enabled_custom_service_fee' => $pricing['enabled_custom_service_fee'] ?? 0,
            'service_fee_price' => $pricing['service_fee_price'] ?? null,
            'show_phone' => $pricing['show_phone'] ?? 0,
            'phone_country_code' => $pricing['phone_country_code'] ?? null,
            'phone_dial_code' => $pricing['phone_dial_code'] ?? null,
            'phone_number' => $pricing['phone_number'] ?? null,
            'address' => $location['normal_location']['address'] ?? null,
            'location_type' => $location['location_type'] ?? null,
            'itinerary_experience' => json_encode($itineraryExperience) ?? null,
            'discount_by_number_of_people' => $discounts ?? null,
            'availability_open_hours' => $availabilityOpenHours ?? null,
        ]);

        if (isset($general['faq']['question']) && is_array($general['faq']['question'])) {
            foreach ($general['faq']['question'] as $index => $question) {
                $answer = $general['faq']['answer'][$index] ?? null;

                if (! empty($question) && ! empty($answer)) {
                    TourFaq::create([
                        'question' => $question,
                        'answer' => $answer,
                        'tour_id' => $tour->id,
                    ]);
                }
            }
        }

        if (! empty($statusTab['attributes'])) {
            foreach ($statusTab['attributes'] as $attributeId => $itemIds) {
                foreach ($itemIds as $itemId) {
                    $tour->attributes()->attach($attributeId, ['tour_attribute_item_id' => $itemId]);
                }
            }
        }

        if (! empty($general['details'])) {
            foreach ($general['details'] as $detail) {
                if ($detail['name'] !== null && ! empty($detail['items'])) {
                    TourDetail::create([
                        'tour_id' => $tour->id,
                        'name' => $detail['name'],
                        'items' => $detail['items'],
                        'urls' => $detail['urls'],
                    ]);
                }
            }
        }

        if (isset($pricing['is_person_type_enabled']) && $pricing['is_person_type_enabled'] == '1') {

            if ($pricing['price_type'] === 'normal' && isset($pricing['normal'])) {
                foreach ($pricing['normal']['person_type'] as $index => $personType) {
                    TourPricing::create([
                        'tour_id' => $tour->id,
                        'price_type' => $pricing['price_type'],
                        'person_type' => $personType,
                        'person_description' => $pricing['normal']['person_description'][$index] ?? null,
                        'min_person' => $pricing['normal']['min_person'][$index] ?? null,
                        'max_person' => $pricing['normal']['max_person'][$index] ?? null,
                        'price' => $pricing['normal']['price'][$index] ?? null,
                    ]);
                }
            }

            if ($pricing['price_type'] === 'private') {
                TourPricing::create([
                    'tour_id' => $tour->id,
                    'price_type' => $pricing['price_type'],
                    'car_price' => $pricing['private']['car_price'] ?? null,
                    'min_person' => $pricing['private']['min_person'] ?? null,
                    'max_person' => $pricing['private']['max_person'] ?? null,
                ]);
            }

            if ($pricing['price_type'] === 'water' && isset($pricing['water'])) {
                foreach ($pricing['water']['time'] as $index => $waterTime) {
                    TourPricing::create([
                        'tour_id' => $tour->id,
                        'price_type' => $pricing['price_type'],
                        'time' => $waterTime,
                        'water_price' => $pricing['water']['water_price'][$index] ?? null,
                    ]);
                }
            }

            if ($pricing['price_type'] === 'promo' && isset($pricing['promo'])) {
                foreach ($pricing['promo']['promo_title'] as $index => $promoTitle) {
                    TourPricing::create([
                        'tour_id' => $tour->id,
                        'price_type' => $pricing['price_type'],
                        'promo_title' => $promoTitle,
                        'original_price' => $pricing['promo']['original_price'][$index] ?? null,
                        'discount_price' => $pricing['promo']['discount_price'][$index] ?? null,
                        'promo_price' => $pricing['promo']['promo_price'][$index] ?? null,
                        'offer_expire_at' => $pricing['promo']['offer_expire_at'][$index] ?? null,
                    ]);
                }
            }
        }

        if (isset($location['location_type'])) {
            if ($location['location_type'] == 'normal_location') {
                $cityIds = $location['normal_location']['city_ids'] ?? [];
                $tour->cities()->sync($cityIds);
            }
            if ($location['location_type'] === 'normal_itinerary') {
                $days = array_filter($location['normal_itinerary']['days'] ?? []);
                $titles = array_filter($location['normal_itinerary']['title'] ?? []);
                $descriptions = array_filter($location['normal_itinerary']['description'] ?? []);
                $locationFiles = $request->file('tour.location', []);
                $featuredImages = $locationFiles['normal_itinerary']['featured_image'] ?? [];
                $featuredImageAltTexts = array_filter($location['normal_itinerary']['featured_image_alt_text'] ?? []);
                foreach ($days as $index => $day) {
                    if (isset($titles[$index]) && isset($descriptions[$index])) {
                        TourItinerary::create([
                            'tour_id' => $tour->id,
                            'day' => $day ?? null,
                            'title' => $titles[$index] ?? null,
                            'description' => $descriptions[$index] ?? null,
                            'featured_image' => isset($featuredImages[$index])
                                ? $this->simpleUploadImg($featuredImages[$index], 'Tours/Tour-itinerary/Featured-images')
                                : null,
                            'featured_image_alt_text' => $featuredImageAltTexts[$index] ?? null,
                        ]);
                    }
                }
            }
        }

        $this->uploadImg('banner_image', 'Tours/Banners/Featured-images', $tour, 'banner_image');
        $this->uploadImg('featured_image', 'Tours/Featured-images', $tour, 'featured_image');
        $this->uploadImg('promotional_image', 'Tours/Promotional-images', $tour, 'promotional_image');

        if ($request->gallery) {
            foreach ($request->file('gallery') as $index => $image) {
                $path = $this->simpleUploadImg($image, 'Tours/Gallery-images');

                $tour->media()->create([
                    'file_path' => $path,
                    'alt_text' => $request['gallery_alt_texts'][$index],
                ]);
            }
        }

        handleSeoData($request, $tour, 'Tour');

        return redirect()->route('admin.tours.index')->with('notify_success', 'Tour Added successfully.')->with('active_tab', 'details');
    }

    public function edit($id)
    {
        $tour = Tour::with(['attributes', 'attributes.attributeItems'])->find($id);
        $attributes = TourAttribute::where('status', 'active')
            ->latest()->get();
        $categories = TourCategory::where('status', 'publish')->latest()->get();

        $tours = Tour::where('id', '!=', $id)->get();
        $users = User::where('is_active', 1)->get();

        $cities = City::where('status', 'publish')->get();
        $data = compact('tour', 'categories', 'cities', 'tours', 'users', 'attributes');

        return view('admin.tours.tours-management.edit', $data)->with('title', ucfirst(strtolower($tour->title)));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);

        $general = $request->input('tour.general', []);
        $statusTab = $request->input('tour.status', []);
        $availabilityData = $request->input('tour.availability', []);
        $pricing = $request->input('tour.pricing', []);
        $location = $request->input('tour.location', []);
        $itineraryExperience = $request->input('itinerary_experience', []);
        $badge = $request->input('tour.badge', []);

        $slugText = ! empty($general['slug']) ? $general['slug'] : $general['title'];
        $slug = $this->createSlug($slugText, 'tours', $tour->slug);

        $inclusions = ! empty($general['inclusions']) && ! in_array(null, $general['inclusions'], true)
            ? json_encode(array_filter($general['inclusions'], fn ($value) => $value !== null))
            : null;

        $exclusions = ! empty($general['exclusions']) && ! in_array(null, $general['exclusions'], true)
            ? json_encode(array_filter($general['exclusions'], fn ($value) => $value !== null))
            : null;

        $features = ! empty($general['features']) && ! in_array(null, $general['features'], true)
            ? json_encode(array_filter($general['features'], fn ($value) => $value !== null))
            : null;

        $relatedTours = ! empty($request->input('related_tour_ids')) ? json_encode($request->input('related_tour_ids')) : null;
        $extraPrices = ! empty($pricing['extra_price']) ? json_encode($pricing['extra_price']) : null;
        $discounts = ! empty($pricing['discount']) ? json_encode($pricing['discount']) : null;
        $availabilityOpenHours = ! empty($availabilityData['open_hours']) ? json_encode($availabilityData['open_hours']) : null;
        $badge = ! empty($request->input('tour.badge')) ? json_encode($request->input('tour.badge')) : null;

        $tour->update([
            'title' => $general['title'] ?? null,
            'slug' => $slug ?? null,
            'content' => $general['content'] ?? null,
            'category_id' => $general['category_id'] ?? null,
            'badge' => $badge ?? null,
            'banner_image_alt_text' => $request->input('banner_image_alt_text'),
            'featured_image_alt_text' => $request->input('featured_image_alt_text'),
            'promotional_image_alt_text' => $request->input('promotional_image_alt_text'),
            'banner_type' => $general['banner_type'] ?? null,
            'video_link' => $general['video_link'] ?? null,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'features' => $features,
            'status' => $statusTab['status'],
            'author_id' => $statusTab['author_id'],
            'is_featured' => $statusTab['is_featured'] ?? 0,
            'is_open_hours' => $availabilityData['is_open_hours'] ?? 0,
            'featured_state' => $statusTab['featured_state'] ?? null,
            'ical_import_url' => $statusTab['ical_import_url'] ?? null,
            'ical_export_url' => $statusTab['ical_export_url'] ?? null,
            'related_tour_ids' => $relatedTours,
            'is_fixed_date' => $availabilityData['is_fixed_date'] ?? 0,
            'start_date' => $availabilityData['start_date'],
            'end_date' => $availabilityData['end_date'] ?? null,
            'last_booking_date' => $availabilityData['last_booking_date'],
            'regular_price' => $pricing['regular_price'] ?? null,
            'sale_price' => $pricing['sale_price'] ?? null,
            'is_person_type_enabled' => $pricing['is_person_type_enabled'] ?? 0,
            'enabled_custom_service_fee' => $pricing['enabled_custom_service_fee'] ?? 0,
            'price_type' => isset($pricing['is_person_type_enabled']) && $pricing['is_person_type_enabled'] == 1 ? $pricing['price_type'] : null,
            'is_extra_price_enabled' => $pricing['is_extra_price_enabled'] ?? 0,
            'extra_prices' => $extraPrices ?? null,
            'service_fee_price' => $pricing['service_fee_price'] ?? null,
            'show_phone' => $pricing['show_phone'] ?? 0,
            'phone_country_code' => $pricing['phone_country_code'] ?? null,
            'phone_dial_code' => $pricing['phone_dial_code'] ?? null,
            'phone_number' => $pricing['phone_number'] ?? null,
            'address' => $location['normal_location']['address'] ?? null,
            'location_type' => $location['location_type'] ?? null,
            'itinerary_experience' => json_encode($itineraryExperience) ?? null,
            'discount_by_number_of_people' => $discounts ?? null,
            'availability_open_hours' => $availabilityOpenHours ?? null,
        ]);

        if (isset($general['faq']['question']) && is_array($general['faq']['question'])) {
            $tour->faqs()->delete();
            foreach ($general['faq']['question'] as $index => $question) {
                $answer = $general['faq']['answer'][$index] ?? null;

                if (! empty($question) && ! empty($answer)) {
                    TourFaq::create([
                        'question' => $question,
                        'answer' => $answer,
                        'tour_id' => $tour->id,
                    ]);
                }
            }
        }

        if (! empty($statusTab['attributes'])) {
            $tour->attributes()->detach();
            foreach ($statusTab['attributes'] as $attributeId => $itemIds) {
                foreach ($itemIds as $itemId) {
                    $tour->attributes()->attach($attributeId, ['tour_attribute_item_id' => $itemId]);
                }
            }
        } else {
            $tour->attributes()->detach();
        }

        if (! empty($general['details'])) {
            $tour->tourDetails()->delete();
            foreach ($general['details'] as $detail) {
                if ($detail['name'] !== null && ! empty($detail['items'])) {
                    TourDetail::create([
                        'tour_id' => $tour->id,
                        'name' => $detail['name'],
                        'items' => $detail['items'],
                        'urls' => $detail['urls'],
                    ]);
                }
            }
        }

        if (isset($pricing['is_person_type_enabled']) && $pricing['is_person_type_enabled'] == '1') {
            $tour->pricing()->delete();

            if ($pricing['price_type'] === 'normal' && isset($pricing['normal'])) {
                foreach ($pricing['normal']['person_type'] as $index => $personType) {
                    TourPricing::create([
                        'tour_id' => $tour->id,
                        'price_type' => $pricing['price_type'],
                        'person_type' => $personType,
                        'person_description' => $pricing['normal']['person_description'][$index] ?? null,
                        'min_person' => $pricing['normal']['min_person'][$index] ?? null,
                        'max_person' => $pricing['normal']['max_person'][$index] ?? null,
                        'price' => $pricing['normal']['price'][$index] ?? null,
                    ]);
                }
            }

            if ($pricing['price_type'] === 'private') {
                TourPricing::create([
                    'tour_id' => $tour->id,
                    'price_type' => $pricing['price_type'],
                    'car_price' => $pricing['private']['car_price'] ?? null,
                    'min_person' => $pricing['private']['min_person'] ?? null,
                    'max_person' => $pricing['private']['max_person'] ?? null,
                ]);
            }

            if ($pricing['price_type'] === 'water' && isset($pricing['water'])) {
                foreach ($pricing['water']['time'] as $index => $waterTime) {
                    TourPricing::create([
                        'tour_id' => $tour->id,
                        'price_type' => $pricing['price_type'],
                        'time' => $waterTime,
                        'water_price' => $pricing['water']['water_price'][$index] ?? null,
                    ]);
                }
            }

            if ($pricing['price_type'] === 'promo' && isset($pricing['promo'])) {
                foreach ($pricing['promo']['promo_title'] as $index => $promoTitle) {
                    TourPricing::create([
                        'tour_id' => $tour->id,
                        'price_type' => $pricing['price_type'],
                        'promo_title' => $promoTitle,
                        'original_price' => $pricing['promo']['original_price'][$index] ?? null,
                        'discount_price' => $pricing['promo']['discount_price'][$index] ?? null,
                        'promo_price' => $pricing['promo']['promo_price'][$index] ?? null,
                        'offer_expire_at' => $pricing['promo']['offer_expire_at'][$index] ?? null,
                    ]);
                }
            }
        }

        if (isset($location['location_type'])) {
            if ($location['location_type'] == 'normal_location') {
                $cityIds = $location['normal_location']['city_ids'] ?? [];
                $tour->cities()->sync($cityIds);
            }

            if ($location['location_type'] === 'normal_itinerary') {

                $ids = $location['normal_itinerary']['ids'] ?? [];
                $tour->normalItineraries()->whereNotIn('id', $ids)->delete();

                $days = array_filter($location['normal_itinerary']['days'] ?? []);
                $titles = array_filter($location['normal_itinerary']['title'] ?? []);
                $descriptions = array_filter($location['normal_itinerary']['description'] ?? []);
                $locationFiles = $request->file('tour.location', []);
                $featuredImages = $locationFiles['normal_itinerary']['featured_image'] ?? [];
                $featuredImageAltTexts = array_filter($location['normal_itinerary']['featured_image_alt_text'] ?? []);

                foreach ($days as $index => $day) {
                    if (isset($titles[$index]) && isset($descriptions[$index])) {
                        if (! empty($ids[$index])) {
                            $itinerary = TourItinerary::find($ids[$index]);
                            if ($itinerary) {
                                $itinerary->update([
                                    'day' => $day ?? null,
                                    'title' => $titles[$index] ?? null,
                                    'description' => $descriptions[$index] ?? null,
                                    'featured_image' => isset($featuredImages[$index]) && $featuredImages[$index]
                                        ? $this->simpleUploadImg($featuredImages[$index], 'Tours/Tour-itinerary/Featured-images')
                                        : $itinerary->featured_image,
                                    'featured_image_alt_text' => $featuredImageAltTexts[$index] ?? $itinerary->featured_image_alt_text,
                                ]);
                            }
                        } else {

                            TourItinerary::create([
                                'tour_id' => $tour->id,
                                'day' => $day ?? null,
                                'title' => $titles[$index] ?? null,
                                'description' => $descriptions[$index] ?? null,
                                'featured_image' => isset($featuredImages[$index])
                                    ? $this->simpleUploadImg($featuredImages[$index], 'Tours/Tour-itinerary/Featured-images')
                                    : null,
                                'featured_image_alt_text' => $featuredImageAltTexts[$index] ?? null,
                            ]);
                        }
                    }
                }
            }
        }

        if ($request->gallery) {
            foreach ($request->file('gallery') as $index => $image) {
                $path = $this->simpleUploadImg($image, 'Tours/Gallery-images');

                $tour->media()->create([
                    'file_path' => $path,
                    'alt_text' => $request['gallery_alt_texts'][$index],
                ]);
            }
        }

        if ($request->hasFile('banner_image')) {
            $tour->banner_image = $this->simpleUploadImg($request->file('banner_image'), 'Tours/Banners/Featured-images');
        }
        if ($request->hasFile('featured_image')) {
            $tour->featured_image = $this->simpleUploadImg($request->file('featured_image'), 'Tours/Featured-images');
        }
        if ($request->hasFile('promotional_image')) {
            $tour->promotional_image = $this->simpleUploadImg($request->file('promotional_image'), 'Tours/Promotional-images');
        }

        $tour->save();

        handleSeoData($request, $tour, 'Tour');

        return redirect()->route('admin.tours.index')->with('notify_success', 'Tour Added successfully.')->with('active_tab', 'details');
    }

    public function duplicate($id)
    {

        $tour = Tour::findOrFail($id);

        $newTour = $tour->replicate();

        $newTour->title = $tour->title.' - Copy';
        $newTour->status = 'draft';
        $newTour->slug = $this->createSlug($newTour->title, 'tours');

        $newTour->save();

        $this->duplicateSeoData($tour, $newTour);
        $this->duplicateFaqs($tour, $newTour);
        $this->duplicateAttributes($tour, $newTour);
        $this->duplicateDetails($tour, $newTour);
        $this->duplicatePricing($tour, $newTour);
        $this->duplicateItinerary($tour, $newTour);
        $this->duplicateCities($tour, $newTour);
        $this->duplicateMedia($tour, $newTour);

        return redirect()->route('admin.tours.index')->with('notify_success', 'Tour duplicated successfully.');
    }

    public function duplicateSeoData($tour, $newTour)
    {

        $tour->load('seo');

        if ($tour->seo) {

            $newSeoData = $tour->seo->replicate();

            $newSeoData->seoable_id = $newTour->id;
            $newSeoData->seoable_type = get_class($newTour);

            $newSeoData->save();
        }
    }

    private function duplicateFaqs($tour, $newTour)
    {
        foreach ($tour->faqs as $faq) {
            $newTour->faqs()->create([
                'question' => $faq->question,
                'answer' => $faq->answer,
            ]);
        }
    }

    private function duplicateAttributes($tour, $newTour)
    {
        foreach ($tour->attributes as $attribute) {
            $newTour->attributes()->attach($attribute->id);
        }
    }

    private function duplicateDetails($tour, $newTour)
    {
        foreach ($tour->tourDetails as $detail) {
            $newTour->tourDetails()->create([
                'name' => $detail->name,
                'items' => $detail->items,
                'urls' => $detail->urls,
            ]);
        }
    }

    private function duplicatePricing($tour, $newTour)
    {
        $priceType = $tour->price_type;
        switch ($priceType) {
            case 'normal':
                $pricings = $tour->normalPrices;
                break;
            case 'private':
                $pricings = $tour->privatePrices;
                break;
            case 'water':
                $pricings = $tour->waterPrices;
                break;
            case 'promo':
                $pricings = $tour->promoPrices;
                break;
        }
        foreach ($pricings as $pricing) {
            TourPricing::create([
                'tour_id' => $newTour->id,
                'price_type' => $pricing['price_type'],
                'person_type' => $pricing['person_type'],
                'person_description' => $pricing['person_description'],
                'min_person' => $pricing['min_person'],
                'max_person' => $pricing['max_person'],
                'price' => $pricing['price'],
                'car_price' => $pricing['car_price'],
                'time' => $pricing['time'],
                'water_price' => $pricing['water_price'],
                'promo_title' => $pricing['promo_title'],
                'original_price' => $pricing['original_price'],
                'discount_price' => $pricing['discount_price'],
                'promo_price' => $pricing['promo_price'],
                'offer_expire_at' => $pricing['offer_expire_at'],
            ]);
        }
    }

    private function duplicateItinerary($tour, $newTour)
    {
        foreach ($tour->normalItineraries as $itinerary) {
            TourItinerary::create([
                'tour_id' => $newTour->id,
                'day' => $itinerary->day,
                'title' => $itinerary->title,
                'description' => $itinerary->description,
                'featured_image' => $itinerary->featured_image,
                'featured_image_alt_text' => $itinerary->featured_image_alt_text,
            ]);
        }
    }

    private function duplicateCities($tour, $newTour)
    {
        $newTour->cities()->sync($tour->cities->pluck('id')->toArray());
    }

    private function duplicateMedia($tour, $newTour)
    {
        foreach ($tour->media as $media) {
            $newTour->media()->create([
                'file_path' => $media->file_path,
                'alt_text' => $media->alt_text,
            ]);
        }
    }
}
