<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-subcategory', ['only' => ['index']]);
        $this->middleware('can:create-subcategory', ['only' => ['store']]);
        $this->middleware('can:edit-subcategory', ['only' => ['update']]);
        $this->middleware('can:delete-subcategory', ['only' => ['destroy']]);
    }

    public function index()
    {
        $subcategories = SubCategory::with('category')->orderBy('id', 'desc')->get();
        $categories = Category::where('active_status', 1)->get();
        return view('backend.pages.subcategory.index', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|unique:sub_categories,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            $subcategory = new SubCategory();
            $subcategory->category_id = $request->category_id;
            $subcategory->name = $request->name;
            $subcategory->slug = Str::slug($request->name);
            $subcategory->active_status = $request->has('active_status') ? 1 : 0;
            
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('backend/images');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $name);
                $subcategory->logo = $name;
            }

            $subcategory->save();

            DB::commit();
            toast('Subcategory Created Successfully!', 'success');
        } catch (\Exception $e) {
            toast('Error: ' . $e->getMessage(), 'error');
            DB::rollBack();
        }
        return redirect()->route('subcategories.index');
    }

    public function update(Request $request, SubCategory $subcategory)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|unique:sub_categories,name,' . $subcategory->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            
            $subcategory->category_id = $request->category_id;
            $subcategory->name = $request->name;
            $subcategory->slug = Str::slug($request->name);
            $subcategory->active_status = $request->has('active_status') ? 1 : 0;

            if ($request->hasFile('logo')) {
                if ($subcategory->logo && file_exists(public_path('backend/images/' . $subcategory->logo))) {
                    unlink(public_path('backend/images/' . $subcategory->logo));
                }
                $image = $request->file('logo');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('backend/images');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $name);
                $subcategory->logo = $name;
            }

            $subcategory->save();
            DB::commit();
            toast('Subcategory Updated Successfully!', 'success');
            return redirect()->route('subcategories.index');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->withErrors(['name' => 'The subcategory name has already been taken.'])->withInput();
            }
            toast('Error: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function destroy(SubCategory $subcategory)
    {
        if ($subcategory->products()->count() > 0) {
            toast('Subcategory is assigned to products!', 'error');
            return back();
        }
        if ($subcategory->logo && file_exists(public_path('backend/images/' . $subcategory->logo))) {
            unlink(public_path('backend/images/' . $subcategory->logo));
        }
        if ($subcategory->delete()) {
            toast('Subcategory Deleted Successfully!', 'success');
        } else {
            toast('Something Went Wrong!', 'error');
        }
        return back();
    }
}
