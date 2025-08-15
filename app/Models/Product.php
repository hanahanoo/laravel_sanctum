<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'desc', 'price', 'stock', 'foto', 'id_category']; 

    public function category ()
     {
        return $this->belongsTo(category::class, 'id_category');
     }

     public function order ()
     {
      return $this->hasMany(order::class, 'id_product');
     }
}
