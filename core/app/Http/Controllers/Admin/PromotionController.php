<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $pageTitle = __('All Promotions');
        $promotions = Promotion::withCount('products')->latest()->paginate(getPaginate());
        return view('admin.promotions.index', compact('pageTitle', 'promotions'));
    }

    public function create()
    {
        $pageTitle = __('Create Promotion');
        return view('admin.promotions.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'discount_type'  => 'required|in:1,2',
            'discount_value' => 'required|numeric|min:0',
            'product_ids'    => 'required|array',
            'product_ids.*'  => 'exists:products,id'
        ]);

        $promotion = Promotion::create([
            'name'           => $request->name,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'status'         => 1
        ]);

        $promotion->products()->attach($request->product_ids);

        $notify[] = ['success', __('Promotion created successfully')];
        return to_route('admin.promotions.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $promotion = Promotion::with('products')->findOrFail($id);
        $pageTitle = __('Edit Promotion') . ': ' . $promotion->name;
        return view('admin.promotions.edit', compact('pageTitle', 'promotion'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'discount_type'  => 'required|in:1,2',
            'discount_value' => 'required|numeric|min:0',
            'product_ids'    => 'required|array',
            'product_ids.*'  => 'exists:products,id'
        ]);

        $promotion->update([
            'name'           => $request->name,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
        ]);

        $promotion->products()->sync($request->product_ids);

        $notify[] = ['success', __('Promotion updated successfully')];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Promotion::changeStatus($id);
    }

    public function products(Request $request)
    {
        $search = $request->search;
        $products = Product::active();

        if ($search) {
            $products = $products->where('name', 'like', "%$search%");
        }

        $products = $products->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'products' => $products->map(function($p) {
                return [
                    'id'    => $p->id,
                    'name'  => $p->name,
                    'price' => showAmount($p->base_price),
                    'image' => $p->productImage()
                ];
            }),
            'has_more' => $products->hasMorePages()
        ]);
    }
}
