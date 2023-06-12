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
                <h4>Purchasing</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="vendor">Select Vendor</label>
                                <select name="vendor" id="vendor" class="form-control">
                                    <option value=""></option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->it }}">{{ $vendor->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="isPaid">is Paid</label>
                                <select name="isPaid" id="isPaid" onchange="abc()" class="form-control">
                                    <option>Yes</option>
                                    <option>No</option>
                                    <option>Partial</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" id="amount_box">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2" id="paidIn_box">
                            <div class="form-group">
                                <label for="paidIn">Paid In</label>
                                <select name="paidIn" id="paidIn" class="form-control">
                                    <option></option>
                                    @foreach ($paidIns as $acct)
                                        <option value="{{ $acct->id }}">{{ $acct->title }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">

                            <button type="submit" class="btn btn-success" style="margin-top: 30px">Save Bill</button>

                        </div>
                    </div>
                </form>
                <form id="pro_form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product">Select Product</label>
                            <select name="product" required id="product" class="form-control">
                                <option value=""></option>
                                @foreach ($products as $pro)
                                    <option value="{{ $pro->id }}">{{ $pro->name }}</option>
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
                            <label for="rate">Purchase Rate</label>
                            <input type="number" required name="rate" id="rate" class="form-control">
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
                                <th class="border-top-0">Rate</th>
                                <th class="border-top-0">Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

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
    $(document).ready(function() {
        $("#amount_box").css('display', 'none');
    });

$('#pro_form').submit(function(e){
    e.preventDefault();
    var data = $('#pro_form').serialize();
    $.ajax({
        method: 'get',
        url: "{{url('/purchase/store')}}",
        data: data,
        success: function(abc){
            console.log(abc);
        }
    });
});

</script>
@endsection
