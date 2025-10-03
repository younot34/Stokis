<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderRecapItem extends Model
{
    protected $fillable = ['purchase_order_recap_id','product_id','quantity_requested','quantity_approved','price'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
