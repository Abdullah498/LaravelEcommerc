<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function getPhotoAttribute($value){
        return url('/').'/images/products/'.($value);
    }
}
