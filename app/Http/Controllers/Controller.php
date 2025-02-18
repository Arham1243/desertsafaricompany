<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Popup;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;

abstract class Controller
{
    public function __construct()
    {
        $cart = Session::get('cart', []);
        $settings = Setting::where('group', 'general')->pluck('value', 'key');

        $currentUrl = request()->path();

        $activePopups = Popup::where('status', 'active')->orderBy('created_at', 'desc')->get();

        $matchedPopup = null;

        foreach ($activePopups as $popup) {
            $includedPages = $popup->included_pages;

            if (is_string($includedPages)) {
                $includedPages = json_decode($includedPages, true);
            }

            if (is_array($includedPages) && in_array($currentUrl, $includedPages)) {
                $matchedPopup = $popup;
                break;
            }
        }

        View()->share('cart', $cart);
        View()->share('popup', $matchedPopup);
        View()->share('settings', $settings);
    }

    public static function getConfig()
    {
        return Config::where('is_active', 1)
            ->pluck('flag_value', 'flag_type')
            ->toArray();
    }
}
