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

    /**
     * Get the public URL for an asset in storage or fallback to default
     */
    function storage_asset(?string $path, ?string $default = null): string
    {
        if (empty($path)) {
            return $default ? asset($default) : asset('backend/images/products/placeholder.png');
        }

        // Full URLs (http, https) or inline base64/data URIs
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $clean = ltrim($path, '/');

        // If it starts with storage/
        if (str_starts_with($clean, 'storage/')) {
            return asset($clean);
        }

        // If it starts with backend/ or frontend/ (legacy public paths)
        if (str_starts_with($clean, 'backend/') || str_starts_with($clean, 'frontend/')) {
            return asset($clean);
        }

        // Standard Laravel public storage path
        if (file_exists(public_path('storage/' . $clean)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
            return asset('storage/' . $clean);
        }

        // Fallback to default if file is missing in storage
        if ($default) {
            return asset($default);
        }

        return asset('storage/' . $clean);
    }

    /**
     * Safely delete an uploaded file from public storage or fallback legacy path
     */
    function delete_storage_file(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $diskPath = ltrim(str_replace(['storage/', 'backend/images/', 'frontend/images/'], '', $path), '/');

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($diskPath)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->delete($diskPath);
        }

        if (file_exists(public_path($path))) {
            @unlink(public_path($path));
            return true;
        }

        return false;
    }
}
