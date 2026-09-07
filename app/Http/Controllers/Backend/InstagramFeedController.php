<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InstagramFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InstagramFeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-instagram', ['only' => ['index']]);
        $this->middleware('can:create-instagram', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-instagram', ['only' => ['edit', 'update', 'toggleStatus']]);
        $this->middleware('can:delete-instagram', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = InstagramFeed::query();
        $feeds = $query->latest()->paginate(10);
        return view('backend.pages.instagram_feed.index', compact('feeds'));
    }

    public function create()
    {
        return view('backend.pages.instagram_feed.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean'
        ]);

        $feed = new InstagramFeed();
        $feed->link = $request->link ?? 'https://www.instagram.com/';
        $feed->status = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $feed->image = $request->file('image')->store('instagram_feeds', 'public');
        }

        $feed->save();

        toast('Instagram Feed created successfully!', 'success');
        return redirect()->route('instagram-feeds.index');
    }

    public function edit(InstagramFeed $instagramFeed)
    {
        return view('backend.pages.instagram_feed.edit', compact('instagramFeed'));
    }

    public function update(Request $request, InstagramFeed $instagramFeed)
    {
        $request->validate([
            'link' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean'
        ]);

        $instagramFeed->link = $request->link ?? 'https://www.instagram.com/';
        $instagramFeed->status = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            delete_storage_file($instagramFeed->image);
            $instagramFeed->image = $request->file('image')->store('instagram_feeds', 'public');
        }

        $instagramFeed->save();

        toast('Instagram Feed updated successfully!', 'success');
        return redirect()->route('instagram-feeds.index');
    }

    public function destroy(InstagramFeed $instagramFeed)
    {
        delete_storage_file($instagramFeed->image);

        $instagramFeed->delete();
        toast('Instagram Feed deleted successfully!', 'success');
        return redirect()->back();
    }

    public function toggleStatus(Request $request)
    {
        $feed = InstagramFeed::findOrFail($request->id);
        $feed->status = $request->status;
        $feed->save();

        toast('Status updated successfully!', 'success');
        return redirect()->back();
    }
}
