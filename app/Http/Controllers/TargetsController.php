<?php

namespace App\Http\Controllers;

use App\Models\targets;
use App\Http\Controllers\Controller;
use App\Models\account;
use App\Models\accounts;
use App\Models\products;
use App\Models\targetDetails;
use App\Models\units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $targets = targets::orderBy("endDate", 'desc')->get();
        foreach($targets as $target)
        {
            $totalTarget = 0;
            $totalSold = 0;
            
           foreach($target->details as $product)
           {
                $qtySold = DB::table('sales')
                ->join('sale_details', 'sales.id', '=', 'sale_details.bill_id')
                ->where('sales.customer', $target->customerID)  // Filter by customer ID
                ->where('sale_details.product_id', $product->productID)  // Filter by product ID
                ->whereBetween('sale_details.date', [$target->startDate, $target->endDate])  // Filter by date range
                ->sum('sale_details.qty');
                $product->sold = $qtySold;
                $targetQty = $product->qty;

                if($qtySold > $targetQty)
                {
                    $qtySold = $targetQty;
                }
                $product->per = $qtySold / $targetQty * 100;
               

                $totalTarget += $targetQty;
                $totalSold += $qtySold;
           }
           $totalPer = $totalSold / $totalTarget  * 100;
           $target->totalPer = $totalPer;

            if($target->endDate > now())
            {

                $target->campain = "Open";
                $target->campain_color = "success";
            }
            else
            {
                $target->campain = "Closed";
                $target->campain_color = "warning";
            }

            if($totalPer >= 100)
            {
                $target->goal = "Target Achieved";
                $target->goal_color = "success";
            }
            elseif($target->endDate > now() && $totalPer < 100)
            {
                $target->goal = "In Progress";
                $target->goal_color = "info";
            }
            else
            {
                $target->goal = "Not Achieved";
                $target->goal_color = "danger";
            }
        }
        return view('target.index', compact('targets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $customers = account::where('type', 'Customer')->get();
        return view('target.create', compact('products', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $target = targets::create(
                [
                    'customerID'    => $request->customerID,
                    'startDate'     => $request->startDate,
                    'endDate'       => $request->endDate,
                    'notes'         => $request->notes,
                ]
            );

            $ids = $request->id;

            foreach($ids as $key => $id)
            {
                $qty = $request->qty[$key];
                targetDetails::create(
                    [
                        'targetID'      => $target->id,
                        'productID'     => $id,
                        'qty'           => $qty,
                    ]
                );
            }
            DB::commit();
            return back()->with("success", "Target Saved");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(targets $target)
    {
            $totalTarget = 0;
            $totalSold = 0;
            
           foreach($target->details as $product)
           {
                $qtySold = DB::table('sales')
                ->join('sale_details', 'sales.id', '=', 'sale_details.bill_id')
                ->where('sales.customer', $target->customerID)  // Filter by customer ID
                ->where('sale_details.product_id', $product->productID)  // Filter by product ID
                ->whereBetween('sale_details.date', [$target->startDate, $target->endDate])  // Filter by date range
                ->sum('sale_details.qty');
                
                $targetQty = $product->qty;

                if($qtySold > $targetQty)
                {
                    $qtySold = $targetQty;
                }
                $product->sold = $qtySold;
                $product->per = $qtySold / $targetQty * 100;

                $totalTarget += $targetQty;
                $totalSold += $qtySold;
           }
           $totalPer = $totalSold / $totalTarget * 100;
           $target->totalPer = $totalPer;

            if($target->endDate > now())
            {

                $target->campain = "Open";
                $target->campain_color = "success";
            }
            else
            {
                $target->campain = "Closed";
                $target->campain_color = "warning";
            }

            if($totalPer >= 100)
            {
                $target->goal = "Target Achieved";
                $target->goal_color = "success";
            }
            elseif($target->endDate > now() && $totalPer < 100)
            {
                $target->goal = "In Progress";
                $target->goal_color = "info";
            }
            else
            {
                $target->goal = "Not Achieved";
                $target->goal_color = "danger";
            }
        return view('target.view', compact('target'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(targets $targets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, targets $targets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $target = targets::find($id);
        foreach($target->details as $detail)
        {
            $detail->delete();
        }
        $target->delete();
        session()->forget('confirmed_password');
        return to_route('target.index')->with("success", "Target Deletes");
    }

    public function getSignleProduct($id)
    {
        $product = products::find($id);
        return $product;
    }
}
