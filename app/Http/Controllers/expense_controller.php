<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\expense;
use App\Models\expense_categories;
use App\Models\ledger;
use App\Models\transactions;
use Illuminate\Http\Request;

class expense_controller extends Controller
{
    public function expense(){
        $expenses = expense::orderBy('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        $exp_cats = expense_categories::all();
        return view('finance.expenses')->with(compact('expenses', 'accounts', 'exp_cats'));
    }

    public function storeExpense(request $req){
        $ref = getRef();
        $desc = "<strong>Expense</strong><br>" . $req->desc;
        expense::create(
            [
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'catID' => $req->cat,
                'ref' => $ref,
            ]
        );
        createTransaction($req->account, $req->date, 0, $req->amount, $desc, "Expense", $ref);

        $p_acct = account::find($req->account);
        $ledger_head = "Expense";
        $ledger_type = $p_acct->title;
        $ledger_details = $req->desc;
        $ledger_amount = $req->amount;

        addLedger($req->date, $ledger_head, $ledger_type, $ledger_details, $ledger_amount, $ref);

        return back()->with('success', 'Expense saved');
    }

    public function deleteExpense($ref)
    {
        expense::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();

        session()->forget('confirmed_password');
        return redirect('/expense')->with('error', "Expense Deleted");
    }

    public function expDashboard($from, $to)
    {
        $cats = expense_categories::all();

        /* $total_exp = expense::whereBetween('date', [$from, $to])->sum('amount'); */

        return view('exp_dash', compact('cats', 'from', 'to'));
    }

}
