<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'parent_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'parent_id'])
            ->useLogName('category')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Category has been {$eventName}");
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    // Subkategori
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Kategori induk
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relasi ke children (subkategori)
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
