<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Validator;

class OrderStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-order-status', ['only' => ['index']]);
        $this->middleware('can:create-order-status', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-order-status', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete-order-status', ['only' => ['destroy']]);
    }

    public function index()
    {
        $statuses = OrderStatus::orderBy('id', 'asc')->get();
        return view('backend.pages.order_status.index', compact('statuses'));
    }

    public function create()
    {
        return view('backend.pages.order_status.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:order_statuses,name',
            'color_code' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        try {
            DB::beginTransaction();
            
            $isDefault = $request->has('is_default');
            
            if ($isDefault) {
                OrderStatus::where('is_default', true)->update(['is_default' => false]);
            }

            $status = new OrderStatus();
            $status->name = $request->name;
            $status->color_code = $request->color_code;
            $status->is_default = $isDefault;
            $status->save();

            DB::commit();
            toast('Order Status Created Successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Error: ' . $e->getMessage(), 'error');
            return back()->withInput();
        }

        return redirect()->route('order-statuses.index');
    }

    public function edit(OrderStatus $orderStatus)
    {
        return view('backend.pages.order_status.edit', compact('orderStatus'));
    }

    public function update(Request $request, OrderStatus $orderStatus)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:order_statuses,name,' . $orderStatus->id,
            'color_code' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        try {
            DB::beginTransaction();
            
            $isDefault = $request->has('is_default');
            
            if ($isDefault) {
                OrderStatus::where('id', '!=', $orderStatus->id)->where('is_default', true)->update(['is_default' => false]);
            }

            $orderStatus->name = $request->name;
            $orderStatus->color_code = $request->color_code;
            $orderStatus->is_default = $isDefault;
            $orderStatus->save();

            DB::commit();
            toast('Order Status Updated Successfully!', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            toast('Error: ' . $e->getMessage(), 'error');
            return back()->withInput();
        }

        return redirect()->route('order-statuses.index');
    }

    public function destroy(OrderStatus $orderStatus)
    {
        if ($orderStatus->is_default) {
            toast('Cannot delete the default order status!', 'error');
            return back();
        }

        try {
            $orderStatus->delete();
            toast('Order Status Deleted Successfully!', 'success');
        } catch (\Exception $e) {
            toast('Error: ' . $e->getMessage(), 'error');
        }
        
        return redirect()->route('order-statuses.index');
    }
}
