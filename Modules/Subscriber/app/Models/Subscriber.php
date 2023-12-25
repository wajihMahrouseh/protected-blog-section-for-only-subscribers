<?php

namespace Modules\Subscriber\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Subscriber\Database\factories\SubscriberFactory;
use Modules\User\app\Models\User;

class Subscriber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['status'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
