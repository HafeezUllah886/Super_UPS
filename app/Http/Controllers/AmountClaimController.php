<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\amount_claim;
use App\Models\products;
use App\Models\transactions;
use Illuminate\Http\Request;

class AmountClaimController extends Controller
{
    public function index()
    {
        $claims = amount_claim::with('product')->orderBy('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        return view('amountClaim.index', compact('claims', 'accounts'));
    }

    public function create(request $req)
    {
        $products = products::all();

        $vendors = account::where('type', 'Vendor')->get();
        $customers = account::where('type', 'Customer')->get();
        $accounts = account::where('type', 'Business')->get();


        return view('amountClaim.create', compact('products','vendors', 'customers', 'accounts'));
    }

    public function store(request $req)
    {
        $ref = getRef();
        amount_claim::create(
            [
                'vendorID' => $req->vendor,
                'productID' => $req->product,
                'customer' => $req->customer == 0 ? null : $req->customer,
                'account' => $req->account,
                'walkin' => $req->walkin,
                'date' => $req->date,
                'qty' => $req->qty,
                'amount' => $req->amount,
                'reason' => $req->reason,
                'status' => $req->status,
                'payment_status' => $req->payment_status,
                'ref' => $ref,
            ]
        );

        if($req->status == "Claimed")
        {
            createTransaction($req->vendor, $req->date, 0, $req->amount, "Claim of Product with reason: $req->reason","Claim", $ref);
        }

        if($req->payment_status == "Paid")
        {
            createTransaction($req->account, $req->date, 0, $req->amount, "Claim of Product with reason: $req->reason","Claim", $ref);
            if($req->customer != 0)
            {
                createTransaction($req->customer, $req->date, $req->amount, $req->amount, "Claim of Product with reason: $req->reason","Claim", $ref);
            }

        }
        else
        {
            if($req->customer != 0)
            {
                createTransaction($req->customer, $req->date, 0, $req->amount, "Pending of Claim of Product with reason: $req->reason","Claim", $ref);
            }
        }

        return redirect('/claim/amount')->with("msg", "Claim Saved");
    }


    public function delete($ref)
    {
        amount_claim::where("ref", $ref)->delete();
        transactions::where("ref", $ref)->delete();

        return redirect('/claim/amount')->with('error', "Claim Deleted");
    }

    public function approve(Request $req)
    {
        $claim = amount_claim::find($req->id);
        $claim->status = "Claimed";



        createTransaction($claim->vendorID, $claim->date, 0, $claim->amount, "Claim of Product with reason: $claim->reason","Claim", $claim->ref);

        if($req->payment_status == "Paid")
        {
            $claim->payment_status = "Paid";
            createTransaction($req->account, now(), 0, $claim->amount, "Claim of Product with reason: $claim->reason","Claim", $claim->ref);

            if($claim->customer != null)
            {
                createTransaction($claim->customer, now(), $claim->amount, 0, "Claim of Product with reason: $claim->reason","Claim", $claim->ref);
            }
        }
        $claim->save();
        return back()->with("msg", "Claim Approved");
    }

    public function payment(Request $req)
    {
        $claim = amount_claim::find($req->id);
        $claim->payment_status = "Paid";
        $claim->save();


        createTransaction($req->account, now(), 0, $claim->amount, "Claim of Product with reason: $claim->reason","Claim", $claim->ref);

        if($claim->customer != null)
        {
            createTransaction($claim->customer, now(), $claim->amount, 0, "Claim of Product with reason: $claim->reason","Claim", $claim->ref);
        }

        return back()->with("msg", "Payment Saved");
    }
}
