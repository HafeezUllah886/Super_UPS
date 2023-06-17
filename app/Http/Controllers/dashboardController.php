<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\transactions;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function dashboard(){

        $all_cash = account::with('transactions')->where('Category', 'Cash')->get();
        $accounts = [];
        foreach($all_cash as $cash)
        {
          $accounts[] = $cash->id;
        }
        $trans = transactions::whereIn('account_id', $accounts)->get();

        $all = account::with('transactions')->where('type', 'Business')->get();
        $accounts1 = [];
        foreach($all as $act)
        {
          $accounts1[] = $act->id;
        }
        $trans1 = transactions::whereIn('account_id', $accounts1)->get();
        return view('dashboard')->with(compact('trans', 'trans1'));
    }
}
