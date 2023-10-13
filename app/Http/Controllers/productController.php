<?php

namespace App\Http\Controllers;

use App\Models\catergory;
use App\Models\company;
use App\Models\expense;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\saleReturn;
use App\Models\saleReturnDetails;
use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class productController extends Controller
{

    public function category(){
        $cats = catergory::all();
        return view('products.category')->with(compact('cats'));
    }

    public function storeCat(request $req)
    {
        $check = catergory::where('cat', $req->cat)->count();

        if($check > 0)
        {
            return back()->with('error', 'This category is already exists');
        }
        catergory::create(
            [
                'cat' => $req->cat,
            ]
        );
        return back()->with('success', 'Category has been created');
    }

    public function editCat(request $req){
        $check = catergory::where('cat', $req->cat)->where('id', '!=', $req->id)->count();

        if($check > 0)
        {
            return back()->with('error', 'This category is already exists');
        }
        catergory::where('id', $req->id)->update(
            ['cat' => $req->cat]
        );
        return back()->with('success', 'Category has been Updated');
    }

    public function company(){
        $company = company::all();
        return view('products.company')->with(compact('company'));
    }

    public function storeCoy(request $req)
    {
        $check = company::where('name', $req->name)->count();

        if($check > 0)
        {
            return back()->with('error', 'This company already exists');
        }
        company::create(
            [
                'name' => $req->name,
            ]
        );
        return back()->with('success', 'Company has been created');
    }

    public function editCoy(request $req){
        $check = company::where('name', $req->name)->where('id', '!=', $req->id)->count();

        if($check > 0)
        {
            return back()->with('error', 'This category is already exists');
        }
        company::where('id', $req->id)->update(
            ['name' => $req->name]
        );
        return back()->with('success', 'Company has been Updated');
    }


    public function products(){
        $products = products::all();
        $cats = catergory::all();
        $coys = company::all();

        return view('products.products')->with(compact('products', 'cats', 'coys'));
    }

    public function storePro(request $req)
    {
        $check = products::where('name', $req->name)->count();

        if($check > 0)
        {
            return back()->with('error', 'This product already exists');
        }
        products::create(
            [
                'name' => $req->name,
                'price' => $req->price,
                'coy' => $req->coy,
                'cat' => $req->cat,
            ]
        );
        return back()->with('success', 'Product has been created');
    }

    public function editPro(request $req){
        $check = products::where('name', $req->name)->where('id', '!=', $req->id)->count();

        if($check > 0)
        {
            return back()->with('error', 'This Product already exists');
        }
        products::where('id', $req->id)->update(
            [
                'name' => $req->name,
                'price' => $req->price,
                'coy' => $req->coy,
                'cat' => $req->cat,
            ]
        );
        return back()->with('success', 'Product has been Updated');
    }

    public function deletePro($id){
        products::find($id)->delete();
        return back()->with('success', 'Product has been Deleted');
    }

    public function trashedPro(){
        $products = products::onlyTrashed()->get();
        return view('products.trashed')->with(compact('products'));
    }

    public function restorePro($id){
        products::onlyTrashed()->find($id)->restore();
        return back()->with('success', 'Product has been Restored');
    }

    public function profit($from, $to){
                // Assuming $fromDate and $toDate are the provided date range
                $fromDate = $from; // Replace with the actual from date
                $toDate = $to;   // Replace with the actual to date

                $products = Products::all();
               
                foreach ($products as $product) {
                    //////////// Getting avg Purchase Price ///////////////////////
                    $purchases_qty = purchase_details::where('product_id', $product->id)
                        ->whereBetween('date', [$fromDate, $toDate])->sum('qty');
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
                        ->whereBetween('date', [$fromDate, $toDate])->sum('qty');
                        $sales_amount = sale_details::where('product_id', $product->id)
                        ->whereBetween('date', [$fromDate, $toDate])->sum('qty');
                        
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
                    $total_sold = $gross_sold_qty - $return_qty;
                    
                    //////////// Calculating Net Profit per product ///////////////////////

                    $net_product_profit = $total_sold * $ppu;

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

                return view('products.profit')->with(compact('products', 'discounts', 'expense', 'from', 'to'));
    }

}
