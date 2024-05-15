<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;;

use App\Http\Resources\NotificationResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $notifications = $user->notifications()->get();

        $data = NotificationResource::collection($notifications);
        return $this->customeResponse($data, 'Done!', 200);
    }

    public function getNotificationUnRead()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $unreadNotifications = $user->notifications()
            ->wherePivot('is_read', false)
            ->get();
        $data = NotificationResource::collection($unreadNotifications);
        return $this->customeResponse($data, 'Done!', 200);
    }

    public function read(Notification $notification)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->notifications()->updateExistingPivot($notification, [
            'is_read' => true,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Done!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json(['message' => 'Notification Deleted'], 200);
    }
}
