<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\claim;
use App\Models\products;
use App\Models\stock;
use App\Models\transactions;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $products = products::all();
        $vendors = account::where("type", "Vendor")->get();
        $claims = claim::orderBy("id", "desc")->get();
        return view('claim.index', compact('products', 'vendors', 'claims'));
    }

    public function store(request $req)
    {
        $ref = getRef();
        $claim = claim::create(
            [
                'customer' => $req->customer,
                'date' => $req->date,
                'return_product' => $req->returnPro,
                'return_qty' => $req->returnQty,
                'issue_product' => $req->issuePro,
                'issue_qty' => $req->issueQty,
                'vendor' => $req->vendor,
                'amount' => $req->amount,
                'notes' => $req->notes,
                'ref' => $ref
            ]
        );

        stock::create(
            [
                'product_id' => $req->issuePro,
                'date' => $req->date,
                'desc' => "Claim: $req->notes",
                'db' => $req->issueQty,
                'ref' => $ref,
            ]
        );

        createTransaction($req->vendor, $req->date, 0, $req->amount, "Claim $req->notes", "Claim", $ref);

        return back()->with('success', "Claim Created Successfully");

    }

    public function delete($ref)
    {
        stock::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        claim::where('ref', $ref)->delete();

        return redirect('/claim')->with("error", "Claim deleted");
    }
}
