<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],

            'statistics' => [
                'total' => 0,
                'menunggu' => 0,
                'diproses' => 0,
                'perbaikan' => 0,
                'selesai' => 0,
                'ditolak' => 0,
            ],
        ]);
    }
}