<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @mixin IdeHelperTransaction
 */
class Transaction extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = ['code','warehouse_id','type','note','grand_total','created_by'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code','warehouse_id','type','note','grand_total','created_by'])
            ->useLogName('transaction')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Transaction has been {$eventName}");
    }
    public function items() {
        return $this->hasMany(TransactionItem::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}

