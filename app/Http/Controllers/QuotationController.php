<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\products;
use App\Models\quotation;
use App\Models\quotationDetails;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function quotation(){
        $quots = quotation::all();
        $accounts = account::where('type', "!=", "Business")->get();
        return view('quotation.quot')->with(compact('quots', 'accounts'));
    }

    public function storeQuotation(request $req){
        $ref = getRef();
        $customer = $req->customer;
        if($req->cusomter == 0)
        {
            $customer = null;
        }
        quotation::create([
            'customer' => $customer,
            'walkIn' => $req->walkIn,
            'phone' => $req->phone,
            'address' => $req->address,
            'desc' => $req->desc,
            'ref' => $ref,
        ]);

        return redirect('/quotation/details/'.$ref);
    }

    public function quotDetails($ref)
    {
        
        $products = products::all();
        $quot = quotation::where('ref', $ref)->first();
        return view('quotation.quot_details')->with(compact('products', 'quot'));
    }

    public function detailsList($ref)
    {
        $items = quotationDetails::where('ref', $ref)->get();
    }
}
