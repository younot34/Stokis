<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTransactionItem
 */
class TransactionItem extends Model
{
    protected $fillable = [
    'transaction_id','product_id','product_code','product_name',
    'category_name','subcategory_name',
    'quantity','price','total_price','note'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}

