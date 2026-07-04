<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-customer', ['only' => ['index']]);
        $this->middleware('can:create-customer', ['only' => ['create', 'store']]);
        $this->middleware('can:edit-customer', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete-customer', ['only' => ['destroy']]);
    }

    public function index()
    {
        $customers = Customer::latest()->paginate(20);
        return view('backend.pages.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('backend.pages.customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:100',
            'city' => 'nullable|string|max:100',
            'thana' => 'nullable|string|max:100',
            'full_address' => 'nullable|string|max:500',
        ]);

        Customer::create($request->all());

        toast('Customer added successfully!', 'success');
        return redirect()->route('customers.index');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('backend.pages.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|max:100',
            'city' => 'nullable|string|max:100',
            'thana' => 'nullable|string|max:100',
            'full_address' => 'nullable|string|max:500',
        ]);

        $customer->update($request->all());

        toast('Customer updated successfully!', 'success');
        return redirect()->route('customers.index');
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            toast('Customer deleted successfully!', 'success');
        } catch (\Exception $e) {
            toast('Cannot delete customer, they may be linked to an order.', 'error');
        }
        
        return back();
    }
}
