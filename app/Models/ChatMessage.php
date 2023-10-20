<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'message', 'status'];

    public static function numberOfUnreadChatMessages($user1Id, $user2Id)
    {
        $count = static::where('status', config('choices.message_status')['successfully_sent'])
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
