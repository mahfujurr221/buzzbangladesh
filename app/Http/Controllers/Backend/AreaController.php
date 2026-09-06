<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-area')->only('index');
        $this->middleware('can:create-area')->only(['store']);
        $this->middleware('can:edit-area')->only(['edit', 'update']);
        $this->middleware('can:delete-area')->only('destroy');
    }

    public function index(): View
    {
        $area_list = Area::orderBy('name', 'asc')->paginate(15);
        return view('backend.pages.area.index', compact('area_list'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'            => 'required|string|max:191|unique:areas',
            'delivery_charge' => 'required|numeric',
            'status'          => 'required|boolean'
        ]);

        Area::create([
            'name'            => $request->name,
            'delivery_charge' => $request->delivery_charge,
            'status'          => $request->status,
        ]);

        toast('New area added successfully!', 'success');
        return redirect()->back();
    }

    public function edit(int $id): View
    {
        $area = Area::findOrFail($id);
        $area_list = Area::orderBy('name', 'asc')->paginate(15);

        return view('backend.pages.area.index', compact('area', 'area_list'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $area = Area::findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:191|unique:areas,name,' . $id,
            'delivery_charge' => 'required|numeric',
            'status'          => 'required|boolean'
        ]);

        $area->update([
            'name'            => $request->name,
            'delivery_charge' => $request->delivery_charge,
            'status'          => $request->status,
        ]);

        toast('Area updated successfully!', 'success');
        return redirect()->route('areas.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $area = Area::findOrFail($id);

        if ($area->default) {
            toast('Ops sorry! Default area cannot be deleted.', 'warning');
            return redirect()->back();
        }

        $area->delete();

        toast('Area deleted successfully!', 'success');
        return redirect()->back();
    }
}
