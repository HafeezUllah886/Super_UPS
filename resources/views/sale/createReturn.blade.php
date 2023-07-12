@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Sale Returns</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body ">
                <div class="card-header">
                    <h5>Invouce / Bill Details</h5>
                </div>
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">Invoice #</th>
                                <th class="border-top-0">Customer</th>
                                <th class="border-top-0">Sale Date</th>
                                <th class="border-top-0">Ispaid</th>
                                <th class="border-top-0">Discount</th>
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
                                <td>{{ $bill->isPaid }}</td>
                                <td>{{ $bill->discount }}</td>
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
                    <h5>Products Details</h5>
                </div>
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">Product</th>
                                <th class="border-top-0">Price</th>
                                <th class="border-top-0">Sold Qty</th>
                                <th class="border-top-0">Return Qty</th>
                                <th class="border-top-0">Return Amount</th>
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
                                <td> <input type="hidden" name="id[]">{{ $products->product->name}} </td>
                                <td>{{$products->price}}</td>
                                <td>{{$products->qty}}</td>
                                <td><input type="number" class="form-control text-center" onchange="updateAmount({{ $products->id }}, {{ $products->price }})" name="returnQty[]" id="returnQty{{ $products->id }}" value="0" max="{{$products->qty}}"></td>
                                <td> <input type="number" class="form-control text-center" readonly id="amount{{ $products->id }}" name="amount[]"> </td>
                            </tr>
                            @endforeach


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

   function updateAmount(id, price){
        var qty = $("#returnQty"+id).val();
        var amount = qty * price;
        $("#amount"+id).val(amount);
    }
</script>

@endsection
