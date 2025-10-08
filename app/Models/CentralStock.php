<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @mixin IdeHelperCentralStock
 */
class CentralStock extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['product_id', 'quantity'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'quantity'])
            ->useLogName('centralStock')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Central Stock has been {$eventName}");
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

