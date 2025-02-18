<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function edit($resource)
    {
        $settings = Setting::where('group', $resource)->pluck('value', 'key');
        $pages = Page::where('status', 'publish')->get();

        return view("admin.settings.$resource", compact(
            'settings',
            'pages',
        ));
    }

    public function update(Request $request, $resource)
    {
        foreach ($request->except('_token') as $key => $value) {
            if ($request->hasFile($key)) {
                Setting::setFile($key, $request->file($key), $resource);
            } else {
                Setting::set($key, $value, $resource);
            }

            $appName = Setting::get('app_name');
            $appTimezone = Setting::get('app_timezone');
            $appCurrency = Setting::get('app_currency');
            $stripeSecretKey = Setting::get('stripe_secret_key');
            $tabbySecretKey = Setting::get('tabby_secret_key');

            if ($appName) {
                $this->updateEnvFile('APP_NAME', $appName);
            }
            if ($appCurrency) {
                $this->updateEnvFile('APP_CURRENCY', $appCurrency);
            }
            if ($appTimezone) {
                $this->updateEnvFile('APP_TIMEZONE', $appTimezone);
            }
            if ($stripeSecretKey) {
                $this->updateEnvFile('STRIPE_SECRET_KEY', $stripeSecretKey);
            }
            if ($tabbySecretKey) {
                $this->updateEnvFile('TABBY_KEY', $tabbySecretKey);
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
