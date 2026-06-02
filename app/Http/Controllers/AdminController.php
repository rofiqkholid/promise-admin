<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('access')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('nik', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.index', compact('users'));
    }

    public function bulkUpdateAccess(Request $request)
    {
        $data = $request->validate([
            'app' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $users = User::all();
        foreach ($users as $user) {
            UserAccess::updateOrCreate(
                ['id_user' => $user->id],
                [$data['app'] => $data['status']]
            );
        }

        return response()->json(['success' => true, 'message' => 'Bulk access updated successfully']);
    }

    public function updateAccess(Request $request, User $user)
    {
        $data = $request->validate([
            'app' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $access = UserAccess::firstOrCreate(
            ['id_user' => $user->id],
            [
                'app_drawing' => false,
                'app_inventory' => false,
                'app_npc' => false,
                'app_dashboard' => false,
            ]
        );

        $access->{$data['app']} = $data['status'];
        $access->save();

        return response()->json(['success' => true, 'message' => 'Access updated successfully']);
    }
}
