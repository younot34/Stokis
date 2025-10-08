<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @mixin IdeHelperWarehouse
 */
class Warehouse extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'address', 'province', 'city'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'address', 'province', 'city'])
            ->useLogName('stockist')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Stockist has been {$eventName}");
    }
    public function users() {
        return $this->hasMany(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'warehouse_product')->withPivot('quantity')->withTimestamps();
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function purchaseOrders() {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function getTotalAssetAttribute() {
        $normalItems = PurchaseOrderItem::whereHas('purchaseOrder', function($q) {
                $q->where('warehouse_id', $this->id)
                  ->where('status', 'approved');
            })->get();

        $discountItems = PurchaseOrderDiscountItem::whereHas('purchaseOrder', function($q) {
                $q->where('warehouse_id', $this->id)
                  ->where('status', 'approved');
            })->get();

        return $normalItems->sum(fn($i) => $i->quantity_approved * $i->price)
             + $discountItems->sum(fn($i) => $i->quantity_approved * $i->final_price);
    }
}
