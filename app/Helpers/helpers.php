<?php

use App\Models\Setting;
use App\Models\SettingWebsite;
use Illuminate\Support\Facades\Session;

if (! function_exists('toast')) {
    /**
     * Set a Bootstrap 5 toast message in session
     *
     * @param  string  $type  success, danger, warning, info
     */
    function toast(string $message, string $type = 'success')
    {
        Session::flash('message', [
            'text' => $message,
            'type' => $type,
        ]);
    }

    function setting()
    {
        $setting = Setting::first();
        if (! $setting) {
            $setting = Setting::create(['site_name' => 'BuzzBangladesh']);
        }

        return $setting;
    }

    function website_setting()
    {
        $setting = SettingWebsite::first();
        if (! $setting) {
            $setting = SettingWebsite::create(['site_name' => 'BuzzBangladesh']);
        }

        return $setting;
    }
}
