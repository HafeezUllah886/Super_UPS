<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class amount_claim extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(products::class, "productID", 'id');
    }

    public function customer_account(){
        return $this->belongsTo(account::class, 'customer', 'id');
    }

}
