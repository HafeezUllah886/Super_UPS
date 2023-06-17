<?php

use App\Models\account;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\ref;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\transactions;

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
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->where('date', date('Y-m-d'))->sum('cr');
        $db = transactions::where('account_id', $account->id)->where('date', date('Y-m-d'))->sum('db');

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
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->where('date', date('Y-m-d'))->sum('cr');
        $db = transactions::where('account_id', $account->id)->where('date', date('Y-m-d'))->sum('db');

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
    $sales = sale_details::where('date', date('Y-m-d'))->get();
    $total = 0;
    foreach($sales as $item)
    {
        $total += $item->qty * $item-> price;
    }
    return $total;
}
