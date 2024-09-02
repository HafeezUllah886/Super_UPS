<?php

namespace App\Http\Controllers;

use App\Models\expense_categories;
use Illuminate\Http\Request;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cats = expense_categories::all();

        return view('finance.expenses_cats', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        expense_categories::create(
            [
                'exp_cat' => $request->exp_cat,
            ]
        );

        return back()->with('success', "Category Created");
    }

    /**
     * Display the specified resource.
     */
    public function show(expense_categories $expense_categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(expense_categories $expense_categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cat = expense_categories::find($id);

        $cat->update(
            [
                'exp_cat' => $request->exp_cat,
            ]
        );

        return back()->with('success', "Category Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(expense_categories $expense_categories)
    {
        //
    }
}
