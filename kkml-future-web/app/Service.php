<?php

namespace App;

use App\User;
use App\ServiceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'added_by', 'user_id', 'video_provider', 'video_link', 'choice_options', 'thumbnail_img', 'description', 'address'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function service_category() {
        return $this->belongsTo(ServiceCategory::class, 'id');
    }

}
