<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')->latest();

        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }
        if ($cari = $request->query('cari')) {
            $query->where(fn ($q) => $q->where('user_name', 'like', "%{$cari}%")
                ->orWhere('subject_label', 'like', "%{$cari}%"));
        }

        return view('admin.activity.index', [
            'logs' => $query->paginate(30)->withQueryString(),
        ]);
    }
}
