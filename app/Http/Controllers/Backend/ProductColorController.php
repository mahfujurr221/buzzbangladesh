<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductColor;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ProductColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-color', ['only' => ['index']]);
        $this->middleware('can:create-color', ['only' => ['store']]);
        $this->middleware('can:edit-color', ['only' => ['update']]);
        $this->middleware('can:delete-color', ['only' => ['destroy']]);
    }

    public function index()
    {
        $colors = ProductColor::orderBy('id', 'desc')->get();
        return view('backend.pages.color.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_colors,name',
            'code' => 'required|string|max:50',
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
            $color = new ProductColor();
            $color->name = $request->name;
            $color->code = $request->code;
            $color->active_status = $request->has('active_status') ? 1 : 0;
            $color->save();

            DB::commit();
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Color Created Successfully!',
                    'data' => $color
                ]);
            }
            toast('Color Created Successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            toast('Error: ' . $e->getMessage(), 'error');
        }
        return redirect()->route('colors.index');
    }

    public function update(Request $request, ProductColor $color)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:product_colors,name,' . $color->id,
            'code' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }
        try {
            DB::beginTransaction();
            $color->name = $request->name;
            $color->code = $request->code;
            $color->active_status = $request->has('active_status') ? 1 : 0;
            $color->save();
            DB::commit();
            toast('Color Updated Successfully!', 'success');
            return redirect()->route('colors.index');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->withErrors(['name' => 'The color name has already been taken.'])->withInput();
            }
            toast('Error: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function destroy(ProductColor $color)
    {
        if ($color->delete()) {
            toast('Color Deleted Successfully!', 'success');
        } else {
            toast('Something Went Wrong!', 'error');
        }
        return back();
    }
}
