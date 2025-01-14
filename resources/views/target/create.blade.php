@extends('layout.dashboard')
<script>
     function abc() {
        var isPaid = $('#isPaid').find(":selected").val();
        if (isPaid == 'No') {
            $('#paidIn_box').css('display', 'none');
            $("#amount_box").css('display', 'none');
        } else if (isPaid == 'Partial') {
            $("#amount_box").css('display', 'block');
            $('#paidIn_box').css('display', 'block');
        } else {
            $("#amount_box").css('display', 'none');
            $('#paidIn_box').css('display', 'block');
        }
    }

    function walkIn1(){
        console.log(vendor);
        var vendor = $("#vendor").find(':selected').val();

        if(vendor == 0)
        {
            $('#walkIn_box').css("display", "block");
            $('#isPaid').val('Yes');
            $('#isPaid_box').css('display', 'none');
            abc();
        }
        else
        {
            $('#walkIn_box').css("display", "none");
            $('#isPaid_box').css('display', 'block');
        }

    }
</script>
<style>
              input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

</style>
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.Purchase') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">
                <form id="pro_form">
                <div class="row no-gutters">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="product">{{ __('lang.SelectProduct') }}</label>
                            <select name="product" onchange="getSingleProduct()" required id="product" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}">{{ $pro->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
                <div class="table-responsive mt-3">
                    <form method="post" action="{{route('target.store')}}" class="mt-5">
                        @csrf
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Product') }}</th>
                                <th class="border-top-0">{{ __('lang.Quantity') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="products_list">

                        </tbody>
                    </table>
                    
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="sdate">Start Date</label>
                                    <input type="date" name="startDate" value="{{ date("Y-m-d") }}" id="sdate" class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="edate">End Date</label>
                                    <input type="date" name="endDate" value="{{ date("Y-m-d") }}" id="edate" class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="customerID">Customer</label>
                                    <select name="customerID" id="customerID" required class="select2">
                                        <option value=""></option>
                                        <option value="0">{{ __('lang.WalkInVendor') }}</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->title }} </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="desc">{{ __('lang.Desc') }}</label>
                                    <textarea name="notes" id="desc" class="form-control"></textarea>
                                   
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                    <button type="submit" class="btn btn-success btn-lg" style="margin-top: 30px">{{ __('lang.Save') }}</button>

                            </div>
                        </div>
                    </form>
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
    var existingProducts = [];

function getSingleProduct() {
    var id = $("#product").find(':selected').val();

    $.ajax({
        url: "{{ url('/getproduct/') }}/" + id,
        method: "GET",
        success: function(product) {
            let found = $.grep(existingProducts, function(element) {
                return element === product.id;
            });
            if (found.length > 0) {

            } else {
                var id = product.id;
                var html = '<tr id="row_' + id + '">';
                html += '<td class="no-padding" width="70%">' + product.name + '</td>';
                html += '<td class="no-padding"><input type="number" name="qty[]" oninput="updateChanges(' + id +')" min="0.1" required step="any" value="1" class="form-control text-center" id="qty_' + id + '"></td>';
                html += '<td> <span class="btn btn-sm btn-danger" onclick="deleteRow('+id+')">X</span> </td>';
                html += '<input type="hidden" name="id[]" value="' + id + '">';
                html += '</tr>';
                $("#products_list").prepend(html);
              
                existingProducts.push(id);
            }
        }
    });
}

function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_'+id).remove();
        }



</script>
@endsection
