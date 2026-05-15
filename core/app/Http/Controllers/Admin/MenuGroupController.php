<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuGroup;
use Illuminate\Http\Request;

class MenuGroupController extends Controller
{
    public function index()
    {
        $pageTitle = __('Menu Groups');
        $groups = MenuGroup::latest()->paginate(getPaginate());
        return view('admin.menu.group.index', compact('pageTitle', 'groups'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255|unique:menu_groups,location,' . $id,
            'status' => 'required|in:0,1',
        ]);

        if ($id) {
            $group = MenuGroup::findOrFail($id);
            $notification = __('Menu group updated successfully');
        } else {
            $group = new MenuGroup();
            $notification = __('Menu group added successfully');
        }

        $group->name = $request->name;
        $group->location = strtolower(str_replace(' ', '_', $request->location));
        $group->status = $request->status;
        $group->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $group = MenuGroup::findOrFail($id);
        $group->delete();

        $notify[] = ['success', __('Menu group deleted successfully')];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return MenuGroup::changeStatus($id);
    }
}
