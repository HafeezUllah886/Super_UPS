<?php

namespace App\Http\Controllers;

use App\Models\sale;
use App\Models\saleReturn;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    public function index(){
        $saleReturns = saleReturn::orderBy('id', 'desc')->get();

        return view('sale.return')->with(compact('saleReturns'));
    }

    public function search(request $req){
        $bill = sale::find($req->bill);
        if($bill){
            $saleReturns = saleReturn::where('bill_id', $req->bill)->first();
            if($saleReturns){
                return back()->with('error', 'Already Returned');
            }
            return view('sale.createReturn')->with(compact('bill'));
        }
        return back()->with('error', 'Bill Not Found');
    }
}
