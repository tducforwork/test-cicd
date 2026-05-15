<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FilterGroup;
use App\Models\FilterOption;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index()
    {
        $pageTitle = __('Manage Filter Groups');
        $groups = FilterGroup::withCount('options')->orderBy('sort_order')->paginate(getPaginate());
        return view('admin.filter.index', compact('pageTitle', 'groups'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:0,1',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($id) {
            $group = FilterGroup::findOrFail($id);
            $notify[] = ['success', __('Filter group updated successfully')];
        } else {
            $group = new FilterGroup();
            $notify[] = ['success', __('Filter group created successfully')];
        }

        $group->name = $request->name;
        $group->status = $request->status;
        $group->sort_order = $request->sort_order;
        $group->save();

        return back()->withNotify($notify);
    }

    public function options($id)
    {
        $group = FilterGroup::findOrFail($id);
        $pageTitle = __('Manage Options for') . ': ' . $group->name;
        $options = $group->options()->paginate(getPaginate());
        return view('admin.filter.options', compact('pageTitle', 'group', 'options'));
    }

    public function optionStore(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $group = FilterGroup::findOrFail($id);
        
        $option = new FilterOption();
        $option->filter_group_id = $group->id;
        $option->value = $request->value;
        $option->save();

        $notify[] = ['success', __('Filter option added successfully')];
        return back()->withNotify($notify);
    }

    public function optionUpdate(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $option = FilterOption::findOrFail($id);
        $option->value = $request->value;
        $option->save();

        $notify[] = ['success', __('Filter option updated successfully')];
        return back()->withNotify($notify);
    }

    public function optionDelete($id)
    {
        $option = FilterOption::findOrFail($id);
        $option->delete();

        $notify[] = ['success', __('Filter option deleted successfully')];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $group = FilterGroup::findOrFail($id);
        $group->delete();

        $notify[] = ['success', __('Filter group deleted successfully')];
        return back()->withNotify($notify);
    }
}
