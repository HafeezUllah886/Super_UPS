@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.SaleReturns') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body ">
                <div class="card-header">
                    <h5>{{ __('lang.InvoiceDetails') }}</h5>
                </div>
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.InvoiceNo') }}</th>
                                <th class="border-top-0">{{ __('lang.Customer') }}</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">{{ __('lang.Discount') }}</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th class="border-top-0">{{ __('lang.IsPaid') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> {{ $bill->id}} </td>
                                <td>@if (@$bill->customer_account->title)
                                    {{ @$bill->customer_account->title }}
                                @else
                                {{$bill->walking}} (Walk In)

                                @endif</td>
                                <td>{{ $bill->date }}</td>
                                <td>{{ $bill->discount ?? "0" }}</td>
                                <td id="billAmount">{{ $total }}</td>
                                <td>{{ $bill->isPaid }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body ">
                <div class="card-header">
                    <h5>{{ __('lang.ProductDetails') }}</h5>
                </div>
            <form method="post" action="{{url('/return/save/')}}/{{$bill->id}}">
                @csrf
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0">
                        <thead class="th-color">
                            <tr>
                                <th>{{ __('lang.Product') }}</th>
                                <th>{{ __('lang.Price') }}</th>
                                <th>{{ __('lang.SoldQty') }}</th>
                                <th>Claim</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = 0;
                            @endphp
                            @foreach ($bill->details as $products)
                            @php
                            $ser += 1;
                            @endphp
                            <tr>
                                <td> <input type="hidden" value="{{$products->product_id}}" name="id[]">{{ $products->product->name}} </td>
                                <td> <input type="number" readonly class="form-control  text-center" name="price[]" value="{{$products->price}}" id="price{{ $ser }}"> </td>
                                <td> <input type="number" readonly class="form-control text-center" value="{{$products->qty}}" id="qty{{ $ser }}"> </td>
                                <td> <button class="btn btn-warning" onclick="claim({{$products->product_id}}, {{ $products->product->name}}, {{$products->qty}})">Claim</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
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

</script>

@endsection
