<?php

namespace App\Http\Controllers;

use App\Models\claim;
use App\Models\expense;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\saleReturn;
use App\Models\saleReturnDetails;
use App\Models\scrap_purchase;
use App\Models\scrap_sale;
use App\Models\scrap_stock;
use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    public function profit($from, $to){
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

                $sales = sale_details::where('product_id', $product->id)
                ->whereBetween('date', [$fromDate, $toDate])->get();
                

                $sales_qty = $sales->sum('qty');
                $sales_price= $sales->sum('price');
                $sales_rows = $sales->count();
                $gross_sold_qty = $sales_qty; ///// Storing gross sold before proceeding
                if($sales_rows == 0)
                {
                    $last_sale = sale_details::where('product_id', $product->id)
                    ->orderBy('id', 'desc')
                    ->first();
                    $sales_price = $last_sale->price ?? 0;
                    $sales_qty = 1;
                    $sales_rows = 1;
                }
                $avg_sale_price = $sales_price / $sales_rows;
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

        $scrap_purchases = scrap_purchase::whereBetween('date', [$fromDate, $toDate])->get();
        $scrap_sales = scrap_sale::whereBetween('date', [$fromDate, $toDate])->get();

        $s_purchase_amount = 0;
        foreach($scrap_purchases as $s_purchase)
        {
            $s_purchase_amount += $s_purchase->weight * $s_purchase->rate;
        }
        $s_sale_amount = 0;
        foreach($scrap_sales as $s_sale)
        {
            $s_sale_amount += $s_sale->weight * $s_sale->rate;
        }

        if($scrap_purchases->sum('weight') > 0)
        {
            $s_purchase_price = ($s_purchase_amount / $scrap_purchases->sum('weight')) ?? 0;
        }
        else
        {
            $last_s_purchase = scrap_purchase::latest()->first();
            $s_purchase_price = $last_s_purchase->rate ?? 0;
        }

        if($scrap_sales->sum('weight') > 0)
        {
            $s_sale_price = ($s_sale_amount / $scrap_sales->sum('weight')) ?? 0;
        }
        else
        {
            $last_s_sale = scrap_sale::latest()->first();
            $s_sale_price = $last_s_sale->rate ?? 0;
        }
        
        

        $s_ppu = $s_sale_price - $s_purchase_price;

        $s_sold = $scrap_sales->sum('weight');

        $s_profit = $s_ppu * $s_sold;

        return view('reports.profit')->with(compact('products', 'discounts', 'expense', 'from', 'to', 's_profit'));
    }

public function stockAlert()
{
    $products = products::with('category', 'company')->get();
    foreach($products as $product)
    {
        $cr = stock::where("product_id", $product->id)->sum('cr');
        $db = stock::where("product_id", $product->id)->sum('db');

        $product->availStock = $cr - $db;
    }
    return view('reports.stock_alert', compact('products'));
}

public function summary($from, $to){
    $sales = sale_details::whereBetween('date', [$from, $to])
    ->sum(DB::raw('price * qty'));

    $purchases = purchase_details::whereBetween('date', [$from, $to])
    ->sum(DB::raw('rate * qty'));

    $sale_returns = saleReturnDetails::whereBetween('date', [$from, $to])
    ->sum(DB::raw('price * qty'));

    $claims = claim::whereBetween('date', [$from, $to])->sum('qty');

    $scrap_purchase = scrap_purchase::whereBetween('date', [$from, $to])
    ->sum(DB::raw('weight * rate'));

    $scrap_sold = scrap_sale::whereBetween('date', [$from, $to])
    ->sum(DB::raw('weight * rate'));

    $expenses = expense::whereBetween('date', [$from, $to])
    ->sum('amount');

    $profit = summaryProfit($from, $to);

    return view('reports.summary', compact('sales', 'purchases', 'sale_returns','claims', 'scrap_purchase', 'scrap_sold', 'expenses', 'from', 'to', 'profit'));
}

}
