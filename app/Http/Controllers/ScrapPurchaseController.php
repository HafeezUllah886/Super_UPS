<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\scrap_purchase;
use App\Models\scrap_stock;
use Illuminate\Http\Request;

class ScrapPurchaseController extends Controller
{
    public function index()
    {
        $accounts = account::where('type', 'Business')->get();
        return view('scrap.purchase.index', compact('accounts'));
    }

    public function store(request $req)
    {
        $ref = getRef();
        scrap_purchase::create(
            [
                'date' => $req->date,
                'customerName' => $req->customerName,
                'weight' => $req->weight,
                'rate' => $req->rate,
                'desc' => $req->desc,
                'ref' => $ref,
            ]);

            createTransaction($req->account, $req->date, 0, $req->amount, "Scrap Purchased","Scrap", $ref);
            scrap_stock::create(
                [
                    'date' => $req->date,
                    'cr' => $req->weight,
                    'rate' => $req->rate,
                    'desc' => $req->desc,
                    'ref' => $ref,
                ]
            );

            return back()->with("msg", "Scrap Purchased");
    }
}
