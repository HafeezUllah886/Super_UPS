<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\saleReturn;
use App\Models\saleReturnDetails;
use App\Models\stock;
use App\Models\transactions;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class SaleReturnController extends Controller
{
    public function index(){
        $saleReturns = saleReturn::orderBy('id', 'desc')->get();

        return view('sale.return')->with(compact('saleReturns'));
    }

    public function search(request $req){

        $bill = sale::find($req->bill);
        if($bill){
            $saleReturns = saleReturn::where('bill_id', $req->bill)->first();
            if($saleReturns){
                return back()->with('error', 'Already Returned');
            }



            return redirect('/return/view/'.$bill->id);
        }
        return back()->with('error', 'Bill Not Found');
    }
    public function view($id){
        $paidFroms = account::where('type', 'Business')->get();

        $bill = sale::find($id);
        $total = 0;
        $sale_details = sale_details::where('bill_id', $id)->get();
        foreach($sale_details as $details){
            $total += $details->qty * $details->price;
        }
        return view('sale.createReturn')->with(compact('bill','paidFroms', 'total'));
    }

    public function saveReturn(request $req, $bill){
        $req->validate([
            'amount' => "required",
            'paidFrom' => 'required_unless:amount,0',
            'date' => 'required',
        ]);
        $account = null;
        if($req->amount != 0){
            $account = $req->paidFrom;
        }
        $ref = getRef();
        $return = saleReturn::create(
            [
                'bill_id' => $bill,
                'date' => $req->date,
                'paidBy' => $account,
                'deduction' => $req->deduction,
                'amount' => $req->payable,
                'ref' => $ref,
            ]
        );

        $return_id = $return->id;
        $ids = $req->input('id');
        $prices = $req->input('price');
        $qtys= $req->input('returnQty');
        foreach($ids as $key => $id){
            $price = $prices[$key];
            $qty = $qtys[$key];
            if($qty > 0){
                saleReturnDetails::create([
                    'return_id' => $return_id,
                    'product_id' => $id,
                    'qty' => $qty,
                    'price' => $price,
                    'ref' => $ref,
                ]);

                stock::create(
                    [
                        'product_id' => $id,
                        'date' => $req->date,
                        'desc' => "Sale Return",
                        'cr' => $qty,
                        'ref' => $ref
                    ]
                );
            }
        }



       $customer = sale::where('id', $return->bill_id)->first();

       $head = null;
    if($req->amount != 0){

        if(($req->payable - $req->amount) == 0)
        {
         createTransaction($req->paidFrom, today(), 0,$req->amount, "Sale Return", $ref);
        }
        else{
            createTransaction($req->paidFrom, today(), $req->amount,0, "Sale Return", $ref);
            createTransaction($customer->customer, today(),$req->amount, $req->payable, "Sale Return", $ref);
        }
    }
    else{
        createTransaction($customer->customer, today(), $req->amount, 0, "Sale Return", $ref);
    }

       if($customer->customer){
       $head = $customer->customer_account->title;
       }
       else{
        $head = $customer->walking . "(Walk-in)";
       }

       $type = account::find($req->paidFrom);

       addLedger(today(), $head, $type->title, "Sale Return", $req->amount, $ref);

        return redirect('/return')->with('success', 'product Returned');
    }

    public function delete($ref){

        saleReturnDetails::where('ref', $ref)->delete();
        saleReturn::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        stock::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();

        return back()->with('error', "Return Deleted");
    }
}
