<?php

use App\Helpers\SeoHelper;
use App\Models\Tour;
use App\Models\TourCategory;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;

if (! function_exists('buildUrl')) {
    function buildUrl($base, $resource = null, $slug = null)
    {
        $url = $base;
        if ($resource) {
            $url .= '/' . $resource;
        }
        if ($slug) {
            $url .= '/' . $slug;
        }

        return $url;
    }
}
if (! function_exists('sanitizedLink')) {
    function sanitizedLink($url)
    {
        return '//' . preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
    }
}

if (! function_exists('currencySymbol')) {
    function currencySymbol(): HtmlString
    {
        $c = env('APP_CURRENCY');

        return new HtmlString($c === 'AED' ? '<span class="dirham">D</span>' : $c);
    }
}

if (! function_exists('formatPrice')) {
    function formatPrice($price, bool $float = true): HtmlString
    {
        $val = $float
            ? number_format($price, 2, '.', ',')
            : number_format($price, 0, '.', ',');

        return new HtmlString(currencySymbol()->toHtml() . $val);
    }
}
if (! function_exists('handleSeoData')) {
    function handleSeoData($request, $entry, $resource)
    {
        $seoHelper = new SeoHelper;
        $seoHelper->handleSeoData($request, $entry, $resource);
    }
}
if (! function_exists('formatDateTime')) {
    function formatDateTime($date)
    {
        if (empty($date)) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format('M j, Y - g:i A');
    }
}
if (! function_exists('formatDate')) {
    function formatDate($date)
    {
        return Carbon::parse($date)->format('M j, Y');
    }
}
if (! function_exists('renderCategories')) {
    function renderCategories($categories, $selectedCategory = null, $parent_id = null, $level = 0)
    {
        foreach ($categories->where('parent_category_id', $parent_id) as $category) {
            $selected = (old('category_id', $selectedCategory) == $category->id) ? 'selected' : '';

            echo '<option value="' . $category->id . '" ' . $selected . '>';
            echo $level > 0 ? str_repeat('&nbsp;&nbsp;', $level) . str_repeat('-', $level) . ' ' : '';
            echo $category->name;
            echo '</option>';

            renderCategories($categories, $selectedCategory, $category->id, $level + 1);
        }
    }
}
if (! function_exists('renderCategoriesMulti')) {
    function renderCategoriesMulti($categories, $selectedCategories = [], $parent_id = null, $level = 0)
    {
        foreach ($categories->where('parent_category_id', $parent_id) as $category) {
            $selected = in_array($category->id, (array) $selectedCategories) ? 'selected' : '';

            echo '<option value="' . $category->id . '" ' . $selected . '>';
            echo $level > 0 ? str_repeat('&nbsp;&nbsp;', $level) . str_repeat('-', $level) . ' ' : '';
            echo $category->name;
            echo '</option>';

            renderCategoriesMulti($categories, $selectedCategories, $category->id, $level + 1);
        }
    }
}
if (! function_exists('getTimeLeft')) {
    function getTimeLeft($expireAt)
    {
        $now = new DateTime;
        $expiry = new DateTime($expireAt);
        $interval = $expiry->diff($now);

        if ($expiry <= $now) {
            return 'expired';
        }

        if ($interval->d > 0) {
            return $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
        }

        if ($interval->h > 0) {
            return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '');
        }

        if ($interval->i > 0) {
            return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
        }

        return 'less than a minute';
    }
}
if (! function_exists('formatNameForInput')) {
    function formatNameForInput($name)
    {
        return strtolower(str_replace(' ', '_', $name));
    }
}
if (! function_exists('formatKey')) {
    function formatKey($value)
    {
        return ucwords(str_replace('_', ' ', $value));
    }
}
if (! function_exists('formatBigNumber')) {
    function formatBigNumber($num)
    {
        if ($num >= 1_000_000_000) {
            return rtrim(number_format($num / 1_000_000_000, 1, '.', '0'), '.0') . 'B';
        }
        if ($num >= 1_000_000) {
            return rtrim(number_format($num / 1_000_000, 1, '.', '0'), '.0') . 'M';
        }
        if ($num >= 1_000) {
            return rtrim(number_format($num / 1_000, 1, '.', '0'), '.0') . 'K';
        }

        return (string) $num;
    }
}
if (! function_exists('getAllCategoryIds')) {
    function getAllCategoryIds($categoryId)
    {
        $ids = [$categoryId];
        $children = TourCategory::where('parent_category_id', $categoryId)->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, getAllCategoryIds($childId));
        }

        return $ids;
    }
}
if (! function_exists('applyPromoDiscount')) {
    function applyPromoDiscount($price, $discountType, $discountAmount)
    {
        $price = (float) str_replace(',', '', $price);

        if ($discountType === 'percentage') {
            return $price - $price * ($discountAmount / 100);
        } elseif ($discountType === 'fixed') {
            return $price - $discountAmount;
        }

        return $price;
    }
}
if (! function_exists('getTourByID')) {
    function getTourByID($tourId)
    {
        $tour = Tour::findOrFail($tourId);

        return $tour;
    }
}
if (! function_exists('getToursFromCart')) {
    function getToursFromCart($cartData)
    {
        $cartData = json_decode($cartData);
        $tours = [];

        foreach ($cartData->tours as $tourId => $data) {
            $tours[] = Tour::find($tourId);
        }

        return collect($tours)->filter();
    }
}
if (! function_exists('getCouponsFromCart')) {
    function getCouponsFromCart($cartData)
    {
        $cartData = is_string($cartData) ? json_decode($cartData) : $cartData;

        if (! isset($cartData->applied_coupons) || ! is_array($cartData->applied_coupons)) {
            return collect();
        }

        return collect($cartData->applied_coupons)->map(function ($coupon) {
            return (object) [
                'id' => $coupon->coupon ?? null,
                'code' => $coupon->code ?? null,
                'type' => $coupon->type ?? null,
                'amount' => $coupon->amount ?? 0,
                'is_first_order' => $coupon->is_first_order_coupon ?? 0,
            ];
        });
    }
}
if (! function_exists('getTotalNoOfPeopleFromCart')) {
    function getTotalNoOfPeopleFromCart($cartData)
    {
        $cartData = is_string($cartData) ? json_decode($cartData, true) : $cartData;

        return isset($cartData['total_no_of_people']) ? $cartData['total_no_of_people'] . ' people' : 'N/A';
    }
}
if (! function_exists('buildTourDetailUrl')) {
    function buildTourDetailUrl($tour, $withSlug = true, $withBase = true)
    {
        $categorySlug = $tour->category->slug ?? 'no-category';
        $city = $tour->city->slug ?? 'no-city';
        $country = $tour->city->country->iso_alpha2 ?? 'no-country';
        $slug = $tour->slug ?? 'no-slug';

        $path = "$country/$city/$categorySlug";
        if ($withSlug) {
            $path .= "/$slug";
        }

        return $withBase ? url($path) : $path;
    }
}
if (! function_exists('buildCategoryDetailUrl')) {
    function buildCategoryDetailUrl($category, $withSlug = true, $withBase = true)
    {
        $segments = [];

        if (! empty($category->city?->country?->iso_alpha2)) {
            $segments[] = strtolower($category->city->country->iso_alpha2);
        } elseif (! empty($category->country?->iso_alpha2)) {
            $segments[] = strtolower($category->country->iso_alpha2);
        }

        if (! empty($category->city?->slug)) {
            $segments[] = $category->city->slug;
        }

        if ($withSlug && ! empty($category->slug)) {
            $segments[] = $category->slug;
        }

        $path = implode('/', $segments);

        return $withBase ? url($path) : $path;
    }
}
if (! function_exists('buildBlogDetailUrl')) {
    function buildBlogDetailUrl($blog, $withSlug = true, $withBase = true)
    {
        $country = $blog->city->country->iso_alpha2 ?? 'no-country';
        $city = $blog->city->slug ?? 'no-city';
        $slug = $blog->slug ?? 'no-slug';

        $path = "$country/$city";

        if ($withSlug) {
            $path .= "/blog/$slug";
        }

        return $withBase ? url($path) : $path;
    }
}
if (! function_exists('buildNewsDetailUrl')) {
    function buildNewsDetailUrl($news, $withSlug = true, $withBase = true)
    {
        $segments = ['news'];

        if ($withSlug && ! empty($news->slug)) {
            $segments[] = $news->slug;
        }

        $path = implode('/', $segments);

        return $withBase ? url($path) : $path;
    }
}

if (! function_exists('replaceTemplateVariables')) {
    function replaceTemplateVariables(string $template, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }
}
if (! function_exists('getSortedHeaderMenu')) {
    function getSortedHeaderMenu($menuJson)
    {
        if (! $menuJson) {
            return [];
        }

        $menuArray = json_decode($menuJson, true);

        if (! $menuArray || ! is_array($menuArray)) {
            return [];
        }

        usort($menuArray, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

        return $menuArray;
    }
}
if (! function_exists('url_to_path')) {
    function url_to_path($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = str_replace('/public/', '', $path);

        return ltrim($path, '/');
    }
}
if (! function_exists('sanitizePhoneNumber')) {
    function sanitizePhoneNumber(string $number, bool $keepPlus = false): string
    {
        return $keepPlus
            ? preg_replace('/[^\d+]/', '', $number)
            : preg_replace('/\D/', '', $number);
    }
}

if (! function_exists('makePhoneNumber')) {
    function makePhoneNumber($dial, $number)
    {
        if ($dial && $number) {
            return '+' . $dial . ' ' . $number;
        }

        return 'N/A';
    }
}
function getToursByBlock(array $block, $offset = 0, $limit = null)
{
    $tours = Tour::with(['city.country', 'categories'])->get();

    $resourceType = $block['resource_type'] ?? 'tour';
    $sortBy = $block['sort_by'] ?? null;

    if ($resourceType === 'city') {
        $tours = $tours->whereIn('city_id', $block['city_ids'] ?? []);
    } elseif ($resourceType === 'country') {
        $tours = $tours->filter(
            fn($t) => $t->city && in_array($t->city->country_id, $block['country_ids'] ?? [])
        );
    } elseif ($resourceType === 'category') {
        $tours = $tours->filter(
            fn($t) => $t->categories->pluck('id')
                ->intersect($block['category_ids'] ?? [])
                ->isNotEmpty()
        );
    } else {
        $tours = $tours->whereIn('id', $block['tour_ids'] ?? []);
    }

    if ($resourceType === 'tour') {
        match ($sortBy) {
            'asc' => $tours = $tours->sortBy('title'),
            'desc' => $tours = $tours->sortByDesc('title'),
            'random' => $tours = $tours->shuffle(),
            default => null
        };
    }

    // Only slice if limit is provided
    return $limit ? $tours->slice($offset, $limit)->values() : $tours->values();
}
