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
                $("#price").val(data);

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
                <h4>Sale</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">

                <form id="pro_form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product">Select Product</label>
                            <select name="product" required id="product" onchange="price1()" class="select2">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}" data-price="{{ $pro->price }}">{{ $pro->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="qty">Quantity</label>
                            <input type="number" required name="qty" id="qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="price1">Price</label>
                            <input type="number" required name="price" id="price" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-info" style="margin-top: 30px">Add Product</button>
                    </div>
                </div>
            </form>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">Ser</th>
                                <th class="border-top-0">Product Name</th>
                                <th class="border-top-0">Quantity</th>
                                <th class="border-top-0">Price</th>
                                <th class="border-top-0">Amount</th>
                                <th>Action</th>
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
                                    <label for="date">Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date" class="form-control">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customer">Select Customer</label>
                                    <select name="customer" id="customer" onchange="walkIn1()" class="select2">
                                        <option value=""></option>
                                        <option value="0">Walk-in Vendor</option>
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
                                    <label for="">Customer Name</label>
                                    <input type="text" name="walkIn" class="form-control">
                                    @error('walkIn')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="isPaid_box">
                                <div class="form-group">
                                    <label for="isPaid">is Paid</label>
                                    <select name="isPaid" id="isPaid" onchange="abc()" class="form-control">
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Partial</option>
                                    </select>
                                    @error('isPaid')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2" id="amount_box">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control">
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" id="paidIn_box">
                                <div class="form-group">
                                    <label for="paidIn">Paid In</label>
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

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="desc">Description</label>
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
                                            <label for="discount">Discount</label>
                                            <input type="number" name="discount" id="discount" class="form-control">
                                            @error('discount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-lg" style="margin-top: 30px">Save Sale</button>
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

@endsection


@section('scripts')
<style>
    .dataTables_paginate {
        display: block
    }

</style>
<script>
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
</script>
@endsection