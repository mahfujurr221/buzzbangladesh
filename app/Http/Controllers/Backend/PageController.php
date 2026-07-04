<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-page', ['only' => ['index']]);
        $this->middleware('can:create-page', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-page', ['only' => ['edit', 'update', 'toggleStatus']]);
        $this->middleware('can:delete-page', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('slug', 'like', "%{$request->search}%");
        }

        $pages = $query->latest()->paginate(10);
        return view('backend.pages.page.index', compact('pages'));
    }

    public function create()
    {
        return view('backend.pages.page.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);

        $page = new Page();
        $page->title = $request->title;
        // Ensure slug is formatted correctly
        $page->slug = Str::slug($request->slug);
        $page->content = $request->content;
        $page->status = $request->has('status') ? 1 : 0;

        $page->save();

        toast('Page created successfully!', 'success');
        return redirect()->route('pages.index');
    }

    public function edit(Page $page)
    {
        return view('backend.pages.page.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);

        $page->title = $request->title;
        $page->slug = Str::slug($request->slug);
        $page->content = $request->content;
        $page->status = $request->has('status') ? 1 : 0;

        $page->save();

        toast('Page updated successfully!', 'success');
        return redirect()->route('pages.index');
    }

    public function toggleStatus(Request $request)
    {
        $page = Page::findOrFail($request->id);
        $page->status = !$page->status;
        $page->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Page status updated successfully!'
        ]);
    }

    public function destroy(Page $page)
    {
        $page->delete();
        toast('Page deleted successfully!', 'success');
        return back();
    }
}
