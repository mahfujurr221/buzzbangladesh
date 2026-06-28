<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Session;

if (! function_exists('toast')) {
    /**
     * Set a Bootstrap 5 toast message in session
     *
     * @param string $message
     * @param string $type success, danger, warning, info
     */
    function toast(string $message, string $type = 'success')
    {
        Session::flash('message', [
            'text' => $message,
            'type' => $type
        ]);
    }

    function setting()
    {
        $setting = Setting::first();
        return $setting;
    }
}
