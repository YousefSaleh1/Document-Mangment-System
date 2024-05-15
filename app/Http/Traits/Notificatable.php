<?php

namespace App\Http\Traits;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\DocumentResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Notificatable
{
    /**
     * Get the notifications associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany Returns the morphMany relationship for notifications.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notificatable');
    }

    /**
     * Create a new notification for the given model.
     *
     * @param Category|Document|Comment $model The model to create the notification for.
     * @param string $title The title of the notification.
     * @param string $body The body of the notification.
     * @return Notification $notification
     */
    public function notificaionsStore(Category|Document|Comment $model, string $title, string $body)
    {
        $notification = $model->notifications()->create([
            'title' => $title,
            'body'  => $body
        ]);

        return $notification;
    }

    /**
     * Get the notification item resource based on the notificatable type and ID.
     *
     * @param string $notificatableType The type of the notificatable item.
     * @param int $notificatableId The ID of the notificatable item.
     * @return array|string Returns an array with the item type and the corresponding resource, or a string 'Not Found!' if the item is not found.
     */
    public function getNotificationItemResource(string $notificatableType, int $notificatableId)
    {
        switch ($notificatableType) {
            case 'App\Models\Category':
                $category = Category::findOrFail($notificatableId);
                return ['item_type' => 'Category', new CategoryResource($category)];
                break;
            case 'App\Models\Document':
                $document = Document::findOrFail($notificatableId);
                return ['item_type' => 'Document', new DocumentResource($document)];
                break;
            case 'App\Models\Comment':
                $comment = Comment::findOrFail($notificatableId);
                return ['item_type' => 'Comment', new CommentResource($comment)];
                break;
            default:
                return 'Not Found!';
                break;
        }
    }

    public function sendNotification(Notification $notification)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $serverKey = 'AAAAP0GeWyY:APA91bHn-Vt2yZyv9oBDNE2zm85GFriGaxRsc40JXgelsL8S3JgEg1AHS1YDy7EUMHTAmyKbSei8j4CH708mDk7idTK5myq_8KOLnsjv5pfxw_WPxvgSVFgBSsdKLrNumFDzjTB9GCk-';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title"              => $notification->title,
                "body"               => $notification->body,
                "notificatable_id"   => $notification->notificatable_id,
                "notificatable_type" => $notification->notificatable_type,
            ]
        ];

        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }
}
