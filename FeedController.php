<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activity;

class FeedController extends Controller
{
    public function index(Request $request) {
        return response()->json(
            Activity::with(['user', 'target'])->latest()->get()
        );
    }
}
