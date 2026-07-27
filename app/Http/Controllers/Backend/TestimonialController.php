<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-testimonial',   ['only' => ['index']]);
        $this->middleware('can:create-testimonial', ['only' => ['store']]);
        $this->middleware('can:edit-testimonial',   ['only' => ['update', 'toggleStatus']]);
        $this->middleware('can:delete-testimonial', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Testimonial::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%')
                  ->orWhere('comment', 'like', '%' . $request->search . '%');
        }

        $testimonials = $query->latest()->paginate(20)->withQueryString();

        return view('backend.pages.testimonial.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:100',
            'title'      => 'nullable|string|max:255',
            'comment'    => 'required|string',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        $testimonial = Testimonial::create([
            'name'          => $request->name,
            'title'         => $request->title,
            'comment'       => $request->comment,
            'rating'        => $request->rating,
            'active_status' => $request->has('active_status') ? 1 : 0,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Testimonial created successfully!',
                'data'    => $testimonial,
            ]);
        }

        toast('Testimonial created successfully!', 'success');
        return back();
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:100',
            'title'      => 'nullable|string|max:255',
            'comment'    => 'required|string',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return back()->withInput();
        }

        $testimonial->update([
            'name'          => $request->name,
            'title'         => $request->title,
            'comment'       => $request->comment,
            'rating'        => $request->rating,
            'active_status' => $request->has('active_status') ? 1 : 0,
        ]);

        toast('Testimonial updated successfully!', 'success');
        return back();
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        toast('Testimonial deleted successfully!', 'success');
        return back();
    }

    public function toggleStatus(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->active_status = !$testimonial->active_status;
        $testimonial->save();

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Testimonial status updated successfully!']);
        }

        toast('Testimonial status updated successfully!', 'success');
        return back();
    }
}
