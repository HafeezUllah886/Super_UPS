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
        if($req->customer == "0")
        {
            $customer = null;
        }
        quotation::create([
            'customer' => $customer,
            'walkIn' => $req->walkIn,
            'phone' => $req->phone,
            'address' => $req->address,
            'validTill' => $req->valid,
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
        return view('quotation.list')->with(compact('items'));
    }

    public function storeDetails(request $req)
    {
        $check = quotationDetails::where('product', $req->product)->where('quot', $req->id)->count();
        if($check > 0){
            return "existing";
        }

        quotationDetails::create([
            'quot' => $req->id,
            'product' => $req->product,
            'qty' => $req->qty,
            'price' => $req->price,
            'ref' => $req->ref,
        ]);

        return "done";
    }

    public function deleteDetails($id, $quot){
        quotationDetails::where('product', $id)->where('quot', $quot)->delete();
        return "done";
    }

    public function updateDiscount($ref, $discount){
        quotation::where('ref', $ref)->update([
            'discount' => $discount,
        ]);
    }

    public function print($ref){
        $quot = quotation::with('details', 'customer_account')->where('ref', $ref)->first();

        return view('quotation.print')->with(compact('quot'));
    }
}
