<?php

namespace App\Http\Controllers;

use App\Models\sale;
use App\Models\sale_details;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        return view('claim.index');
    }

    public function create(request $req)
    {
        $bill = sale::find($req->bill);

        if($bill)
        {
            $total = 0;
            $sale_details = sale_details::where('bill_id', $req->bill)->get();
            foreach($sale_details as $details){
                $total += $details->qty * $details->price;
            }
            return view('claim.create', compact('bill', 'total'));
        }

        return back()->with("error", "Invoice not found");
    }
}
