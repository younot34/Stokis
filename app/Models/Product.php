<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = ['code','name','parent_id','category_id','price','discount','discount_price'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code','name','category_id','price','discount','discount_price'])
            ->useLogName('product')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Product has been {$eventName}");
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function warehouses() {
        return $this->belongsToMany(Warehouse::class, 'warehouse_product')->withPivot('quantity')->withTimestamps();
    }
    public function getFinalPriceAttribute()
    {
        if ($this->discount_price) {
            return $this->discount_price;
        }
        if ($this->discount) {
            return $this->price - ($this->price * $this->discount / 100);
        }
        return $this->price;
    }
}

