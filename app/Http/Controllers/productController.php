<?php

namespace App\Http\Controllers;

use App\Models\catergory;
use App\Models\company;
use App\Models\products;
use Illuminate\Http\Request;

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
}
