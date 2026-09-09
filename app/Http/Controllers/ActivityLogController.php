<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(15);

        return view('activity-log.index', compact('activities'));
    }
}
