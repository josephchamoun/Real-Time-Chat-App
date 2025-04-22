<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

class Conversation extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_conversation');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
