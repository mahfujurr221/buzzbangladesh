<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware("can:update-setting")->only("index");
    }
    public function index()
    {
        $setting = Setting::first();
        // dd($setting);
        return view('backend.pages.website.setting', compact('setting'));
    }

    public function update(Request $request, string $id)
    {
        $setting = Setting::first();
        // basic information
        if ($request->update_section == 'basic_information') {
            $setting->site_name = $request->site_name;
            $setting->site_title = $request->site_title;
            $setting->address = $request->address;
            $setting->address2 = $request->address2;
            $setting->phone = $request->phone;
            $setting->email = $request->email;
            $setting->footer_text = $request->footer_text;
            $setting->newslatter_text = $request->newslatter_text;
            $setting->headline = $request->headline;

            //favicon
            if ($request->hasFile('favicon')) {
                if (file_exists(public_path('uploads/' . $setting->favicon))) {
                    @unlink(public_path('uploads/' . $setting->favicon));
                }
                $image = $request->file('favicon');
                $filename = 'favicon' . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/'), $filename);
                $setting->favicon = $filename;
            }

            //logo
            if ($request->hasFile('logo')) {
                if (file_exists(public_path('uploads/' . $setting->logo))) {
                    @unlink(public_path('uploads/' . $setting->logo));
                }
                $image = $request->file('logo');
                $filename = 'logo' . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/'), $filename);
                $setting->logo = $filename;
            }

            toast('Basic information updated successfully!', 'success');
        }

        // social media links
        if ($request->update_section == 'social') {
            $setting->facebook = $request->facebook;
            $setting->twitter = $request->twitter;
            $setting->instagram = $request->instagram;
            $setting->youtube = $request->youtube;
            $setting->linkedin = $request->linkedin;
            $setting->pinterest = $request->pinterest;

            toast('Social media links updated successfully!', 'success');
        }

        // seo
        if ($request->update_section == 'seo') {
            $setting->meta_title = $request->meta_title;
            $setting->meta_description = $request->meta_description;
            $setting->meta_keywords = json_encode($request->meta_keywords);

            toast('SEO updated successfully!', 'success');
        }

        // google_map
        if ($request->update_section == 'google_map') {
            $setting->google_map = $request->google_map;
            toast('Google map updated successfully!', 'success');
        }

        $setting->save();
        return redirect()->route('settings.index');
    }
}
