<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use Illuminate\Database\QueryException;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-brand', ['only' => ['index']]);
        $this->middleware('can:create-brand', ['only' => ['store']]);
        $this->middleware('can:edit-brand', ['only' => ['update']]);
        $this->middleware('can:delete-brand', ['only' => ['destroy']]);
    }

    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->get();
        return view('backend.pages.brand.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->active_status = $request->has('active_status') ? 1 : 0;
            
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('backend/images');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $name);
                $brand->logo = $name;
            }

            $brand->save();

            DB::commit();
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Brand Created Successfully!',
                    'data' => $brand
                ]);
            }
            toast('Brand Created Successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            toast('Error: ' . $e->getMessage(), 'error');
        }
        return redirect()->route('brands.index');
    }

    public function update(Request $request, Brand $brand)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|unique:brands,name,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            
            $brand->name = $request->name;
            $brand->active_status = $request->has('active_status') ? 1 : 0;

            if ($request->hasFile('logo')) {
                if ($brand->logo && file_exists(public_path('backend/images/' . $brand->logo))) {
                    unlink(public_path('backend/images/' . $brand->logo));
                }
                $image = $request->file('logo');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('backend/images');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $name);
                $brand->logo = $name;
            }

            $brand->save();
            DB::commit();
            toast('Brand Updated Successfully!', 'success');
            return redirect()->route('brands.index');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->withErrors(['name' => 'The brand name has already been taken.'])->withInput();
            }
            toast('Error: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            toast('Brand is assigned to products!', 'error');
            return back();
        }
        if ($brand->logo && file_exists(public_path('backend/images/' . $brand->logo))) {
            unlink(public_path('backend/images/' . $brand->logo));
        }
        if ($brand->delete()) {
            toast('Brand Deleted Successfully!', 'success');
        } else {
            toast('Something Went Wrong!', 'error');
        }
        return back();
    }
}
