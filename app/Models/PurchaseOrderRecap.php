<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderRecap extends Model
{
    protected $fillable = ['po_code','warehouse_id','requested_by','approved_by','status'];

    public function items() {
        return $this->hasMany(PurchaseOrderRecapItem::class);
    }
    public function discountItems() {
        return $this->hasMany(PurchaseOrderRecapDiscountItem::class);
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
    public function requester() {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function approver() {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

