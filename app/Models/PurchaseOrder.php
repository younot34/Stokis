<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPurchaseOrder
 */
class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_code','warehouse_id','requested_by','approved_by','status',
        'jasa_pengiriman',
        'resi_number',
        'image',
    ];

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
    public function requester() {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function approver() {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function items() {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    public function discountItems()
    {
        return $this->hasMany(PurchaseOrderDiscountItem::class);
    }
    public function recap()
    {
        return $this->hasOne(PurchaseOrderRecap::class, 'po_code', 'po_code');
    }

}
