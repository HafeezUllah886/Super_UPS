<?php

namespace App\Http\Controllers;

use App\Models\saleReturn;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    public function index(){
        $saleReturns = saleReturn::orderBy('id', 'desc')->get();

        return view('sale.return')->with(compact('saleReturns'));
    }
}
