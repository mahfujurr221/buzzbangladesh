<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view-website-setting')->only('website_setting');
        $this->middleware('can:update-website-setting')->only('website_setting_update');
        $this->middleware('can:view-backend-setting')->only('backend_setting');
        $this->middleware('can:update-backend-setting')->only('backend_setting_update');
    }

    /**
     * Website Settings
     */
    public function website_setting()
    {
        $setting = SettingWebsite::first();
        if (!$setting) {
            $setting = SettingWebsite::create(['site_name' => 'VejalNai']);
        }
        return view('backend.pages.setting.website_setting', compact('setting'));
    }

    public function website_setting_update(Request $request)
    {
        $setting = SettingWebsite::first();
        if (!$setting) {
            $setting = SettingWebsite::create(['site_name' => 'VejalNai']);
        }

        // Logo handle
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo.png';
            
            // Save to frontend ONLY
            $frontPath = public_path('frontend/assets/images');
            if (!file_exists($frontPath)) { @mkdir($frontPath, 0777, true); }
            $file->move($frontPath, $filename);
            
            $setting->logo = $filename;
        }


        // Favicon handle
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $filename = 'favicon.png';
            
            // Save to frontend ONLY
            $frontPath = public_path('frontend/assets/images');
            if (!file_exists($frontPath)) { @mkdir($frontPath, 0777, true); }
            $file->move($frontPath, $filename);
            
            $setting->favicon = $filename;
        }

        $data = $request->except(['logo', 'favicon']);
        if (isset($data['meta_keywords']) && is_string($data['meta_keywords'])) {
            $data['meta_keywords'] = array_map('trim', explode(',', $data['meta_keywords']));
        }
        if (isset($data['meta_keywords_bn']) && is_string($data['meta_keywords_bn'])) {
            $data['meta_keywords_bn'] = array_map('trim', explode(',', $data['meta_keywords_bn']));
        }
        
        $setting->update($data);
        
        toast('Website Settings updated successfully!', 'success');
        return redirect()->back();
    }

    /**
     * Backend Settings
     */
    public function backend_setting()
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create(['site_name' => 'VejalNai']);
        }
        return view('backend.pages.setting.backend_setting', compact('setting'));
    }

    public function backend_setting_update(Request $request)
    {
        $setting = Setting::first();

        $request->validate([
            'site_name' => 'required|string|max:255',
            'currency_name' => 'required|string|max:255',
            'currency_symbol' => 'required|string|max:255',
            'currency_code' => 'required|string|max:255',
        ]);

        // Update all fields directly
        $setting->site_name = $request->site_name;
        $setting->site_title = $request->site_title;
        $setting->site_title_bn = $request->site_title_bn;
        $setting->address = $request->address;
        $setting->address_bn = $request->address_bn;
        $setting->phone = $request->phone;
        $setting->email = $request->email;
        $setting->currency_name = $request->currency_name;
        $setting->currency_symbol = $request->currency_symbol;
        $setting->currency_code = $request->currency_code;
        $setting->currency_position = $request->currency_position;
        $setting->pos_receipt_type = $request->pos_receipt_type;
        $setting->purchase_receipt_type = $request->purchase_receipt_type;
        $setting->payment_receipt_type = $request->payment_receipt_type;
        $setting->invoice_view_type = $request->invoice_view_type;
        $setting->low_stock_limit = $request->low_stock_limit;
        $setting->default_vat = $request->default_vat ?: 5.00;
        $setting->dark_mode = $request->has('dark_mode') ? 1 : 0;

        //favicon
        if ($request->hasFile('favicon')) {
            $image = $request->file('favicon');
            $filename = 'favicon.png';
            
            // Save to backend ONLY
            $backPath = public_path('backend/images');
            if (!file_exists($backPath)) { @mkdir($backPath, 0777, true); }
            $image->move($backPath, $filename);
            
            $setting->favicon = $filename;
        }



        //logo
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = 'logo.png';
            
            // Save to backend ONLY
            $backPath = public_path('backend/images');
            if (!file_exists($backPath)) { @mkdir($backPath, 0777, true); }
            $image->move($backPath, $filename);
            
            $setting->logo = $filename;
        }


        $setting->save();
        toast('Backend Settings updated successfully!', 'success');
        return redirect()->back();
    }
}
