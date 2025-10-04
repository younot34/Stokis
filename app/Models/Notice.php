<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','warehouse_id','type','note','grand_total','created_by',
        'status', 'jasa_pengiriman',
        'resi_number','customer_name','customer_phone','customer_address','image'
    ];

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
