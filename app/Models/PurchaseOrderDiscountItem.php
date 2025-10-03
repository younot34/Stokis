<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPurchaseOrder
 */
class PurchaseOrderDiscountItem extends Model
{
    protected $fillable = [
        'purchase_order_id','product_id',
        'quantity_requested','quantity_approved',
        'price','discount','final_price'
    ];
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
