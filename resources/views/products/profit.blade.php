@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Profit / Loss</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <strong>APP</strong> = Avg Purchase Price, <strong>ASP</strong> = Avg Sale Price, <strong> PPU</strong> = Profit Per Unit
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">Ser</th>
                                <th class="border-top-0">Product Name</th>
                            {{--     <th class="border-top-0">Total Purchased</th> --}}
                                <th class="border-top-0">APP</th>
                                <th class="border-top-0">ASP</th>
                                <th class="border-top-0">PPU</th>
                                <th class="border-top-0">Sold</th>
                                <th class="border-top-0">Return</th>
                                <th class="border-top-0">Profit</th>
                                <th class="border-top-0">Stock</th>
                                <th class="border-top-0">Stock Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = 0;
                                $total = 0;
                            @endphp

                            @foreach ($products as $product)
                            @php
                                $ser += 1;
                                $total += ($product->sale_quantity - $product->return) * $product->ppu;
                                $net_profit = 0;
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td> {{ $product->name }} </td>
                               {{--  <td> {{ $product->purchase_quantity}} </td> --}}
                                <td> {{ round($product->average_purchase_price,2)}} </td>
                                <td> {{ round($product->average_sale_price,2)}} </td>
                                <td> {{ round($product->ppu,2)}} </td>
                                <td> {{ $product->sale_quantity}} </td>
                                <td> {{ $product->return}} </td>
                                <td> {{ round(($product->sale_quantity - $product->return) * $product->ppu,2) }} </td>
                                <td> {{ $product->available_stock}} </td>
                                <td> {{ $product->available_stock * $product->price}} </td>
                            </tr>
                            @endforeach
                            <tr>
                            <td colspan="7" style="text-align: right;"> <strong>Total</strong> </td>
                            <td> <strong>{{ round($total,2) }}</strong> </td>
                        </tr>

                        <tr>
                            <td colspan="7" style="text-align: right;"> <strong>Discounts</strong> </td>
                            <td> <strong>{{ round($discounts,2) }}</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: right;"> <strong>Expenses</strong> </td>
                            <td> <strong>{{ round($expense,2) }}</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: right;"> <strong>Net Profit</strong> </td>
                            <td> <strong>{{ round($total - $discounts - $expense,2) }}</strong> </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    .dataTables_paginate {
        display: block
    }

</style>
<script>
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>

@endsection
