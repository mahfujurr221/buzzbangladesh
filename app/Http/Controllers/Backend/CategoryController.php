<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-category', ['only' => ['index']]);
        $this->middleware('can:create-category', ['only' => ['store']]);
        $this->middleware('can:edit-category', ['only' => ['update']]);
        $this->middleware('can:delete-category', ['only' => ['destroy']]);
    }

    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('backend.pages.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            $category = new Category();
            $category->name = $request->name;
            $category->active_status = $request->has('active_status') ? 1 : 0;
            
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('backend/images');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $name);
                $category->logo = $name;
            }

            $category->save();

            DB::commit();
            toast('Category Created Successfully!', 'success');
        } catch (\Exception $e) {
            toast('Error: ' . $e->getMessage(), 'error');
            DB::rollBack();
        }
        return redirect()->route('categories.index');
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,' . $category->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            
            $category->name = $request->name;
            $category->active_status = $request->has('active_status') ? 1 : 0;

            if ($request->hasFile('logo')) {
                if ($category->logo && file_exists(public_path('backend/images/' . $category->logo))) {
                    unlink(public_path('backend/images/' . $category->logo));
                }
                $image = $request->file('logo');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('backend/images');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $name);
                $category->logo = $name;
            }

            $category->save();
            DB::commit();
            toast('Category Updated Successfully!', 'success');
            return redirect()->route('categories.index');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->withErrors(['name' => 'The category name has already been taken.'])->withInput();
            }
            toast('Error: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function destroy(Category $category)
    {
        if ($category->subCategories()->count() > 0 || $category->products()->count() > 0) {
            toast('Category is in use and cannot be deleted!', 'error');
            return back();
        }
        if ($category->logo && file_exists(public_path('backend/images/' . $category->logo))) {
            unlink(public_path('backend/images/' . $category->logo));
        }
        if ($category->delete()) {
            toast('Category Deleted Successfully!', 'success');
        } else {
            toast('Something Went Wrong!', 'error');
        }
        return back();
    }
}
