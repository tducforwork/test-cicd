<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle  = __("All Categories");
        $categories = Category::isParent()->with('allSubcategories')->withCount('products')->orderBy('name')->paginate(getPaginate());
        $allCategories = Category::orderBy('name')->get();
        return view('admin.category.index', compact('pageTitle', 'categories', 'allCategories'));
    }



    public function store(Request $request, $id = 0)
    {
        $this->validation($request, $id);

        if ($this->categoryExists($request, $id)) {
            $notify[] = ['error', __('The name has already been taken')];
            return back()->withNotify($notify)->withInput();
        }

        $category = $id ? Category::findOrFail($id) : new Category();
        $category->name = $request->name;
        $category->parent_id = $request->parent_id ? $request->parent_id : null;
        $category->slug = $request->slug ? \Illuminate\Support\Str::slug($request->slug) : \Illuminate\Support\Str::slug($request->name);

        if ($request->hasFile('image')) {
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $category->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', __("Couldn't upload your image")];
                return back()->withNotify($notify);
            }
        }

        $category->bg_color = $request->bg_color;
        $category->icon_color = $request->icon_color;
        $category->icon = $request->icon;

        $category->save();

        $notification = $id ? __('Category updated successfully') : __('Category added successfully');
        $notify[] = ['success', $notification];
        
        return back()->withNotify($notify);
    }

    public function delete(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
            $notify[] = ['error', __('Cannot delete this category. It is assigned to one or more products.')];
            return back()->withNotify($notify);
        }

        $category->delete();
        $notify[] = ['success', __('Category deleted successfully')];
        return back()->withNotify($notify);
    }

    protected function categoryExists(Request $request, $id)
    {
        return Category::where('id', '!=', $id)->where('name', $request->name)->exists();
    }

    public function categoryById($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->image_path = $category->categoryImage();
            return response()->json(['category' => $category]);
        }
        return response('Category not found', '404');
    }

    protected function validation($request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'slug'      => 'nullable|string|max:255',
            'image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'svg'])],
            'icon'      => 'nullable|string',
            'icon_color'=> 'nullable|string',
            'bg_color'  => 'nullable|string',
        ]);
    }

    public function updateStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->show_on_home = $category->show_on_home ? 0 : 1;
        $category->save();

        $notify[] = ['success', __('Status updated successfully')];
        return back()->withNotify($notify);
    }
}
