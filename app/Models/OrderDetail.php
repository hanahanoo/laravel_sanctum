<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = ['id_order', 'id_product', 'qty', 'price'];

    public function order() {
        return $this->belongsTo(order::class, 'id_order');
    }

    public function product() {
        return $this->belongsTo(product::class, 'id_product');
    }
}
