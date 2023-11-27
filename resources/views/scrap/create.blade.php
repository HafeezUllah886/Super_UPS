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
                                <td> <a class="btn btn-warning" onclick="claim({{$products->product_id}}, '{{ $products->product->name}}', {{$products->qty}})">Claim</a></td>
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
<div class="modal fade" id="claimModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Claim Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{url('/claim/store')}}" method="post">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="saleID" value="{{$bill->id}}">
                <input type="hidden" name="productID" id="productID">
                <div class="form-group">
                    <label for="product">Product</label>
                    <input type="text" id="product" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="qty">Claim Qty (Max: <span id="qtyMax"></span> )</label>
                    <input type="number" name="qty" id="qty" class="form-control" >
                </div>
                <div class="form-group">
                    <label for="reason">Reason</label>
                    <input type="text" name="reason" id="reason" class="form-control" >
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" value="{{date("Y-m-d")}}" class="form-control" >
                </div>
                <div class="row">
                    <div class="col">
                    <label>Status</label><br>
                    <input type="radio" name="status" checked value="Pending"> Wait for Approval <br>
                    <input type="radio" name="status" value="Claimed"> Approve
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
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
