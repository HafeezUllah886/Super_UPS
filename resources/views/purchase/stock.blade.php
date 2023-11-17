@extends('layout.dashboard')

@section('content')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.AvailableStock') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body new-user">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">{{ __('lang.AvailableStock') }}</th>
                                <th class="border-top-0">{{ __('lang.Price') }}</th>
                                <th class="border-top-0">{{ __('lang.StockValue') }}</th>
                                <th class="border-top-0">{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                            <tr>
                                <td> {{ $key }} </td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->balance}}</td>
                                <td><input type="number" class="form-control" id="id_{{$product->id}}" data-sym="{{$product->sym}}" data-qty="{{$product->balance}}" oninput="checkValue(this, {{$product->id}})"></td>
                                <td><input type="number" class="form-control" id="subTotal_{{$product->id}}" readonly></td>
                                <td><a href="{{ url('/stock/details/') }}/{{ $product->id }}/{{ date("Y-m-01") }}/{{ date("Y-m-t") }}" class="btn btn-info">Details</a></td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right;"> <strong>Total</strong> </td>
                                <td style="text-align: center;"> <strong id="total"></strong> </td>
                            </tr>
                        </tfoot>
                    </table>
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

    function checkValue(input, id)
    {
        var sym = $(input).data('sym');
        var qty = $(input).data('qty');
        var val = input.value;
        var currentValue = 0;
        if(sym == '*')
        {
            currentValue = qty * val;
        }
        if(sym == '/')
        {
            currentValue = qty / val;
        }
        $("#subTotal_"+id).val(currentValue);
        var total = 0;
        $('input[id^="subTotal_"]').each(function() {
    
        var inputValue = parseFloat($(this).val()) || 0;
        total += inputValue;
       
        });
        $("#total").html(parseFloat(total).toLocaleString());
    }
</script>
@endsection
