<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $pageTitle = 'Quản lý Tag sản phẩm';
        $tags = Tag::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.tag.index', compact('pageTitle', 'tags'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'type' => 'required|in:orange,green,red,purple',
        ]);

        if ($id) {
            $tag = Tag::findOrFail($id);
            $notification = 'Cập nhật tag thành công';
        } else {
            $tag = new Tag();
            $notification = 'Thêm tag mới thành công';
        }

        $tag->name = $request->name;
        $tag->type = $request->type;
        $tag->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->products()->detach();
        $tag->delete();

        $notify[] = ['success', 'Xóa tag thành công'];
        return back()->withNotify($notify);
    }
}
