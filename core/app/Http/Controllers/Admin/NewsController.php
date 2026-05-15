<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $search = trim(request()->search);
        
        $query = News::with(['category', 'author']);

        if ($search) {
            $query->where('title', 'like', "%$search%");
        }

        $data['news'] = $query->orderBy('id', 'desc')->paginate(getPaginate());
        $data['pageTitle'] = __('Quản lý Tin Tức');
        return view('admin.news.index', $data);
    }

    public function create()
    {
        $data['categories'] = NewsCategory::orderBy('name')->get();
        $data['pageTitle'] = __('Thêm Tin Tức Mới');
        $data['news'] = null;
        return view('admin.news.create', $data);
    }

    public function edit($id)
    {
        $data['news'] = News::findOrFail($id);
        $data['categories'] = NewsCategory::orderBy('name')->get();
        $data['pageTitle'] = __('Sửa Tin Tức');
        return view('admin.news.create', $data);
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string|max:2000',
            'category_id' => 'nullable|integer|exists:news_categories,id',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            'excerpt.max' => __('Phần tóm tắt không được vượt quá 2000 ký tự.'),
            'title.required' => __('Tiêu đề là bắt buộc.'),
            'title.max' => __('Tiêu đề không được vượt quá 255 ký tự.'),
            'featured_image.image' => __('Ảnh đại diện phải là định dạng hình ảnh hợp lệ.'),
            'featured_image.max' => __('Kích thước ảnh không được vượt quá 2MB.'),
        ]);

        $news = $id ? News::findOrFail($id) : new News();

        $news->title = $request->title;
        
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (News::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = "{$originalSlug}-" . $counter++;
        }
        $news->slug = $slug;
        $news->content = $request->content;
        $news->excerpt = $request->excerpt;
        $news->category_id = $request->category_id;
        $news->is_show_home = $request->is_show_home ? 1 : 0;
        $news->published_at = now();
        
        if (!$id) {
            $news->admin_id = auth()->guard('admin')->id();
            $news->view_count = 0;
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            try {
                $news->featured_image = fileUploader($request->featured_image, getFilePath('news'));
            } catch (\Exception $e) {
                $notify[] = ['error', __('Không thể tải lên ảnh đại diện')];
                return back()->withNotify($notify);
            }
        }

        $news->save();

        $notify[] = ['success', $id ? __('Cập nhật tin tức thành công') : __('Thêm tin tức thành công')];
        return to_route('admin.news.index')->withNotify($notify);
    }

    public function delete(Request $request)
    {
        $news = News::findOrFail($request->id);
        
        if ($news->featured_image) {
            fileManager()->removeFile(getFilePath('news') . '/' . $news->featured_image);
        }
        
        $news->delete();

        $notify[] = ['success', __('Xóa tin tức thành công')];
        return back()->withNotify($notify);
    }

    public function updateHomeStatus($id)
    {
        $news = News::findOrFail($id);
        $news->is_show_home = $news->is_show_home ? 0 : 1;
        $news->save();

        return response()->json([
            'status' => 'success',
            'message' => __('Cập nhật trạng thái thành công')
        ]);
    }

    // ==================== Category Management ====================
    
    public function categoryIndex()
    {
        $data['categories'] = NewsCategory::withCount('news')->orderBy('name')->paginate(getPaginate());
        $data['pageTitle'] = __('Quản lý Danh Mục Tin Tức');
        return view('admin.news.category', $data);
    }

    public function categoryStore(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category = $id ? NewsCategory::findOrFail($id) : new NewsCategory();

        $category->name = $request->name;

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (NewsCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = "{$originalSlug}-" . $counter++;
        }
        $category->slug = $slug;
        $category->save();

        $notify[] = ['success', $id ? __('Cập nhật danh mục thành công') : __('Thêm danh mục thành công')];
        return back()->withNotify($notify);
    }

    public function categoryDelete($id)
    {
        $category = NewsCategory::findOrFail($id);
        
        if ($category->news()->count() > 0) {
            $notify[] = ['error', __('Không thể xóa danh mục đã có tin tức')];
            return back()->withNotify($notify);
        }
        
        $category->delete();

        $notify[] = ['success', __('Xóa danh mục thành công')];
        return back()->withNotify($notify);
    }
}
