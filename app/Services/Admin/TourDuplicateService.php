<?php

namespace App\Services\Admin;

use App\Models\Tour;
use App\Traits\Sluggable;
use App\Models\TourPricing;
use App\Models\TourAddOn;
use App\Models\TourItinerary;
use Illuminate\Support\Str;

class TourDuplicateService
{
    use Sluggable;

    public function duplicate(Tour $tour): Tour
    {
        $newTour = $tour->replicate();

        $newTour->title = $tour->title . ' - Copy';
        $newTour->status = 'draft';
        $newTour->slug = $this->createSlug($newTour->title, 'tours');

        $newTour->save();

        $this->duplicateSeoData($tour, $newTour);
        $this->duplicateFaqs($tour, $newTour);
        $this->duplicateAttributes($tour, $newTour);
        $this->duplicatePricing($tour, $newTour);
        $this->duplicateItinerary($tour, $newTour);
        $this->duplicateAddOns($tour, $newTour);
        $this->duplicateCategories($tour, $newTour);
        $this->duplicateMedia($tour, $newTour);

        return $newTour;
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

    private function duplicatePricing($tour, $newTour)
    {
        $priceType = $tour->price_type;
        $pricings = match ($priceType) {
            'normal' => $tour->normalPrices,
            'private' => $tour->privatePrices,
            'water' => $tour->waterPrices,
            'promo' => $tour->promoPrices,
            default => null,
        };

        if (! $pricings) {
            return;
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
                'promo_slug' => $pricing['promo_slug'],
                'promo_is_free' => $pricing['promo_is_free'],
                'original_price' => $pricing['original_price'],
            ]);
        }

        // Duplicate promo add-ons if price type is promo
        if ($priceType === 'promo') {
            $promoAddOn = TourPricing::where('tour_id', $tour->id)
                ->where('price_type', 'promoAddOn')
                ->first();

            if ($promoAddOn && $promoAddOn->promo_addons) {
                $promoAddOns = json_decode($promoAddOn->promo_addons);

                // regenerate unique slugs for each add-on
                $newPromoAddOns = collect($promoAddOns)->map(function ($addon) {
                    $addon->promo_slug = Str::slug(strip_tags($addon->title)) . '-' . uniqid();
                    return $addon;
                })->all();

                // create new promoAddOn row for the duplicated tour
                TourPricing::create([
                    'tour_id' => $newTour->id,
                    'price_type' => 'promoAddOn',
                    'promo_addons' => json_encode($newPromoAddOns),
                ]);
            }
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

    private function duplicateAddOns($tour, $newTour)
    {
        foreach ($tour->addOns as $addOn) {
            TourAddOn::create([
                'tour_id' => $newTour->id,
                'heading' => $addOn->heading,
                'tour_ids' => $addOn->tour_ids,
            ]);
        }
    }

    private function duplicateCategories($tour, $newTour)
    {
        $newTour->categories()->sync($tour->categories->pluck('id')->toArray());
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
