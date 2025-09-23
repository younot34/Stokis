<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'parent_id', 'category_id', 'price'];

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
}

