<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index($groupId)
    {
        $group = MenuGroup::findOrFail($groupId);
        $pageTitle = __('Menu Items') . ' - ' . $group->name;
        $items = MenuItem::where('menu_group_id', $groupId)->where('parent_id', 0)->with('children')->orderBy('order')->get();
        $allParents = MenuItem::where('menu_group_id', $groupId)->get();
        
        return view('admin.menu.item.index', compact('pageTitle', 'group', 'items', 'allParents'));
    }

    public function store(Request $request, $groupId, $id = 0)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'url'           => 'nullable|string|max:255',
            'parent_id'     => 'required|integer',
            'order'         => 'required|integer',
            'status'        => 'required|in:0,1',
            'has_mega_menu' => 'nullable',
        ]);

        if ($id) {
            $item = MenuItem::findOrFail($id);
            $notification = __('Menu item updated successfully');
        } else {
            $item = new MenuItem();
            $item->menu_group_id = $groupId;
            $notification = __('Menu item added successfully');
        }

        $item->title         = $request->title;
        $item->url           = $request->url;
        $item->parent_id     = $request->parent_id;
        $item->order         = $request->order;
        $item->status        = $request->status;
        $item->has_mega_menu = $request->has_mega_menu ? 1 : 0;
        $item->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function destroy($groupId, $id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();

        $notify[] = ['success', __('Menu item deleted successfully')];
        return back()->withNotify($notify);
    }

    public function status($groupId, $id)
    {
        return MenuItem::changeStatus($id);
    }
}
