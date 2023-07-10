<?php

use App\Models\account;
use App\Models\expense;
use App\Models\ledger;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\ref;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\stock;
use App\Models\transactions;
use Carbon\Carbon;

function getRef(){
    $ref = ref::first();
    if($ref){
        $ref->ref = $ref->ref + 1;
    }
    else{
        $ref = new ref();
        $ref->ref = 1;
    }
    $ref->save();
    return $ref->ref;
}

function createTransaction($account_id, $date, $cr, $db, $desc, $ref){
    transactions::create(
        [
            'account_id' => $account_id,
            'date' => $date,
            'cr' => $cr,
            'db' => $db,
            'desc' => $desc,
            'ref' => $ref,
        ]
    );
}

function getAccountBalance($account_id){
    $transactions  = transactions::where('account_id', $account_id)->get();
    $balance = 0;
    foreach($transactions as $trans)
    {
        $balance += $trans->cr;
        $balance -= $trans->db;
    }

    return $balance;
}

function customerDues(){
   $accounts = account::where('type', 'customer')->get();
   $cr = 0;
   $db = 0;
   $balance = 0;
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->sum('cr');
        $db = transactions::where('account_id', $account->id)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;
}

function vendorDues(){
    $accounts = account::where('type', 'vendor')->get();
    $cr = 0;
    $db = 0;
    $balance = 0;
    foreach ($accounts as $account){
         $cr = transactions::where('account_id', $account->id)->sum('cr');
         $db = transactions::where('account_id', $account->id)->sum('db');

         $balance += $cr - $db;
    }

    return $balance;
 }

function totalCash(){
    $accounts = account::where('Category', 'Cash')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->sum('cr');
        $db = transactions::where('account_id', $account->id)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;

}

function todayCash(){
    $accounts = account::where('Category', 'Cash')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   $Date = Carbon::now()->format('Y-m-d');
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->whereDate('date', $Date)->sum('cr');
        $db = transactions::where('account_id', $account->id)->whereDate('date', $Date)->sum('db');
        $balance += $cr - $db;
   }

   return $balance;

}


function totalBank(){
    $accounts = account::where('Category', 'Bank')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->sum('cr');
        $db = transactions::where('account_id', $account->id)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;

}

function todayBank(){
    $accounts = account::where('Category', 'Bank')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   $Date = Carbon::now()->format('Y-m-d');
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->whereDate('date', $Date)->sum('cr');
        $db = transactions::where('account_id', $account->id)->whereDate('date', $Date)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;

}

function getPurchaseBillTotal($id){
    $items = purchase_details::where('bill_id', $id)->get();
    $total = 0;
    $amount = 0;
    foreach($items as $item)
    {
        $amount = $item->rate * $item->qty;
        $total += $amount;
    }

    return $total;
}

function getSaleBillTotal($id){
    $items = sale_details::where('bill_id', $id)->get();
    $total = 0;
    $amount = 0;
    foreach($items as $item)
    {
        $amount = $item->price * $item->qty;
        $total += $amount;
    }
    $bill = sale::find($id);
    $total = $total - $bill->discount;
    return $total;
}

function updatePurchaseAmount($id){
    $bill = purchase::where('id', $id)->first();
    $total = getPurchaseBillTotal($id);
    if($bill->isPaid == 'No')
    {
        if($bill->vendor_account->type == 'Vendor')
        {
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->first();
            $trans->cr = $total;
        }
        else{
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->first();
            $trans->db = $total;
        }

        $trans->save();
    }
    elseif($bill->isPaid == 'Yes')
    {
        $trans = transactions::where('account_id', $bill->account->id)->where('ref', $bill->ref)->first();
        $trans->db = $total;
        $trans->save();
    }
    else
    {
        if($bill->vendor_account->type == 'Vendor')
        {
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->first();
            $trans->cr = $total;
        }
        else{
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->first();
            $trans->db = $total;
        }

        $trans->save();

    }
}

function updateSaleAmount($id){
    $bill = sale::where('id', $id)->first();
    $total = getSaleBillTotal($id);
    if($bill->isPaid == 'No')
    {

            $trans = transactions::where('account_id', $bill->customer_account->id)->where('ref', $bill->ref)->first();
            $trans->cr = $total;
            $trans->save();
    }
    elseif($bill->isPaid == 'Yes')
    {
        $trans = transactions::where('account_id', $bill->account->id)->where('ref', $bill->ref)->first();
        $trans->cr = $total;
        $trans->save();
    }
    else
    {
            $trans = transactions::where('account_id', $bill->customer_account->id)->where('ref', $bill->ref)->first();
            $trans->cr = $total;
            $trans->save();
    }


}

function todaySale(){
    $Date = Carbon::now()->format('Y-m-d');
    $sales = sale_details::whereDate('date', $Date)->get();

    $total = 0;
    foreach($sales as $item)
    {
        $total += $item->qty * $item-> price;
    }
    return $total;
}

function todayExpense(){
    $Date = Carbon::now()->format('Y-m-d');
    $exp = expense::whereDate('date', $Date)->sum('amount');

    return round($exp,0);
}



function addLedger($date, $head, $type, $details, $amount, $ref){
    ledger::create(
        [
            'date' => $date,
            'head' => $head,
            'type' => $type,
            'details' => $details,
            'amount' => $amount,
            'ref' => $ref
        ]
    );

    return "Ledger Added";
}

function deleteLedger($ref)
{
    ledger::where('ref', $ref)->delete();
    return "Ledger Deleted";
}


function profit(){
    $products = Products::all();

        foreach ($products as $product) {
            $purchases = Purchase_details::where('product_id', $product->id)->get();
            $sales = Sale_details::where('product_id', $product->id)->get();

            $daily_prices = [];
            foreach ($purchases as $purchase) {
                $daily_prices[$purchase->date] = $purchase->rate;
            }
            foreach ($sales as $sale) {
                $daily_prices[$sale->date] = $sale->price;
            }

            $total_purchase_amount = 0;
            $total_purchase_quantity = 0;
            foreach ($purchases as $purchase) {
                $total_purchase_amount += $purchase->qty * $daily_prices[$purchase->date];
                $total_purchase_quantity += $purchase->qty;
            }

            $total_sale_amount = 0;
            $total_sale_quantity = 0;
            foreach ($sales as $sale) {
                $total_sale_amount += $sale->qty * $daily_prices[$sale->date];
                $total_sale_quantity += $sale->qty;
            }

            $profit = $total_sale_amount - $total_purchase_amount;
            $average_purchase_price = $total_purchase_amount / $total_purchase_quantity;
            $average_sale_price = $total_sale_amount / $total_sale_quantity;

            $stock_cr = stock::where('product_id', $product->id)->sum('cr');
            $stock_db = stock::where('product_id', $product->id)->sum('db');
            $available_stock = $stock_cr - $stock_db;

            $product->profit = $profit;
            $product->purchase_quantity = $total_purchase_quantity;
            $product->sale_quantity = $total_sale_quantity;
            $product->average_purchase_price = $average_purchase_price;
            $product->average_sale_price = $average_sale_price;
            $product->available_stock = $available_stock;
        }

        return $products;
    }
