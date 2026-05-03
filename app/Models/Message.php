<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'conversation_id',
        'role',
        'content'
    ];
    protected $touches = ['conversation'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}