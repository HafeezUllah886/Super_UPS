<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_draft;
use App\Models\stock;
use Illuminate\Http\Request;

class purchaseController extends Controller
{
    public function purchase(){
        $vendors = account::where('type', '!=','Business')->get();
        $paidIns = account::where('type', 'Business')->get();
        $products = products::all();
        return view('purchase.purchase')->with(compact('vendors', 'products', 'paidIns'));
    }

    public function StoreDraft(request $req){
        $check = purchase_draft::where('product_id', $req->product)->count();
        if($check > 0)
        {
            return "Existing";
        }

        purchase_draft::create(
            [
                'product_id' => $req->product,
                'qty' => $req->qty,
                'rate' => $req->rate,
            ]
        );

        return "Done";
    }

    public function draftItems(){
        $items = purchase_draft::with('product')->get();

        return view('purchase.draft')->with(compact('items'));
    }

    public function updateDraftQty($id, $qty){
        $item = purchase_draft::find($id);
        $item->qty = $qty;
        $item->save();

        return "Qty Updated";
    }

    public function updateDraftRate($id, $rate){
        $item = purchase_draft::find($id);
        $item->rate = $rate;
        $item->save();

        return "Rate Updated";
    }

    public function deleteDraft($id)
    {
        purchase_draft::find($id)->delete();
        return "Draft deleted";
    }

    public function storePurchase(request $req){
        $req->validate([
            'date' => 'required',
            'vendor' => 'required',
            'amount' => 'required_if:isPaid,Partial',
            'paidFrom' => 'required_unless:isPaid,No',
        ],[
            'date.required' => 'Select Date',
            'vendor.required' => 'Select Vendor',
            'amount' => 'Enter Paid Amount',
            'paidFrom' => 'Select Account'
        ]);
        $ref = getRef();
        $purchase = purchase::create([
            'vendor' => $req->vendor,
            'paidFrom' => $req->paidFrom,
            'date' => $req->date,
            'desc' => $req->desc,
            'isPaid' => $req->isPaid,
            'ref' => $ref,
        ]);
        $desc = "<strong>Purchased</strong><br/> Bill No. ".$purchase->id;
        $items = purchase_draft::all();
        $total = 0;
        $amount = 0;
        foreach ($items as $item){
            $amount = $item->rate * $item->qty;
            $total += $amount;
            purchase_details::create([
                'bill_id' => $purchase->id,
                'product_id' => $item->product_id,
                'rate' => $item->rate,
                'qty' => $item->qty,
                'ref' => $ref,
            ]);

            stock::create([
                'product_id' => $item->product_id,
                'date' => $req->date,
                'desc' => $desc,
                'cr' => $item->qty,
                'ref' => $ref
            ]);
         }

         $desc1 = "<strong>Products Purchased</strong><br/>Bill No. ".$purchase->id;
         $desc2 = "<strong>Products Purchased</strong><br/>Partial payment of Bill No. ".$purchase->id;
         if($req->isPaid == 'Yes'){
            createTransaction($req->paidFrom, $req->date, 0, $total, $desc1, $ref);
         }
         elseif($req->isPaid == 'No'){
            createTransaction($req->vendor, $req->date, $total, 0, $desc1, $ref);
         }
         else{
            createTransaction($req->vendor, $req->date, $total, $req->amount, $desc2, $ref);
            createTransaction($req->paidFrom, $req->date, 0, $req->amount, $desc1, $ref);
         }
    }

    public function history(){
        $history = purchase::with('vendor', 'paidFrom', 'details')->orderBy('id', 'desc')->get();
        return view('purchase.history')->with(compact('history'));
    }
}
