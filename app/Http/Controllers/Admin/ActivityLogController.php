<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer');

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter berdasarkan modul / log_name
        if ($request->filled('module')) {
            $query->where('log_name', $request->module);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search berdasarkan description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        // Ambil list user & modul untuk filter dropdown
        $users = \App\Models\User::orderBy('name')->get();
        $modules = Activity::select('log_name')->distinct()->pluck('log_name');

        return view('admin.activity_logs.index', compact('logs', 'users', 'modules'));
    }
}
