<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['id_user', 'id_product', 'qty', 'price']; 

        public function product ()
        {
            return $this->hasMany(product::class, 'id_product');
        }

        public function user ()
        {
            return $this->belongsTo(product::class, 'id_user');
        }
}
