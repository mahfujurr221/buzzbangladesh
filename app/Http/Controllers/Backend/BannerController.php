<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-banner', ['only' => ['index']]);
        $this->middleware('can:create-banner', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-banner', ['only' => ['edit', 'update', 'toggleStatus']]);
        $this->middleware('can:delete-banner', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('subtitle', 'like', "%{$request->search}%");
        }

        $banners = $query->latest()->paginate(10);
        return view('backend.pages.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('backend.pages.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean'
        ]);

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->button_text = $request->button_text;
        $banner->button_link = $request->button_link;
        $banner->status = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/banners'), $imageName);
            $banner->image = 'uploads/banners/' . $imageName;
        }

        $banner->save();

        toast('Banner created successfully!', 'success');
        return redirect()->route('banners.index');
    }

    public function edit(Banner $banner)
    {
        return view('backend.pages.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean'
        ]);

        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->button_text = $request->button_text;
        $banner->button_link = $request->button_link;
        $banner->status = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            // Delete old image
            if (File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/banners'), $imageName);
            $banner->image = 'uploads/banners/' . $imageName;
        }

        $banner->save();

        toast('Banner updated successfully!', 'success');
        return redirect()->route('banners.index');
    }

    public function toggleStatus(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $banner->status = !$banner->status;
        $banner->save();

        toast('Banner status updated successfully!', 'success');
        return redirect()->back();
    }

    public function destroy(Banner $banner)
    {
        if (File::exists(public_path($banner->image))) {
            File::delete(public_path($banner->image));
        }
        $banner->delete();

        toast('Banner deleted successfully!', 'success');
        return back();
    }
}
