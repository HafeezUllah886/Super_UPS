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
use App\Models\saleReturn;
use App\Models\saleReturnDetails;
use App\Models\scrap_stock;
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

function createTransaction($account_id, $date, $cr, $db, $desc, $type, $ref){
    transactions::create(
        [
            'account_id' => $account_id,
            'date' => $date,
            'cr' => $cr,
            'db' => $db,
            'desc' => $desc,
            'type' => $type,
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
    $balance = 0;
    $date = date('Y-m-d');
        $transactions = transactions::whereDate('date', $date)->whereHas('account', function ($query) {
            $query->where('Category', 'Cash');
        })->get();

    foreach($transactions as $transaction)
    {
        $balance += $transaction->cr;
        $balance -= $transaction->db;
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
            $trans->date = $bill->date;
        }
        else{
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->first();
            $trans->db = $total;
            $trans->date = $bill->date;
        }

        $trans->save();
    }
    elseif($bill->isPaid == 'Yes')
    {
        $trans = transactions::where('account_id', $bill->account->id)->where('ref', $bill->ref)->first();
        $trans->db = $total;
        $trans->date = $bill->date;
        $trans->save();
    }
    else
    {
        if($bill->vendor_account->type == 'Vendor')
        {
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->first();
            $trans->cr = $total;
            $trans->date = $bill->date;
        }
        else{
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->first();
            $trans->db = $total;
            $trans->date = $bill->date;
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
            $trans->date = $bill->date;
            $trans->save();
    }
    elseif($bill->isPaid == 'Yes')
    {
        $trans = transactions::where('account_id', $bill->account->id)->where('ref', $bill->ref)->first();
        $trans->cr = $total;
        $trans->date = $bill->date;
        $trans->save();
    }
    else
    {
            $trans = transactions::where('account_id', $bill->customer_account->id)->where('ref', $bill->ref)->first();
            $trans->cr = $total;
            $trans->date = $bill->date;
            $trans->save();
    }

    $ledger = ledger::where('ref', $bill->ref)->first();
    $ledger->amount = $total;
    $ledger->save();


}

function todaySale(){
    $Date = Carbon::now()->format('Y-m-d');
    $sales = sale_details::whereDate('date', $Date)->get();

    $total = 0;
    foreach($sales as $item)
    {
        $total += $item->qty * $item-> price;
        $total -= $item->bill->discount;
    }
    return $total;
}

function totalSale(){

    $sales = sale_details::all();
    $total = 0;
    foreach($sales as $item)
    {
        $total += $item->qty * $item-> price;
        $total -= $item->bill->discount;
    }
    return $total;
}

function todayExpense(){
    $Date = Carbon::now()->format('Y-m-d');
    $exp = expense::whereDate('date', $Date)->sum('amount');

    return round($exp,0);
}

function totalExpense(){

    $exp = expense::sum('amount');

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

function stockValue()
{
    $products = products::with('purchases', 'sales')->get();
    $stock_value = 0;
    foreach($products as $product)
    {
        $purchase_qty = $product->purchases->sum('qty');
        $purchase_amount = 0;
        foreach($product->purchases as $purchase)
        {
            $purchase_subTotal = $purchase->rate * $purchase->qty;
            $purchase_amount += $purchase_subTotal;
        }

        if($purchase_amount == 0 || $purchase_qty == 0)
        {
            $avg_purchase_rate = 0;
        }
        else{
            $avg_purchase_rate = $purchase_amount / $purchase_qty;
        }

        $sold = $product->sales->sum('qty');

        $total_purchased = $purchase_qty - $sold;

        $product_value = $total_purchased * $avg_purchase_rate;
        $stock_value += $product_value;
    }

    return $stock_value;
}

function bankBalance()
{
    $accounts = account::where('Category', 'Bank')->get();
    $balance = 0;
    foreach ($accounts as $account) {
        $balance += transactions::where('account_id', $account->id)->sum('cr');
        $balance -= transactions::where('account_id', $account->id)->sum('db');
    }

    return $balance;
}

function remaining(){
    return (customerDues() - vendorDues()) + stockValue();
}

function summaryProfit($from, $to){
    // Assuming $fromDate and $toDate are the provided date range
    $fromDate = $from; // Replace with the actual from date
    $toDate = $to;   // Replace with the actual to date

    $products = products::all();

    foreach ($products as $product) {
        //////////// Getting avg Purchase Price ///////////////////////
        $purchases_qty = purchase_details::where('product_id', $product->id)
            ->whereBetween('date', [$fromDate, $toDate])->count();
        $purchases_amount = purchase_details::where('product_id', $product->id)
            ->whereBetween('date', [$fromDate, $toDate])->sum('rate');

            if($purchases_amount == 0)
            {
                $last_purchase = purchase_details::where('product_id', $product->id)
                ->orderBy('id', 'desc')
                ->first();
                $purchases_amount = $last_purchase->rate ?? 0;
                $purchases_qty = 1;
            }
            $avg_purchase_price = $purchases_amount / $purchases_qty;
        //////////// Getting avg Sale Price ///////////////////////

            $sales_qty = sale_details::where('product_id', $product->id)
            ->whereBetween('date', [$fromDate, $toDate])->count();
            $sales_amount = sale_details::where('product_id', $product->id)
            ->whereBetween('date', [$fromDate, $toDate])->sum('price');

            $gross_sold_qty = $sales_qty; ///// Storing gross sold before proceeding
            if($sales_amount == 0)
            {
                $last_sale = sale_details::where('product_id', $product->id)
                ->orderBy('id', 'desc')
                ->first();
                $sales_amount = $last_sale->price ?? 0;
                $sales_qty = 1;
            }
            $avg_sale_price = $sales_amount / $sales_qty;
        //////////// Getting Profit per Unit ///////////////////////

        $ppu = $avg_sale_price - $avg_purchase_price;
            if($avg_sale_price == 0)
            {
                $ppu = 0;
            }
         //////////// Getting return Qty ///////////////////////
        $returns = saleReturn::whereBetween('date', [$fromDate, $toDate])->get();
        $qty = 0;
        foreach($returns as $return)
        {
            $product_return = saleReturnDetails::where('return_id', $return->id)->where('product_id', $product->id)->sum('qty');
            $qty += $product_return;
        }
        $return_qty = $qty;
         //////////// Subtracting return Qty from gross qty to get total Sold///////////////////////
         $total_sold = $gross_sold_qty;

         //////////// Calculating Net Profit per product ///////////////////////

         $return_profit = $ppu * $return_qty;
         $product_profit = $total_sold * $ppu;
         $net_product_profit = $product_profit - $return_profit;

        //////////// Getting Available Stock ///////////////////////
        $stock_cr = stock::where('product_id', $product->id)->sum('cr');
        $stock_db = stock::where('product_id', $product->id)->sum('db');
        $available_stock = $stock_cr - $stock_db;

        //////////// Calculating Stock Value ///////////////////////

        $stock_value = $avg_sale_price * $available_stock;

        //////////// Passing all data to product variable ///////////////////////
        $product->app = $avg_purchase_price;
        $product->asp = $avg_sale_price;
        $product->ppu = $ppu;
        $product->sold = $total_sold;
        $product->return = $return_qty;
        $product->profit = $net_product_profit;
        $product->stock = $available_stock;
        $product->stock_value = $stock_value;
    }
    $discounts = sale::whereBetween('date', [$fromDate, $toDate])->sum('discount');
    $expense = expense::whereBetween('date', [$fromDate, $toDate])->sum('amount');

    $scrap_stock = scrap_stock::all();
    $s_purchase = 0;
    $s_sale = 0;
    foreach($scrap_stock as $s_stock)
    {
        if($s_stock->cr > 0)
        {
            $s_purchase += $s_stock->cr * $s_stock->rate;
        }
        if($s_stock->db > 0)
        {
            $s_sale += $s_stock->db * $s_stock->rate;
        }
    }
    $s_profit = $s_sale - $s_purchase;
    $totalProfit = 0;
    foreach ($products as $product)
    {
        $totalProfit += $product->profit;
    }
    $netProfit = round(($totalProfit + $s_profit) - $discounts - $expense,2);

    return $netProfit;
}
