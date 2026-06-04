<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Scope;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $scopes = Scope::where('is_active', 1)->get();
        $allScopes = Scope::orderBy('scope_name')->get();
        // Load all menus with their parent info, sorted hierarchically
        $menus = Menu::getSortedHierarchy();

        // Get potential parent menus (usually level 1 menus or menus with no route, or we allow any)
        $parentMenus = Menu::where('is_active', 1)
            ->where(function($q) {
                $q->whereNull('parent_id')
                  ->orWhere('route', '#');
            })
            ->orderBy('title')
            ->get();

        return view('admin.menus', compact('menus', 'scopes', 'parentMenus', 'allScopes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'parent_id' => 'nullable|integer|exists:menus,id',
            'scope_id' => 'required|string|exists:scopes,id',
            'is_active' => 'required|boolean',
            'is_visible' => 'required|boolean',
        ]);

        // Calculate level
        $level = 1;
        if (!empty($data['parent_id'])) {
            $parent = Menu::find($data['parent_id']);
            if ($parent) {
                $level = $parent->level + 1;
            }
        }
        $data['level'] = $level;

        Menu::create($data);

        return response()->json(['success' => true, 'message' => 'Menu created successfully']);
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'parent_id' => 'nullable|integer|exists:menus,id|different:id',
            'scope_id' => 'required|string|exists:scopes,id',
            'is_active' => 'required|boolean',
            'is_visible' => 'required|boolean',
        ]);

        // Calculate level
        $level = 1;
        if (!empty($data['parent_id'])) {
            $parent = Menu::find($data['parent_id']);
            if ($parent) {
                $level = $parent->level + 1;
            }
        }
        $data['level'] = $level;

        $menu->update($data);

        return response()->json(['success' => true, 'message' => 'Menu updated successfully']);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json(['success' => true, 'message' => 'Menu deleted successfully']);
    }

    public function storeScope(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|max:50|unique:scopes,id',
            'scope_name' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        Scope::create($data);

        return response()->json(['success' => true, 'message' => 'Scope created successfully']);
    }

    public function destroyScope($id)
    {
        $scope = Scope::findOrFail($id);
        $scope->delete();

        return response()->json(['success' => true, 'message' => 'Scope deleted successfully']);
    }

    public function updateScope(Request $request, $id)
    {
        $data = $request->validate([
            'scope_name' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        $scope = Scope::findOrFail($id);
        $scope->update($data);

        return response()->json(['success' => true, 'message' => 'Scope updated successfully']);
    }

    public function ajaxList(Request $request)
    {
        $scopeId = $request->input('scope_id');
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value');

        // Fetch all sorted hierarchical menus for this scope
        $menus = Menu::getSortedHierarchy($scopeId);

        $totalRecords = $menus->count();

        // Apply server-side search filter
        if (!empty($search)) {
            $menus = $menus->filter(function($menu) use ($search) {
                return stripos($menu->title, $search) !== false
                    || stripos($menu->route, $search) !== false;
            });
        }

        $filteredRecords = $menus->count();

        // Paginate the collection using slice
        if ($length != -1) {
            $data = $menus->slice($start, $length)->values();
        } else {
            $data = $menus->values();
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
}
