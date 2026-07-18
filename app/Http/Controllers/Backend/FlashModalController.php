<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FlashModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FlashModalController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-flash-modal',   ['only' => ['index']]);
        $this->middleware('can:create-flash-modal', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-flash-modal',   ['only' => ['edit', 'update']]);
        $this->middleware('can:delete-flash-modal', ['only' => ['destroy']]);
    }

    public function index()
    {
        $flashModals = FlashModal::latest()->paginate(20);
        return view('backend.pages.flash_modal.index', compact('flashModals'));
    }

    public function create()
    {
        return view('backend.pages.flash_modal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'nullable|string|max:150',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link'          => 'nullable|url|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'delay_seconds' => 'required|integer|min:0',
        ]);

        $imagePath = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/flash_modals'), $imageName);
            $imagePath = 'uploads/flash_modals/' . $imageName;
        }

        FlashModal::create([
            'title'         => $request->title,
            'image'         => $imagePath,
            'link'          => $request->link,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'delay_seconds' => $request->delay_seconds,
            'active_status' => $request->has('active_status') ? 1 : 0,
            'created_by'    => auth()->id(),
        ]);

        toast('Flash Modal created successfully!', 'success');
        return redirect()->route('flash-modals.index');
    }

    public function edit($id)
    {
        $flashModal = FlashModal::findOrFail($id);
        return view('backend.pages.flash_modal.edit', compact('flashModal'));
    }

    public function update(Request $request, $id)
    {
        $flashModal = FlashModal::findOrFail($id);

        $request->validate([
            'title'         => 'nullable|string|max:150',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link'          => 'nullable|url|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'delay_seconds' => 'required|integer|min:0',
        ]);

        $imagePath = $flashModal->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if (File::exists(public_path($flashModal->image))) {
                File::delete(public_path($flashModal->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/flash_modals'), $imageName);
            $imagePath = 'uploads/flash_modals/' . $imageName;
        }

        $flashModal->update([
            'title'         => $request->title,
            'image'         => $imagePath,
            'link'          => $request->link,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'delay_seconds' => $request->delay_seconds,
            'active_status' => $request->has('active_status') ? 1 : 0,
            'updated_by'    => auth()->id(),
        ]);

        toast('Flash Modal updated successfully!', 'success');
        return redirect()->route('flash-modals.index');
    }

    public function destroy($id)
    {
        $flashModal = FlashModal::findOrFail($id);

        if (File::exists(public_path($flashModal->image))) {
            File::delete(public_path($flashModal->image));
        }

        $flashModal->delete();

        toast('Flash Modal deleted successfully!', 'success');
        return redirect()->route('flash-modals.index');
    }
}
