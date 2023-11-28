<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\scrap_sale;
use App\Models\scrap_stock;
use Illuminate\Http\Request;

class ScrapSaleController extends Controller
{
    public function index()
    {
        $scraps = scrap_sale::orderBy('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        return view('scrap.sale.index', compact('accounts', 'scraps'));
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

            return back()->with("msg", "Scrap Sold");
    }

    public function delete($ref)
    {
        scrap_stock::where('ref', $ref)->delete();
        scrap_sale::where('ref', $ref)->delete();

        return redirect('/scrap/sale')->with("error", "Scrap Sale Deleted");
    }
}
