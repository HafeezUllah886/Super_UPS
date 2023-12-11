@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Create Claim (Amount)</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body ">
              <form action="{{ url('/claim/amount/store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Vendor</label>
                            <select name="vendor" required class="form-control" id="">
                                @foreach ($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Customer</label>
                            <select name="customer" onchange="checkCustomer()" required class="form-control" id="customer">
                                <option value="0">Walk-In Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->title}}</option>
                                @endforeach
                            </select>
                            <input type="text" name="walkin" id="walkin" class="form-control" placeholder="Enter Customer Name" id="walkin">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Product</label>
                            <select name="product" required class="form-control" id="">
                                @foreach ($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Quantity</label>
                            <input type="number" required name="qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Customer Amount</label>
                            <input type="number" required name="customer_amount" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Vendor Amount</label>
                            <input type="number" required name="amount" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date" required name="date" value="{{ date("Y-m-d") }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status1">Status</label>
                            <select name="status" onchange="checkStatus()" class="form-control" id="status1">
                                <option value="Pending">Wait for Approval</option>
                                <option value="Claimed">Approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 payment_status d-none">
                        <div class="form-group">
                            <label for="payment_status">Payment Status</label>
                            <select name="payment_status" class="form-control" id="payment_status">
                                <option value="Unpaid">Unpaid</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 accounts d-none">
                        <div class="form-group">
                            <label for="">Account</label>
                            <select name="account" required class="form-control" id="">
                                @foreach ($accounts as $account)
                                    <option value="{{$account->id}}">{{$account->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Reason</label>
                            <textarea required name="reason" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" class="btn btn-success mt-1">Save</button>
                    </div>
                </div>
              </form>

            </div>
        </div>
    </div>

</div>
@endsection
@section('scripts')
<script>
    function checkCustomer(){
        var customer = $("#customer").find(":selected").val();
        if(customer != 0)
        {
            $("#walkin").css("display","none");

        }
        else
        {
            $("#walkin").css("display","block");

        }
    }

    function checkStatus()
    {
        var status = $("#status1").find(":selected").val();

        if(status == "Pending")
        {
            $(".payment_status").addClass("d-none");
            $(".accounts").addClass("d-none");
            $("#payment_status").val("Unpaid");
        }
        else
        {
            $(".payment_status").removeClass("d-none");
            $(".accounts").removeClass("d-none");
        }
    }
</script>

@endsection
