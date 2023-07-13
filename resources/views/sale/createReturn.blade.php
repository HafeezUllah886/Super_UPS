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
                                <th class="border-top-0">Discount</th>
                                <th class="border-top-0">Bill Amount</th>
                                <th class="border-top-0">Ispaid</th>
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
                                <td>{{ $bill->discount }}</td>
                                <td id="billAmount"></td>
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
                    <h5>Products Details</h5>
                </div>
            <form method="post" action="{{url('/return/save/')}}/{{$bill->id}}">
                @csrf
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
                                <td> <input type="hidden" value="{{$products->product_id}}" name="id[]">{{ $products->product->name}} </td>
                                <td> <input type="number" readonly class="form-control  text-center" name="price[]" value="{{$products->price}}" id="price{{ $ser }}"> </td>
                                <td> <input type="number" readonly class="form-control text-center" value="{{$products->qty}}" id="qty{{ $ser }}"> </td>
                                <td><input type="number" class="form-control text-center" onchange="updateAmount({{ $ser }}, {{ $products->price }})" name="returnQty[]" id="returnQty{{ $ser }}" value="0" max="{{$products->qty}}"></td>
                                <td> <input type="number" class="form-control text-center" readonly id="amount{{ $ser }}" name="amount[]" value="0"> </td>
                            </tr>
                            
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-right">Total</td>
                                <td colspan="4" class="text-center" id="totalAmount">0</td>
                            </tr>

                        </tbody>
                    </table>

                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="netAmount">Return Amount</label>
                            <input type="number" name="amount" value="0" id="netAmount" class="form-control">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="paidFrom">Paid From</label>
                                <select name="paidFrom" id="paidFrom" class="form-control">
                                    <option value="">Select Account</option>
                                    @foreach ($paidFroms as $acct)
                                        <option value="{{ $acct->id }}">{{ $acct->title }}</option>
                                    @endforeach
                                </select>
                                @error('paidFrom')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Return Date</label>
                            <input type="date" name="date" value="{{date("Y-m-d")}}" class="form-control">
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                       <button type="submit" class="btn btn-success mt-4">Save Return</button>
                    </div>
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
    $(document).ready(function(){
    var total = 0;
    var count = 1;
    var priceInput, qtyInput;

    while ($('#price' + count).length > 0 && $('#qty' + count).length > 0) {
      priceInput = parseFloat($('#price' + count).val());
      qtyInput = parseFloat($('#qty' + count).val());

      if (!isNaN(priceInput) && !isNaN(qtyInput)) {
        total += priceInput * qtyInput;
      }

      count++;
    }
        $("#billAmount").html(total);
    });
   function updateAmount(id, price){
        var returnQty = $("#returnQty"+id).val();
        var qty = $("#qty"+id).val();
        if(returnQty > qty){
            $("#returnQty"+id).val(qty);
            returnQty = qty;
        }
        var amount = returnQty * price;
        $("#amount"+id).val(amount);

        var sum = 0;
        $('input[id^="amount"]').each(function() {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            sum += value;
        }
        });
        $("#totalAmount").html(sum);
        var netAmount = sum - {{$bill->discount}};
        $("#netAmount").val(netAmount);
    }

</script>

@endsection
