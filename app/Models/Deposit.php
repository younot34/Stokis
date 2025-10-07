<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $fillable = ['warehouse_id', 'nominal'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}
