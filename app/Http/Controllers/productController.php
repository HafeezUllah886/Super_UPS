<?php

namespace App\Http\Controllers;

use App\Models\account;
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
       /*  $account = account::create(
            [
                'title' => $req->name,
                'type' => "Product",
            ]
        ); */
        products::create(
            [
                'name' => $req->name,
                'price' => $req->price,
                'sym' => $req->sym,
                'coy' => $req->coy,
                'cat' => $req->cat,
               /*  'accountID' => $account->id, */
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
                'sym' => $req->sym,
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

                $fromDate = $from;
                $toDate = $to;

                $sales = sale_details::with('product')->whereBetween('date', [$fromDate, $toDate])
                ->selectRaw('product_id, sum(qty) as qty, sum(subTotal) as subTotal')
                ->groupBy('product_id')
                ->get();
                foreach ($sales as $sale) {
                    $sale->salePrice = $sale->subTotal / $sale->qty;

                    $purchase = purchase_details::where("product_id", $sale->product_id)
                    ->whereBetween('date', [$fromDate, $toDate])
                    ->selectRaw('product_id, sum(qty) as qty, sum(subTotal) as subTotal')
                    ->groupBy('product_id')
                    ->first();

                    if(!$purchase)
                    {
                        $purchase = purchase_details::where("product_id", $sale->product_id)
                        ->whereDate('date', '<=', $fromDate)
                        ->first();
                    }

                    $sale->purchasePrice = $purchase->subTotal / $purchase->qty;
                    $sale->profit =  $sale->salePrice - $sale->purchasePrice;


                    //////////// Getting Available Stock ///////////////////////
                    $stock_cr = stock::where('product_id', $sale->product_id)->sum('cr');
                    $stock_db = stock::where('product_id', $sale->product_id)->sum('db');
                    $available_stock = $stock_cr - $stock_db;

                    //////////// Calculating Stock Value ///////////////////////

                    $stock_value = $sale->salePrice * $available_stock;

                    //////////// Passing all data to product variable ///////////////////////

                    $sale->stock = $available_stock;
                    $sale->stock_value = $stock_value;
                }
                $expense = expense::whereBetween('date', [$fromDate, $toDate])->sum('amount');

                return view('products.profit')->with(compact('sales', 'expense', 'from', 'to'));
    }

}
