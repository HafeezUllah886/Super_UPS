<?php

namespace App\Http\Controllers;

use App\Models\account;
use Illuminate\Http\Request;

class ScrapPurchaseController extends Controller
{
    public function index()
    {
        $accounts = account::where('type', 'Business')->get();
        return view('scrap.purchase.index');
    }
}
