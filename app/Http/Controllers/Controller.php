<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\ImageTable;
use App\Models\Popup;

abstract class Controller
{
    public function __construct()
    {
        $logo = ImageTable::where('table_name', 'logo')->latest()->first();

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

        View()->share('logo', $logo);
        View()->share('popup', $matchedPopup);
    }

    public static function getConfig()
    {
        return Config::where('is_active', 1)
            ->pluck('flag_value', 'flag_type')
            ->toArray();
    }
}
