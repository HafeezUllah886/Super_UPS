<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\claim;
use App\Models\products;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\stock;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = claim::with('bill', 'product')->orderBy('id', 'desc')->get();
        return view('claim.index', compact('claims'));
    }

    public function create(request $req)
    {
        $products = products::all();
      
        $vendors = account::where('type', 'Vendor')->get(); 
       
        
        return view('claim.create', compact('products','vendors'));
    }

    public function store(request $req)
    {
        $ref = getRef();
        claim::create(
            [
                'salesID' => $req->saleID,
                'productID' => $req->productID,
                'date' => $req->date,
                'qty' => $req->qty,
                'reason' => $req->reason,
                'status' => $req->status,
                'ref' => $ref,
            ]
        );

        if($req->status == "Claimed")
        {
            stock::create(
                [
                    'product_id' => $req->productID,
                    'date' => $req->date,
                    'desc' => "Claimed in bill no. $req->saleID with reason: $req->reason",
                    'db' => $req->qty,
                    'ref' => $ref,
                ]
            );
        }

        return redirect('/claim')->with("msg", "Claim Saved");
    }

    public function delete($ref)
    {
        stock::where("ref", $ref)->delete();
        claim::where("ref", $ref)->delete();

        return redirect('/claim')->with('error', "Claim Deleted");
    }

    public function approve($ref)
    {
        $claim = claim::where("ref", $ref)->first();
        $claim->status = "Claimed";
        $claim->save();

        stock::create(
            [
                'product_id' => $claim->productID,
                'date' => now(),
                'desc' => "Claimed in bill no. $claim->salesID with reason: $claim->reason",
                'db' => $claim->qty,
                'ref' => $ref,
            ]
        );

        return back()->with("msg", "Claim Approved");
    }
}
