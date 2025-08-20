<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use App\Models\TourDetailPopup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function edit($resource)
    {
        $detailPopups = TourDetailPopup::where('status', 'active')->get();
        $settings = Setting::where('group', $resource)->pluck('value', 'key');
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
            if ($request->hasFile($key)) {
                Setting::setFile($key, $request->file($key), $resource);
            } elseif (in_array($key, ['perks', 'detail_popup_ids']) && is_array($value)) {
                Setting::set($key, json_encode($value), $resource);
            } elseif (! is_array($value)) {
                Setting::set($key, $value, $resource);
            }
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

        $value = '"'.$value.'"';

        if (strpos($envFile, "$key=") !== false) {
            $envFile = preg_replace("/^$key=.*/m", "$key=$value", $envFile);
        } else {
            $envFile .= "\n$key=$value";
        }

        File::put($envPath, $envFile);
    }
}
