<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use App\Models\TourDetailPopup;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    use UploadImageTrait;

    public function edit($resource)
    {
        $detailPopups = TourDetailPopup::where('status', 'active')->get();
        $settings = Setting::pluck('value', 'key');
        $pages = Page::where('status', 'publish')->get();
        $viewPath = "admin.settings.$resource";

        if (! view()->exists($viewPath)) {
            return redirect()->back()->with('notify_error', 'Settings page not found.');
        }

        return view($viewPath, compact('settings', 'pages', 'detailPopups'));
    }

    public function update(Request $request, $resource)
    {
        $input = $request->except('_token');

        foreach ($input as $key => $value) {
            if ($key === 'footer_config') {
                continue;
            }
            if ($request->hasFile($key)) {
                Setting::setFile($key, $request->file($key), $resource);
            } elseif (in_array($key, ['perks', 'detail_popup_ids', 'header_menu', 'global_local_business_schema']) && is_array($value)) {
                Setting::set($key, json_encode($value), $resource);
            } elseif (! is_array($value)) {
                Setting::set($key, $value, $resource);
            }
        }

        if ($request->has('merchant_images')) {
            $merchantImages = $request->merchant_images ?? [];
            $processedImages = [];

            foreach ($merchantImages as $index => $imageData) {
                $imageItem = [
                    'alt_text' => $imageData['alt_text'] ?? '',
                ];

                // New uploaded file
                if (!empty($imageData['image']) && $request->hasFile("merchant_images.$index.image")) {
                    $file = $request->file("merchant_images.$index.image");
                    $imageItem['image'] = $this->simpleUploadImg($file, 'Merchant-Images');

                    // No new upload, keep existing
                } elseif (!empty($imageData['existing_image'])) {
                    $imageItem['image'] = $imageData['existing_image'];
                }

                if (!empty($imageItem['image'])) {
                    $processedImages[] = $imageItem;
                }
            }

            Setting::set('merchant_images', json_encode($processedImages), $resource);
        }

        $imageKeys = [
            'stripe_logo' => 'Stripe-Logo',
            'tabby_logo' => 'Tabby-Logo',
            'paypal_logo' => 'Paypal-Logo',
            'tamara_logo' => 'Tamara-Logo',
            'cash_logo' => 'Cash-Logo',
        ];

        foreach ($imageKeys as $key => $folder) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $imagePath = $this->simpleUploadImg($file, $folder);
                Setting::set($key, $imagePath, $resource);
            }
        }

        if ($request->has('footer_config')) {
            $footer_config = $request->footer_config ?? [];
            if (is_string($footer_config)) {
                $footer_config = json_decode($footer_config, true) ?? [];
            }

            $blocks = $footer_config['blocks'] ?? [];

            foreach ($blocks as $index => &$block) {
                if ($block['type'] === 'image' && $request->hasFile("footer_config.blocks.$index.image")) {
                    $file = $request->file("footer_config.blocks.$index.image");
                    $block['image'] = asset($this->simpleUploadImg($file, 'Footer/Quick-links'));
                }
            }
            unset($block);

            $footer_config['blocks'] = $blocks;
            Setting::set('footer_config', json_encode($footer_config), $resource);
        }

        $envKeys = [
            'app_name' => 'APP_NAME',
            'app_timezone' => 'APP_TIMEZONE',
            'app_currency' => 'APP_CURRENCY',
            'tabby_public_key' => 'TABBY_PUBLIC_KEY',
            'tabby_secret_key' => 'TABBY_SECRET_KEY',
            'stripe_publishable_key' => 'STRIPE_PUBLISHABLE_KEY',
            'stripe_secret_key' => 'STRIPE_SECRET_KEY',
            'paypal_client_id' => 'PAYPAL_CLIENT_ID',
            'paypal_secret_key' => 'PAYPAL_SECRET_KEY',
            'tamara_public_key' => 'TAMARA_PUBLIC_KEY',
            'tamara_secret_key' => 'TAMARA_SECRET_KEY',
            'postpay_public_key' => 'POSTPAY_PUBLIC_KEY',
            'postpay_secret_key' => 'POSTPAY_SECRET_KEY',
            'email_from_name' => 'MAIL_FROM_NAME',
            'email_from_address' => 'MAIL_FROM_ADDRESS',
            'facebook_client_id' => 'FACEBOOK_CLIENT_ID',
            'facebook_client_secret' => 'FACEBOOK_CLIENT_SECRET',
            'google_client_id' => 'GOOGLE_CLIENT_ID',
            'google_client_secret' => 'GOOGLE_CLIENT_SECRET',
            're_captcha_site_key' => 'RE_CAPTCHA_SITE_KEY',
            'pointcheckout_api_key' => 'POINTCHECKOUT_API_KEY',
            'pointcheckout_api_secret' => 'POINTCHECKOUT_API_SECRET',
        ];

        foreach ($envKeys as $inputKey => $envKey) {
            if ($request->has($inputKey)) {
                $this->updateEnvFile($envKey, $request->input($inputKey));
            }
        }

        return back()->with('success', 'Settings updated successfully');
    }

    public function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');
        $envFile = File::get($envPath);

        $value = '"' . $value . '"';

        if (strpos($envFile, "$key=") !== false) {
            $envFile = preg_replace("/^$key=.*/m", "$key=$value", $envFile);
        } else {
            $envFile .= "\n$key=$value";
        }

        File::put($envPath, $envFile);
    }
}
