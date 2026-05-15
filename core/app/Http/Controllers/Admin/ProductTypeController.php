<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductTypeController extends Controller
{
    public function index()
    {
        $pageTitle = __("All Product Types");
        $productTypes = ProductType::orderBy('name')->paginate(getPaginate());
        return view('admin.product_type.index', compact('pageTitle', 'productTypes'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_types,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:product_types,slug,' . $id,
        ]);

        if ($id == 0) {
            $productType = new ProductType();
            $notification = __('Product Type created successfully');
        } else {
            $productType = ProductType::findOrFail($id);
            $notification = __('Product Type updated successfully');
        }

        $productType->name = $request->name;
        $productType->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $productType->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $productType = ProductType::findOrFail($id);
        $productType->delete();

        $notify[] = ['success', __('Product Type deleted successfully')];
        return back()->withNotify($notify);
    }
}
