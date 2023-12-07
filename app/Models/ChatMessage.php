<?php

namespace App\Models;

use App\Enum\MessageStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'message', 'status'];
    protected $casts = ['status'];

    public static function numberOfUnreadChatMessages($user1Id, $user2Id)
    {
        $count = static::where('status', MessageStatusEnum::SENT)
        ->where(
            function ($query) use($user1Id, $user2Id) {
                $query->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
        })
        ->orWhere(
            function ($query) use ($user1Id, $user2Id) {
                $query->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
        })
        ->count();

        return $count;
    }
}
