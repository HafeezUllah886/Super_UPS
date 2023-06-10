<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\deposit;
use App\Models\transactions;
use App\Models\withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function accounts(){
        $accounts = account::where('type', 'Business')->get();

        return view('finance.accounts')->with(compact('accounts'));
    }

    public function storeAccount(request $req){
        $check = Account::where('title', $req->title)->count();
        if($check > 0){
            return back()->with('error', 'Account already exists');
        }
        $account = account::create(
            [
                'title' => $req->title,
                'type' => "Business",
            ]
        );
        if($req->amount != 0) {
            createTransaction($account->id, date('Y-m-d'), "$req->amount", "0", "Initial Amount", getRef());
        }
        return back()->with('success', 'Account created successfully');
    }

    public function deleteAccount($id){
        if(getAccountBalance($id) != 0){
            return back()->with('error', 'Account cannot be deleted');
        }

        transactions::where('account_id', $id)->delete();
        account::where('id', $id)->delete();

        return back()->with('success', 'Account deleted successfully');
    }

    public function statementView($id){
        $account = account::find($id);
        return view('finance.statement')->with(compact('account'));
    }

    public function details($id, $from, $to)
    {
        $from = Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $to = Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');
        $items = transactions::where('account_id', $id)->where('date', '>=', $from)->where('date', '<=', $to)->get();
        $prev = transactions::where('account_id', $id)->where('date', '<', $from)->get();

        $p_balance = 0;
        foreach ($prev as $item) {
            $p_balance += $item->cr;
            $p_balance -= $item->db;
        }

        $all = transactions::where('account_id', $id)->get();


        return view('finance.statement_details')->with(compact('items', 'p_balance', 'id'));
    }

    public function deposit(){
        $deposits = deposit::orderBy('id', 'desc')->get();
        $accounts = account::all();
        return view('finance.deposits')->with(compact('deposits', 'accounts'));
    }

    public function storeDeposit(request $req){
        $ref = getRef();
        $desc = "<strong>Deposit</strong><br>" . $req->desc;
        deposit::create(
            [
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'ref' => $ref,
            ]
        );
        createTransaction($req->account, $req->date, $req->amount, 0, $desc, $ref);

        return back()->with('success', 'Amount deposit was successfull');
    }

    public function deleteDeposit($ref)
    {
        deposit::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();

        return back()->with('success', 'Deposit was deleted');
    }


    public function withdraw(){
        $withdraws = withdraw::orderBy('id', 'desc')->get();
        $accounts = account::all();
        return view('finance.withdraws')->with(compact('withdraws', 'accounts'));
    }

    public function storeWithdraw(request $req){
        $ref = getRef();
        $desc = "<strong>Withdraw</strong><br>" . $req->desc;
        withdraw::create(
            [
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'ref' => $ref,
            ]
        );
        createTransaction($req->account, $req->date, 0, $req->amount, $desc, $ref);

        return back()->with('success', 'Amount withdraw was successfull');
    }

    public function deleteWithdraw($ref)
    {
        withdraw::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();

        return back()->with('success', 'Withdraw was deleted');
    }
}
