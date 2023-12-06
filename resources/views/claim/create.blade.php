@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Create Claim</h4>
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
                            <label for="">Vendor</label>
                            <select name="vendor" class="form-control" id="">
                                @foreach ($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->title}}</option>
                                @endforeach
                            </select>
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
