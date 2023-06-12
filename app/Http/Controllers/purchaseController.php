<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\products;
use App\Models\purchase_draft;
use Illuminate\Http\Request;

class purchaseController extends Controller
{
    public function purchase(){
        $vendors = account::where('type', 'Vendor')->get();
        $paidIns = account::where('type', 'Business')->get();
        $products = products::all();
        return view('purchase.purchase')->with(compact('vendors', 'products', 'paidIns'));
    }

    public function StoreDraft(request $req){
        $check = purchase_draft::find($req->product);
        if($check)
        {
            return "Existing";
        }

        purchase_draft::create(
            [
                'product_id' => $req->product,
                'qty' => $req->qty,
                'rate' => $req->rate,
            ]
        );

        return "Done";
    }
}
