<?php

use App\Models\account;
use App\Models\ref;
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
