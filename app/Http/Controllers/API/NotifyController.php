<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Notifications\UserNotify;
use App\Http\Controllers\Controller;


class NotifyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // authenticated user

        // Validate incoming data
        $request->validate([
            'type' => 'nullable|string',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        // Send notification
        $user->notify(new UserNotify($request->only(['type', 'title', 'body'])));

        return response()->json([
            'status' => 'success',
            'message' => 'Notification sent successfully',
            'data' => $user->notifications // database stored notifications
        ]);
    }
}
