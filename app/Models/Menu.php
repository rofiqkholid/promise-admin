<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'scope_id',
        'title',
        'route',
        'icon',
        'sort_order',
        'level',
        'is_active',
        'is_visible',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }

    public static function getSortedHierarchy($scopeId = null, $onlyActive = false)
    {
        $query = self::with('parent');
        if ($onlyActive) {
            $query->where('is_active', 1);
        }
        if ($scopeId) {
            $query->where('scope_id', $scopeId);
        }
        
        $allMenus = $query->get();
        
        // Group by parent_id
        $grouped = $allMenus->groupBy('parent_id');
        
        // Determine root level menus
        $menuIds = $allMenus->pluck('id')->toArray();
        $roots = $allMenus->filter(function($menu) use ($menuIds) {
            return is_null($menu->parent_id) || !in_array($menu->parent_id, $menuIds);
        });
        
        // Sort roots by scope_id, then sort_order, then id
        $roots = $roots->sortBy([
            ['scope_id', 'asc'],
            ['sort_order', 'asc'],
            ['id', 'asc']
        ]);
        
        $result = collect();
        
        $traverse = function($menu) use (&$traverse, $grouped, $result) {
            $result->push($menu);
            $children = $grouped->get($menu->id, collect());
            $sortedChildren = $children->sortBy([
                ['sort_order', 'asc'],
                ['id', 'asc']
            ]);
            foreach ($sortedChildren as $child) {
                $traverse($child);
            }
        };
        
        foreach ($roots as $root) {
            $traverse($root);
        }
        
        return $result;
    }
}
