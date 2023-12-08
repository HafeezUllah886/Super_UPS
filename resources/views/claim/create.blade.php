@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Create Claim (Product)</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white">
            <div class="card-body ">
              <form action="">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Vendor</label>
                            <select name="vendor" class="form-control" id="">
                                @foreach ($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Customer</label>
                            <input type="text" name="customer" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Product</label>
                            <select name="product" class="form-control" id="">
                                @foreach ($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Quantity</label>
                            <input type="number" name="qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Reason</label>
                            <input type="text" name="reason" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="Radio" value="Pending" id="pending" name="status" class="form-control">
                            <label for="pending">Wait for Approval</label>
                        </div>
                        <div class="form-group">
                            <input type="Radio" value="Approved" id="approved" name="status" class="form-control">
                            <label for="approved">Wait for Approval</label>
                        </div>
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
    function claim(id, name, qty){
        $("#product").val(name);
        $("#productID").val(id);
        $("#qtyMax").text(qty);
        $("#qty").attr("max", qty);
     $("#claimModal").modal('show');
    }
</script>

@endsection
