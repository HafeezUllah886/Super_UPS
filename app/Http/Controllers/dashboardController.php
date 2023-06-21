<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\transactions;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function dashboard(){
        /* Ledger Entries */
        $ledger = ledger::orderBy('id', 'desc')->get();

        /* Income and Expense */
        $all = account::with('transactions')->where('type', 'Business')->get();
        $accounts1 = [];
        foreach($all as $act)
        {
          $accounts1[] = $act->id;
        }
        $trans1 = transactions::whereIn('account_id', $accounts1)->get();

         /* Income and Expense */
         $cash = account::with('transactions')->where('Category', 'Cash')->get();
         $account = [];
         foreach($all as $act)
         {
           $account[] = $act->id;
         }
         $cashs = transactions::whereIn('account_id', $account)->get();
        return view('dashboard')->with(compact('ledger', 'trans1', 'cashs'));
    }

    public function settings(){

    }
}
