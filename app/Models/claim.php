<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class claim extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function returnProduct()
    {
        return $this->belongsTo(products::class, 'return_product', 'id');
    }

    public function issueProduct()
    {
        return $this->belongsTo(products::class, 'issue_product', 'id');
    }

    public function vendorDetails()
    {
        return $this->belongsTo(account::class, 'vendor', 'id');
    }
}
