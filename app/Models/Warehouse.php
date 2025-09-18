<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'warehouse_product')->withPivot('quantity')->withTimestamps();
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
