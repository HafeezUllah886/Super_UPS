<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_draft;
use App\Models\stock;
use App\Models\transactions;
use Illuminate\Http\Request;

class purchaseController extends Controller
{
    public function purchase(){
        $vendors = account::where('type', '!=','Business')->get();
        $paidFroms = account::where('type', 'Business')->get();
        $products = products::all();
        return view('purchase.purchase')->with(compact('vendors', 'products', 'paidFroms'));
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
        if($req->isPaid == 'No')
        {
            $purchase = purchase::create([
                'vendor' => $req->vendor,
                'paidFrom' => null,
                'date' => $req->date,
                'desc' => $req->desc,
                'amount' => null,
                'isPaid' => $req->isPaid,
                'ref' => $ref,
            ]);
        }
        elseif($req->isPaid == 'Yes')
        {
            $purchase = purchase::create([
                'vendor' => $req->vendor,
                'paidFrom' => $req->paidFrom,
                'date' => $req->date,
                'desc' => $req->desc,
                'amount' => null,
                'isPaid' => $req->isPaid,
                'ref' => $ref,
            ]);
        }
        else{
            $purchase = purchase::create([
                'vendor' => $req->vendor,
                'paidFrom' => $req->paidFrom,
                'date' => $req->date,
                'desc' => $req->desc,
                'amount' => $req->amount,
                'isPaid' => $req->isPaid,
                'ref' => $ref,
            ]);
        }
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

         purchase_draft::truncate();

         return redirect('/purchase/history');
    }

    public function history(){
        $history = purchase::with('vendor_account', 'account')->orderBy('id', 'desc')->get();
        return view('purchase.history')->with(compact('history'));
    }

    public function edit($id)
    {
        $bill = purchase::where('id', $id)->first();
        $vendors = account::where('type', '!=','Business')->get();
        $paidFroms = account::where('type', 'Business')->get();
        $products = products::all();

        return view('purchase.edit')->with(compact('bill', 'products', 'vendors', 'paidFroms'));

    }

    public function editItems($id){
        $items = purchase_details::with('product')->where('bill_id', $id)->get();

        return view('purchase.edit_details')->with(compact('items'));
    }

    public function editAddItems(request $req, $id){
        $check = purchase_details::where('product_id', $req->product)->where('bill_id', $id)->count();
        if($check > 0)
        {
            return "Existing";
        }
        $bill = purchase::where('id', $id)->first();
        purchase_details::create(
            [
                'bill_id' => $bill->id,
                'product_id' => $req->product,
                'qty' => $req->qty,
                'rate' => $req->rate,
                'ref' => $bill->ref,
            ]
        );
        updatePurchaseAmount($bill->id);
        return "Done";
    }

    public function deleteEdit($id)
    {

        $item = purchase_details::find($id);
        $bill = $item->bill->id;
        $item->delete();
        updatePurchaseAmount($bill);
        return "Deleted";
    }

    public function updateEditQty($id, $qty){
        $item = purchase_details::find($id);
        $item->qty = $qty;
        $item->save();

        updatePurchaseAmount($item->bill->id);
        return "Qty Updated";
    }

    public function updateEditRate($id, $rate){
        $item = purchase_details::find($id);
        $item->rate = $rate;
        $item->save();
        updatePurchaseAmount($item->bill->id);
        return "Rate Updated";
    }

    public function deletePurchase($ref)
    {
        purchase_details::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        stock::where('ref', $ref)->delete();
        purchase::where('ref', $ref)->delete();

        return back()->with('error', "Purchase Deleted");
    }
}
