<?php

namespace App;

use Service;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = 'service_categories';
    protected $fillable = [
        'name'
    ];

    public function service(){
        return $this->hasMany(Service::class, 'category_id');
    }
   
}
