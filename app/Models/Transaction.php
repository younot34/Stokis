<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['code','warehouse_id','type','note','created_by'];

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

