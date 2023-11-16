@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="col-md-6">
                    <h4>{{__('lang.Profit/Loss')}}</h4>
                </div>
                <div class="col-md-6">
                    <table>
                        <tr>
                            <td>From: </td>
                            <td> <input type="date" class="form-control" value="{{ $from }}" id="from"> </td>
                            <td> &nbsp; - &nbsp; </td>
                            <td> To: </td>
                            <td> <input type="date" class="form-control" value="{{ $to }}" id="to"> </td>
                            <td> &nbsp;<button class="btn btn-info" id="btn">Filter</button> </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                {{-- <strong>APP</strong> = Avg Purchase Price (اوسط خریدی قیمت), <strong>ASP</strong> = Avg Sale Price(اوسط فروخت کی قیمت), <strong> PPU</strong> = Profit Per Unit (قیمت فی دانا) --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{__('lang.Ser')}}</th>
                                <th class="border-top-0">{{__('lang.Product')}}</th>
                            {{--     <th class="border-top-0">Total Purchased</th> --}}
                                <th class="border-top-0">Purchase Price</th>
                                <th class="border-top-0">Sale Price</th>
                                <th class="border-top-0">Profit</th>
                                <th class="border-top-0">Qty Sold</th>
                                <th class="border-top-0">Sub Profit</th>
                                <th class="border-top-0">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = 0;
                                $totalProfit = 0;
                            @endphp

                            @foreach ($sales as $sale)
                            @php
                                $ser += 1;
                                $totalProfit += $sale->profit * $sale->qty;
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td> {{ $sale->product->name }} </td>
                                <td> {{ $sale->purchasePrice }} </td>
                                <td> {{ $sale->salePrice }} </td>
                                <td> {{ $sale->profit }} </td>
                                <td> {{ numberFormat($sale->qty) }} </td>
                                <td> {{numberFormat($sale->qty * $sale->profit)}} </td>
                                <td> {{ numberFormat($sale->stock)}} </td>
                            </tr>
                            @endforeach
                            <tr>
                            <td colspan="6" style="text-align: right;"> <strong>{{__('lang.Total')}}</strong> </td>
                            <td> <strong>{{ numberFormat($totalProfit) }}</strong> </td>
                        </tr>

                        <tr>
                            <td colspan="6" style="text-align: right;"> <strong>{{__('lang.Expenses')}}</strong> </td>
                            <td> <strong>{{ numberFormat($expense) }}</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="6" style="text-align: right;"> <strong>{{__('lang.NetProfit')}}</strong> </td>
                            <td> <strong>{{ numberFormat($totalProfit - $expense) }}</strong> </td>
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

    $("#btn").click(function (){
        var from = $("#from").val();
        var to = $("#to").val();

        window.open("{{ url('/profit/') }}/"+from+"/"+to, '_self');
    });
</script>

@endsection
