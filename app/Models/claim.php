<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class claim extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bill()
    {
        return $this->belongsTo(sale::class, 'salesID', 'id');
    }

    public function product()
    {
        return $this->belongsTo(products::class, "productID", 'id');
    }
    
}
