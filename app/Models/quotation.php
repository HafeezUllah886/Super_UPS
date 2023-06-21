<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    use HasFactory;
    protected $fillable = (
        [
            'customer',
            'date',
            'walkIn',
            'discount',
            'phone',
            'address',
            'desc',
            'ref',
        ]
    );

    public function customer_account(){
        return $this->belongsTo(account::class, 'customer');
    }
}
