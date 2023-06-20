<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\deposit;
use App\Models\expense;
use App\Models\ledger;
use App\Models\transactions;
use App\Models\transfer;
use App\Models\withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function accounts(){
        $accounts = account::where('type', 'Business')->get();

        return view('finance.accounts')->with(compact('accounts'));
    }

    public function storeAccount(request $req, $type){
        $check = Account::where('title', $req->title)->count();
        if($check > 0){
            return back()->with('error', 'Account already exists');
        }
        if($type == 'Business'){
            $account = account::create(
                [
                    'title' => $req->title,
                    'type' => $type,
                    'category' => $req->cat,
                ]
            );
        }
        else
        {
            $account = account::create(
                [
                    'title' => $req->title,
                    'type' => $type,
                    'phone' => $req->phone,
                    'address' => $req->address,
                ]
            );
        }
        $ref = getRef();
        if($req->amount != 0) {
            createTransaction($account->id, now(), "$req->amount", "0", "Initial Amount", $ref);
        }

        addLedger($req->date, "Initial Amount", $req->title, "Account Created", $req->amount, $ref);
        return back()->with('success', 'Successfully Created');
    }

    public function editAccount(request $req, $type){
        $check = Account::where('id' , '!=', $req->id)->where('title', $req->title)->count();
        if($check > 0){
            return back()->with('error', 'Already exists');
        }
        if($type == 'Business'){
            $account = account::where('id', $req->id)->update(
                [
                    'title' => $req->title,
                ]
            );
        }
        else
        {
            $account = account::where('id', $req->id)->update(
                [
                    'title' => $req->title,
                    'phone' => $req->phone,
                    'address' => $req->address,
                ]
            );
        }

        return back()->with('success', 'Successfully Updated');
    }

    public function deleteAccount($id){
        if(transactions::where('account_id', $id)->count() > 0){
            return back()->with('error', 'Unable to delete');
        }

        transactions::where('account_id', $id)->delete();
        account::where('id', $id)->delete();

        return back()->with('success', 'Deleted successfully');
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
        $title = account::find($req->account);
        createTransaction($req->account, $req->date, $req->amount, 0, $desc, $ref);
        addLedger($req->date, "Deposit", $title->title, "Amount Deposited", $req->amount, $ref);
        return back()->with('success', 'Amount deposit was successfull');
    }

    public function deleteDeposit($ref)
    {
        deposit::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();

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
        $title = account::find($req->account);
        createTransaction($req->account, $req->date, 0, $req->amount, $desc, $ref);
        addLedger($req->date, "Withdraw", $title->title, "Amount Withdrawn", $req->amount, $ref);
        return back()->with('success', 'Amount withdraw was successfull');
    }

    public function deleteWithdraw($ref)
    {
        withdraw::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();

        return back()->with('success', 'Withdraw was deleted');
    }

    public function vendors(){
        $accounts = account::where('type', 'Vendor')->get();
        $to_accounts = account::where('type', 'Business')->get();

        return view('vendor.list')->with(compact('accounts', 'to_accounts'));
    }

    public function customers(){
        $accounts = account::where('type', 'Customer')->get();
        $to_accounts = account::where('type', 'Business')->get();
        return view('customer.list')->with(compact('accounts', 'to_accounts'));
    }


    public function expense(){
        $expenses = expense::orderBy('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        return view('finance.expenses')->with(compact('expenses', 'accounts'));
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
                'ref' => $ref,
            ]
        );
        createTransaction($req->account, $req->date, 0, $req->amount, $desc, $ref);

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

        return back()->with('success', 'Expense deleted');
    }

    public function transfer(){
        $from_accounts = account::where('type', '!=', 'Vendor')->get();
        $to_accounts = account::where('type', '!=', 'Customer')->get();

        $transfers = transfer::with('from_account', 'to_account')->orderBy('id', 'desc')->get();
        return view('finance.transfer')->with(compact('from_accounts', 'to_accounts', 'transfers'));

    }

    public function storeTransfer(request $req){
        if($req->from == $req->to)
        {
            return back()->with('error', "Please select different accounts to transfer");
        }

        $ref = getRef();

        transfer::create(
            [
                'from' => $req->from,
                'to' => $req->to,
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'ref' => $ref,
            ]
        );

        $from = account::find($req->from);
        $to = account::find($req->to);

        $desc = "<strong>Transfer to ".$to->title."</strong><br>" . $req->desc;
        $desc1 = "<strong>Transfer from ".$from->title."</strong><br>" . $req->desc;

        if($from->type == 'Customer' && $to->type == 'Business' || $from->type == 'Business' && $to->type == 'Business'){
            createTransaction($req->from, $req->date, 0, $req->amount, $desc, $ref);
            createTransaction($req->to, $req->date, $req->amount, 0, $desc1, $ref);
        }
        else{
            createTransaction($req->from, $req->date, 0, $req->amount, $desc, $ref);
            createTransaction($req->to, $req->date, 0, $req->amount, $desc1, $ref);
        }

        if($from->type == 'Customer' && $to->type == 'Business'){
            addLedger($req->date, $from->title, $to->title, "Received from Customer", $req->amount, $ref);
        }
        if($from->type == 'Business' && $to->type == 'Customer'){
            addLedger($req->date, $to->title, $from->title, "Payment to Customer", $req->amount, $ref);
        }
        if($from->type == 'Vendor' && $to->type == 'Business'){
            addLedger($req->date, $from->title, $to->title, "Received from Vendor", $req->amount, $ref);
        }
        if($from->type == 'Business' && $to->type == 'Vendor'){
            addLedger($req->date, $to->title, $from->title, "Payment to Vendor", $req->amount, $ref);
        }
        return back()->with('success', 'Amount Transfered');
    }

    public function deleteTransfer($ref)
    {
        transfer::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();

        return back()->with('success', 'Transfer deleted');
    }
}
