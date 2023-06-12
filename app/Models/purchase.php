<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;
    protected $fillable = (
        [
            'vendor',
            'paidFrom',
            'isPaid',
            'date',
            'desc',
        ]
    );

    public function vendor(){
        return $this->belongsTo(account::class, 'vendor', 'id');
    }

    public function paidFrom(){
        return $this->belongsTo(account::class, 'paidFrom', 'id');
    }

    public function details(){
        return $this->hasMany(purchase_details::class, 'id', 'bill_id');
    }
}
