<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function scopeFilter($query,$filters){
         return $query->when($filters['search']??false,function ($query,$search){
           return $query->where('name','like','%'.$search.'%');
        });
    }
}
