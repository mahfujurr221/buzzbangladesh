<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SeasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-season',   ['only' => ['index']]);
        $this->middleware('can:create-season', ['only' => ['store']]);
        $this->middleware('can:edit-season',   ['only' => ['update']]);
        $this->middleware('can:delete-season', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Season::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $seasons = $query->latest()->paginate(20)->withQueryString();

        return view('backend.pages.season.index', compact('seasons'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:100|unique:seasons,name',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        $slug = Str::slug($request->name);
        $count = Season::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $season = Season::create([
            'name'         => $request->name,
            'slug'         => $slug,
            'description'  => $request->description,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'active_status'=> $request->has('active_status') ? 1 : 0,
            'created_by'   => auth()->id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Season created successfully!',
                'data'    => $season,
            ]);
        }

        toast('Season created successfully!', 'success');
        return back();
    }

    public function update(Request $request, $id)
    {
        $season = Season::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:100|unique:seasons,name,' . $id,
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        $slug = $season->name !== $request->name ? Str::slug($request->name) : $season->slug;

        $season->update([
            'name'         => $request->name,
            'slug'         => $slug,
            'description'  => $request->description,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'active_status'=> $request->has('active_status') ? 1 : 0,
            'updated_by'   => auth()->id(),
        ]);

        toast('Season updated successfully!', 'success');
        return back();
    }

    public function destroy(Season $season)
    {
        if ($season->products()->count() > 0) {
            toast('Season cannot be deleted because it has products associated with it.', 'error');
            return back();
        }

        $season->delete();
        toast('Season deleted successfully!', 'success');
        return back();
    }

    public function toggleStatus(Request $request, Season $season)
    {
        $season->active_status = !$season->active_status;
        $season->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully.',
            'active_status' => $season->active_status,
            'status_label' => $season->status_label,
        ]);
    }
}
