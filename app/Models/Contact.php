<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['contact_user1_id', 'contact_user2_id', 'name'];
    public $with = [];

    /**
    * @return BelongsTo
    * @description Get The contact user2 the Users belongs to
    */
    public function contactUser2()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'profile_photo_path');
    }

    /**
     *
     * @description Get All Contacts For user
     */
    public static function getContacts(int $userId)
    {
        $contacts = static::where('contact_user1_id', $userId)->get();

        return $contacts;
    }
}


