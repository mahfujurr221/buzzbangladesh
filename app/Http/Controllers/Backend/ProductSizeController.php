<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSize;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ProductSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-size', ['only' => ['index']]);
        $this->middleware('can:create-size', ['only' => ['store']]);
        $this->middleware('can:edit-size', ['only' => ['update']]);
        $this->middleware('can:delete-size', ['only' => ['destroy']]);
    }

    public function index()
    {
        $sizes = ProductSize::orderBy('id', 'desc')->get();
        return view('backend.pages.size.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_sizes,name',
            'body_size' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            $size = new ProductSize();
            $size->name = $request->name;
            $size->body_size = $request->body_size;
            $size->height = $request->height;
            $size->active_status = $request->has('active_status') ? 1 : 0;
            $size->save();

            DB::commit();
            toast('Size Created Successfully!', 'success');
        } catch (\Exception $e) {
            toast('Error: ' . $e->getMessage(), 'error');
            DB::rollBack();
        }
        return redirect()->route('sizes.index');
    }

    public function update(Request $request, ProductSize $size)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:product_sizes,name,' . $size->id,
            'body_size' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            $size->name = $request->name;
            $size->body_size = $request->body_size;
            $size->height = $request->height;
            $size->active_status = $request->has('active_status') ? 1 : 0;
            $size->save();
            DB::commit();
            toast('Size Updated Successfully!', 'success');
            return redirect()->route('sizes.index');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->withErrors(['name' => 'The size name has already been taken.'])->withInput();
            }
            toast('Error: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function destroy(ProductSize $size)
    {
        if ($size->delete()) {
            toast('Size Deleted Successfully!', 'success');
        } else {
            toast('Something Went Wrong!', 'error');
        }
        return back();
    }
}
