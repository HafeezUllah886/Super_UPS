<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\products;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\sale_draft;
use App\Models\stock;
use App\Models\transactions;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function sale(){
        $customers = account::where('type','Customer')->get();
        $paidIns = account::where('type', 'Business')->get();
        $products = products::all();
        return view('sale.sale')->with(compact('customers', 'products', 'paidIns'));
    }

    public function getPrice($id){
        $product = products::find($id);
        $stock = stock::where('product_id', $id)->get();
        $balance = 0;
        foreach($stock as $item){
            $balance += $item->cr;
            $balance -= $item->db;
        }
        return response()->json(array(
            'balance' => $balance, 'price' => $product->price
        ));
    }

    public function StoreDraft(request $req){
        $check = sale_draft::where('product_id', $req->product)->count();
        if($check > 0)
        {
            return "Existing";
        }

        sale_draft::create(
            [
                'product_id' => $req->product,
                'qty' => $req->qty,
                'price' => $req->price,
            ]
        );

        return "Done";
    }

    public function draftItems(){
        $items = sale_draft::with('product')->get();

        return view('sale.draft')->with(compact('items'));
    }

    public function updateDraftQty($id, $qty){
        $item = sale_draft::find($id);
        $item->qty = $qty;
        $item->save();

        return "Qty Updated";
    }

    public function updateDraftRate($id, $price){
        $item = sale_draft::find($id);
        $item->price = $price;
        $item->save();

        return "Price Updated";
    }

    public function deleteDraft($id)
    {
        sale_draft::find($id)->delete();
        return "Draft deleted";
    }

    public function storeSale(request $req){
        $req->validate([
            'date' => 'required',
            'customer' => 'required',
            'walkIn' => 'required_if:customer,0',
            'amount' => 'required_if:isPaid,Partial',
            'paidIn' => 'required_unless:isPaid,No',
        ],[
            'date.required' => 'Select Date',
            'customer.required' => 'Select customer',
            'amount' => 'Enter Received Amount',
            'paidIn' => 'Select Account',
            'walkn' => 'Enter Vendor Name'
        ]);
        $ref = getRef();
        $customer = null;
        $walkIn = null;
        $amount = null;
        $paidIn = null;
        if($req->isPaid == 'Yes')
        {
            if($req->customer == 0){
                $walkIn = $req->walkIn;
            }
            else
            {
                $customer = $req->customer;

            }
            $paidIn = $req->paidIn;
        }
        elseif($req->isPaid == 'No'){
            $customer = $req->customer;
        }
        else{
            $customer = $req->customer;
            $paidIn = $req->paidIn;
            $amount = $req->amount;
        }

        $sale = sale::create([
            'customer' => $customer,
            'walking' => $walkIn,
            'paidIn' => $paidIn,
            'date' => $req->date,
            'desc' => $req->desc,
            'amount' => $amount,
            'discount' => $req->discount,
            'isPaid' => $req->isPaid,
            'ref' => $ref,
        ]);

        $desc = "<strong>Sale</strong><br/> Invoice No. ".$sale->id;
        $items = sale_draft::all();
        $total = 0;
        $amount1 = 0;
        foreach ($items as $item){
            $amount1 = $item->price * $item->qty;
            $total += $amount1;
            sale_details::create([
                'bill_id' => $sale->id,
                'product_id' => $item->product_id,
                'price' => $item->price,
                'qty' => $item->qty,
                'date' => $req->date,
                'ref' => $ref,
            ]);

            stock::create([
                'product_id' => $item->product_id,
                'date' => $req->date,
                'desc' => $desc,
                'db' => $item->qty,
                'ref' => $ref
            ]);
         }
         $net_total = $total - $req->discount;
         $desc1 = "<strong>Products Sold</strong><br/>Invoice No. ".$sale->id;
         $desc2 = "<strong>Products Sold</strong><br/>Partial payment of Invoice No. ".$sale->id;
        if($req->customer != 0){

         if($req->isPaid == 'Yes'){
            createTransaction($req->paidIn, $req->date, $net_total, 0, $desc1, "Sale", $ref);
            createTransaction($req->customer, $req->date, $net_total, $net_total, $desc1, "Sale", $ref);
         }
         elseif($req->isPaid == 'No'){
                createTransaction($req->customer, $req->date, $net_total, 0, $desc1, "Sale", $ref);
         }
         else{
            createTransaction($req->customer, $req->date, $net_total, $req->amount, $desc2, "Sale", $ref);
            createTransaction($req->paidIn, $req->date, $req->amount, 0, $desc1, "Sale", $ref);
         }
        }
        else
        {
            createTransaction($req->paidIn, $req->date, $net_total, 0, $desc1, "Sale", $ref);
        }
        $ledger_head = null;
        $ledger_type = null;
        $ledger_details = "Products Sold";
        $ledger_amount = null;
        $c_acct = account::find($req->customer);
        $p_acct = account::find($req->paidIn);
        if($req->isPaid == "Yes"){
           if($req->customer == 0){
            $ledger_head = $req->walkIn . "(Walk-In)";
           }
           else
           {
            $ledger_head = $c_acct->title;
           }
           $ledger_type = $p_acct->title . "/Paid";
           $ledger_amount = $net_total;
        }
        elseif($req->isPaid == "No")
        {
            $ledger_head = $c_acct->title;
            $ledger_type = "Unpaid";
            $ledger_amount = $net_total;
        }
        else{
            $ledger_head = $c_acct->title;
            $ledger_type = $p_acct->title . "/Partial";
            $ledger_amount = $req->amount;
        }
        addLedger($req->date, $ledger_head, $ledger_type, $ledger_details, $ledger_amount, $ref);

         sale_draft::truncate();

         return redirect('/sale/print/'.$ref);
    }
    public function history(){
        $history = sale::with('customer_account', 'account')->orderBy('id', 'desc')->get();
        return view('sale.history')->with(compact('history'));
    }

    public function deleteSale($ref)
    {
        sale_details::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        stock::where('ref', $ref)->delete();
        sale::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect('/sale/history')->with('error', "Sale Deleted");
    }

    public function edit($id)
    {
        $bill = sale::where('id', $id)->first();
        $customer = account::where('type','Customer')->get();
        $paidIn = account::where('type', 'Business')->get();
        $products = products::all();

        return view('sale.edit')->with(compact('bill', 'products', 'customer', 'paidIn'));

    }

    public function editItems($id){
        $items = sale_details::with('product')->where('bill_id', $id)->get();

        return view('sale.edit_details')->with(compact('items'));
    }

    public function editAddItems(request $req, $id){
        $check = sale_details::where('product_id', $req->product)->where('bill_id', $id)->count();
        if($check > 0)
        {
            return "Existing";
        }
        $bill = sale::where('id', $id)->first();
        $date = $bill->date;
        sale_details::create(
            [
                'bill_id' => $bill->id,
                'product_id' => $req->product,
                'qty' => $req->qty,
                'price' => $req->price,
                'ref' => $bill->ref,
                'date' => $date,
            ]
        );

        stock::create(
            [
                'product_id' => $req->product,
                'date' => $date,
                'desc' => "<strong>Sale</strong><br/> Invoice No. ".$bill->id,
                'db' => $req->qty,
                'ref' => $bill->ref,
            ]
        );

        updateSaleAmount($bill->id);
        return "Done";
    }


    public function updateEditQty($id, $qty){

        $item = sale_details::find($id);
        $item->qty = $qty;
        $item->save();

        $stock = stock::where('product_id', $item->product_id)->where('ref', $item->ref)->first();
        $stock->db = $qty;
        $stock->save();

        updateSaleAmount($item->bill->id);
        return "Quantity Updated";
    }

    public function updateEditDiscount($id, $discount){
        $item = sale::find($id);
        $item->discount = $discount;
        $item->save();
        updateSaleAmount($id);
        return "Discount Updated";
    }

    public function updateEditPrice($id, $price){
        $item = sale_details::find($id);
        $item->price = $price;
        $item->save();
        updateSaleAmount($item->bill->id);
        return "Price Updated";
    }

    public function deleteEdit($id)
    {
        $item = sale_details::find($id);
        $bill = $item->bill->id;
        $item->delete();
        updateSaleAmount($bill);
        return "Deleted";
    }

    public function print($ref){
        $invoice = sale::with('details', 'customer_account')->where('ref', $ref)->first();
        $details = sale_details::where('bill_id', $invoice->id)->get();
        $previous_bal_cr = transactions::where('account_id', $invoice->customer)->where('date', '<', $invoice->date)->sum('cr');
        $previous_bal_db = transactions::where('account_id', $invoice->customer)->where('date', '<', $invoice->date)->sum('db');
        $prev_balance =$previous_bal_cr - $previous_bal_db;

        $currant_bal_cr = transactions::where('account_id', $invoice->customer)->where('date', '<=', $invoice->date)->sum('cr');
        $currant_bal_db = transactions::where('account_id', $invoice->customer)->where('date', '<=', $invoice->date)->sum('db');

        $cur_balance = $currant_bal_cr - $currant_bal_db;
        return view('sale.print')->with(compact('invoice', 'details', 'prev_balance', 'cur_balance'));
    }

}
