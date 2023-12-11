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

    function price1(){

       var id = $('#product').find(":selected").val();
        $.ajax({
            method: 'get',
            url: "{{ url('/sale/getPrice/') }}/"+id,
            success: function(data){
               $('#stock').text(data.balance);
               $('#retail').val(data.product.price);
               $('#gst').val(data.product.gst);
               $('#wht').val(data.product.wht);
               $('#qty').attr('max', data.balance);

               cal();
            }
        });
    }

    function walkIn1(){

        var customer = $("#customer").find(':selected').val();

        if(customer == 0)
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
@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{__('lang.Sale')}}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">

                <form id="pro_form">
                <div class="row no-gutters">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="product">{{__('lang.SelectProduct')}}</label>
                            <select name="product" required id="product" onchange="price1()" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}" data-price="{{ $pro->price }}">{{ $pro->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="qty">{{__('lang.Quantity')}} (Stock: <span id="stock"></span>)</label>
                            <input type="number" required name="qty" oninput="cal()" value="1" id="qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="price1">Retail</label>
                            <input type="number" required name="retail" oninput="cal()" id="retail" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="price1">GST</label>
                            <input type="number" required name="gst" readonly id="gst" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="price1">WHT</label>
                            <input type="number" required name="wht" readonly id="wht" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="price1">%</label>
                            <input type="number" required name="percent" oninput="cal()" value="0" id="percent" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="price1">Net</label>
                            <input type="number" required name="price" readonly id="net" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-info" style="margin-top: 30px">Add</button>
                    </div>
                </div>
            </form>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{__('lang.Ser')}}</th>
                                <th class="border-top-0">{{__('lang.Category')}}</th>
                                <th class="border-top-0">{{__('lang.Product')}}</th>
                                <th class="border-top-0">{{__('lang.Quantity')}}</th>
                                <th class="border-top-0">{{__('lang.Price')}}</th>
                                <th class="border-top-0">{{__('lang.Amount')}}</th>
                                <th>{{__('lang.Action')}}</th>
                            </tr>
                        </thead>
                        <tbody id="items">

                        </tbody>
                    </table>
                    <form method="post" class="mt-5">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date">{{__('lang.Date')}}</label>
                                    <input type="date" name="date" value="{{ date("Y-m-d") }}" id="date" class="form-control">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customer">{{__('lang.SelectCustomer')}}</label>
                                    <select name="customer" id="customer" onchange="walkIn1()" class="select2">
                                        <option value=""></option>
                                        <option value="0">{{__('lang.WalkInCustomer')}}</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->title }} ({{ $customer->type }})</option>
                                        @endforeach
                                    </select>
                                    @error('customer')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="walkIn_box">
                                <div class="form-group">
                                    <label for="">{{__('lang.CustomerName')}}</label>
                                    <input type="text" name="walkIn" class="form-control">
                                    @error('walkIn')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="isPaid_box">
                                <div class="form-group">
                                    <label for="isPaid">{{__('lang.IsPaid')}}</label>
                                    <select name="isPaid" id="isPaid" onchange="abc()" class="form-control">
                                        <option value="Yes">{{__('lang.Yes')}}</option>
                                        <option Value="No">{{__('lang.No')}}</option>
                                        <option Value="Partial">{{__('lang.Partial')}}</option>
                                    </select>
                                    @error('isPaid')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="amount_box">
                                <div class="form-group">
                                    <label for="amount">{{__('lang.Amount')}}</label>
                                    <input type="number" name="amount" id="amount" class="form-control">
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" id="paidIn_box">
                                <div class="form-group">
                                    <label for="paidIn">{{__('lang.PaidIn')}}</label>
                                    <select name="paidIn" id="paidIn" class=" select2">
                                        <option></option>
                                        @foreach ($paidIns as $acct)
                                            <option value="{{ $acct->id }}">{{ $acct->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('paidIn')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 mt-4">
                                <input type="checkbox" class="mt-3" name="print" value="1" id="print">
                                <label for="print">Print with Taxes</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="desc">{{__('lang.Desc')}}</label>
                                    <textarea name="desc" id="desc" class="form-control"></textarea>
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="discount">{{__('lang.Discount')}}</label>
                                            <input type="number" name="discount" id="discount" class="form-control">
                                            @error('discount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-lg" style="margin-top: 30px">{{__('lang.Save')}}</button>
                                    <p class="btn btn-info" data-toggle="modal" style="margin-top: 40px" data-target="#modal">Purchase Scrap</p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Scrap Purchase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="scrapForm" action="{{ url('/scrap/purchase/create') }}">
                @csrf
                <input type="hidden" name="fromSale" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill">Customer Name</label>
                                <input type="text" name="customerName" required id="customerName" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill">Weight</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="weight" id="weight" required oninput="calculateRate()" class="form-control" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="basic-addon2">KG</span>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="srate">Rate</label>
                                <input type="Number" name="rate" step="any" required oninput="calculateRate()" id="srate1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="Number" required name="amount" id="amount1" oninput="calculateAmount()" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" required value="{{ date("Y-m-d") }}" id="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="account">Account</label>
                                <select name="account" class="form-control">
                                    @foreach ($paidIns as $account)
                                        <option value="{{ $account->id }}">{{ $account->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea name="desc" class="d-block w-100" id="desc" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" id="scrapBtn" class="btn btn-primary">{{ __('lang.Create') }}</button>
                </div>
            </form>
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

    function cal(){
        var qty = $("#qty").val();
        var retail = $("#retail").val();
        var gst = $("#gst").val();
        var wht = $("#wht").val();
        var percent = $("#percent").val();

        var netRetail = retail * qty;
        var netGST = gst * qty;
        var netWHT = wht * qty;

        var discount = (netRetail * percent) / 100;
        var afterPercent = netRetail - discount;
        var net = parseFloat(afterPercent) + parseFloat(netGST) + parseFloat(netWHT);
        $("#net").val(net);
    }
    $(document).ready(function() {
        $("#amount_box").css('display', 'none');
         $('#walkIn_box').css("display", "none");
        get_items();
    });

$('#pro_form').submit(function(e){
    e.preventDefault();
    var data = $('#pro_form').serialize();
    $.ajax({
        method: 'get',
        url: "{{url('/sale/store')}}",
        data: data,
        success: function(abc){
            get_items();
            if(abc == 'Existing')
            {
                Snackbar.show({
            text: "Already Added",
            duration: 3000,
            actionTextColor: '#fff',
            backgroundColor: '#e7515a'
            /* actionTextColor: '#fff',
            backgroundColor: '#00ab55' */
            });
            }
        }
    });
});


function get_items(){
    $.ajax({
        method: "GET",
        url: "{{url('/sale/draft/items')}}",
        success: function(respose){
            $("#items").html(respose);
        }
    });
}

function qty(id){
    var val = $("#qty"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/sale/update/draft/qty/')}}/"+id+"/"+val,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Quantity Updated",
            duration: 3000,
            /* actionTextColor: '#fff',
            backgroundColor: '#e7515a' */
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
        }
    });
}

function rate(id){
    var val = $("#rate"+id).val();
    $.ajax({
        method: "GET",
        url: "{{url('/sale/update/draft/rate/')}}/"+id+"/"+val,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Rate Updated",
            duration: 3000,
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
        }
    });
}

function deleteDraft(id){
    $.ajax({
        method: "GET",
        url: "{{url('/sale/draft/delete/')}}/"+id,
        success: function(respose){
            get_items();
            Snackbar.show({
            text: "Item Deleted",
            duration: 3000,
            actionTextColor: '#fff',
            backgroundColor: '#e7515a'
            /* actionTextColor: '#fff',
            backgroundColor: '#00ab55' */
            });
        }
    });
}

function calculateRate(){
        var weight = $("#weight").val();
        var rate = $("#srate1").val();

        var amount = weight * rate;

        $("#amount1").val(amount.toFixed(2));
    }
    function calculateAmount(){
        var weight = $("#weight").val();
        var amount = $("#amount1").val();

        var rate = amount / weight;

        $("#srate1").val(rate.toFixed(2));
    }



$("#scrapForm").on("submit", function(e){
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        url: "{{ url('/scrap/purchase/create') }}",
        method: "post",
        data: data,
        success: function(response){
           $("#modal").modal("hide");
           if(response == "Done")
           {
            Snackbar.show({
            text: "Scrap Purchased",
            duration: 3000,
            actionTextColor: '#fff',
            backgroundColor: '#00ab55'
            });
           }

        }
    });
});
</script>
@endsection
