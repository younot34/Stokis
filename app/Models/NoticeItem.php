<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeItem extends Model
{
    use HasFactory;

    protected $fillable = [
    'notice_id','product_id','product_code','product_name',
    'category_name','subcategory_name',
    'quantity','price','total_price','note'
    ];

    public function notice() {
        return $this->belongsTo(Notice::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
