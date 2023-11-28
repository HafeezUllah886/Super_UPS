<?php

namespace App\Http\Controllers;

use App\Models\scrap_stock;
use Illuminate\Http\Request;

class ScrapStockController extends Controller
{
    public function index()
    {
        $stocks = scrap_stock::all();

        return view('scrap.stock.index', compact('stocks'));
    }
}
