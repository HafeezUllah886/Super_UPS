<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\scrap_sale;
use App\Models\scrap_stock;
use App\Models\transactions;
use Illuminate\Http\Request;

class ScrapSaleController extends Controller
{
    public function index()
    {
        $scraps = scrap_sale::orderBy('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        $cr = scrap_stock::sum('cr');
        $db = scrap_stock::sum('db');
        $stock = $cr - $db;
        return view('scrap.sale.index', compact('accounts', 'scraps', 'stock'));
    }

    public function store(request $req)
    {
        $ref = getRef();
        scrap_sale::create(
            [
                'date' => $req->date,
                'customerName' => $req->customerName,
                'weight' => $req->weight,
                'rate' => $req->rate,
                'desc' => $req->desc,
                'ref' => $ref,
            ]);

            createTransaction($req->account, $req->date, $req->amount, 0, "Scrap Sold","Scrap", $ref);
            scrap_stock::create(
                [
                    'date' => $req->date,
                    'db' => $req->weight,
                    'rate' => $req->rate,
                    'desc' => $req->desc,
                    'ref' => $ref,
                ]
            );

            $account = account::find($req->account);

            addLedger($req->date, $req->customerName, $account->title, "Scrap sold Weight $req->weight Kg", $req->amount, $ref);
            return back()->with("msg", "Scrap Sold");
    }

    public function delete($ref)
    {
        ledger::where('ref', $ref)->delete();
        scrap_stock::where('ref', $ref)->delete();
        scrap_sale::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        return redirect('/scrap/sale')->with("error", "Scrap Sale Deleted");
    }
}
