<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Deposit extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = ['warehouse_id', 'nominal'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['warehouse_id', 'nominal'])
            ->useLogName('deposit')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Deposit has been {$eventName}");
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}
