<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPurchaseOrderItem
 */
class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_order_id','product_id','quantity_requested','quantity_approved','price'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
