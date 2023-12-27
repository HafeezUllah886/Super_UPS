<?php

namespace App\Http\Controllers;

use App\Models\claim;
use App\Models\expense;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\saleReturn;
use App\Models\saleReturnDetails;
use App\Models\scrap_purchase;
use App\Models\scrap_sale;
use App\Models\scrap_stock;
use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{

public function stockAlert()
{
    $products = products::with('category', 'company')->get();
    foreach($products as $product)
    {
        $cr = stock::where("product_id", $product->id)->sum('cr');
        $db = stock::where("product_id", $product->id)->sum('db');

        $product->availStock = $cr - $db;
    }
    return view('reports.stock_alert', compact('products'));
}

}
