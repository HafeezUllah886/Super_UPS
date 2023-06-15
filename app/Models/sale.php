<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    use HasFactory;
    protected $fillable = (
        [
            'customer',
            'walking',
            'paidIn',
            'isPaid',
            'date',
            'desc',
            'amount',
            'ref'
        ]
    );

    public function customer_account(){
        return $this->belongsTo(account::class, 'customer', 'id');
    }

    public function account(){
        return $this->belongsTo(account::class, 'paidIn', 'id');
    }

    public function details(){
        return $this->hasMany(sale_details::class, 'id', 'bill_id');
    }
}
