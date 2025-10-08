<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Notice extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'code','warehouse_id','type','note','grand_total','created_by',
        'status', 'jasa_pengiriman',
        'resi_number','customer_name','customer_phone','customer_address','image',
        'shipping_cost'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code','warehouse_id','type','note','grand_total','created_by',
                'status', 'jasa_pengiriman',
                'resi_number','customer_name','customer_phone','customer_address','image',
                'shipping_cost'])
            ->useLogName('notice')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Notice has been {$eventName}");
    }

    public function items() {
        return $this->hasMany(NoticeItem::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
