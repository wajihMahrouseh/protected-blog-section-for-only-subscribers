<?php

namespace Modules\Blog\app\Models;

use Modules\User\app\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Blog\Database\factories\BlogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['title', 'content', 'publish_date', 'status', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
