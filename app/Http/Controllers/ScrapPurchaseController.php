<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\scrap_purchase;
use App\Models\scrap_stock;
use Illuminate\Http\Request;

class ScrapPurchaseController extends Controller
{
    public function index()
    {
        $scraps = scrap_purchase::orderBy('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        return view('scrap.purchase.index', compact('accounts', 'scraps'));
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

            $account = account::find($req->account);

            addLedger($req->date, $req->customerName, $account->title, "Scrap purchased Weight $req->weight Kg", $req->amount, $ref);
            return back()->with("msg", "Scrap Purchased");
    }

    public function delete($ref)
    {
        ledger::where('ref', $ref)->delete();
        scrap_stock::where('ref', $ref)->delete();
        scrap_purchase::where('ref', $ref)->delete();

        return redirect('/scrap/purchase')->with("error", "Scrap Purchase Deleted");
    }
}
