@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.PurchaseHistory') }}</h4>
                <a href="{{url('/purchase')}}" class="btn btn-success">{{ __('lang.CreateNew') }}</a>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable">
                        <thead class="th-color">
                            <tr>
<<<<<<< HEAD
                                <th class="border-top-0">{{ __('lang.BillNo') }}</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">{{ __('lang.Vendor') }}</th>

                                <th class="border-top-0">{{ __('lang.Details') }}</th>
                                <th class="border-top-0">{{ __('lang.Amount') }}</th>
                                <th class="border-top-0">{{ __('lang.AmountPaid') }}</th>
                                <th class="border-top-0">{{ __('lang.Payment') }}</th>
                                <th class="border-top-0">{{ __('lang.PaidBy') }}</th>
                                <th>{{ __('lang.Action') }}</th>
=======
                                <th class="border-top-0">Bill No.</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Vendor</th>
                                <th class="border-top-0">Details</th>
                                <th class="border-top-0">Amount</th>
                                <th class="border-top-0">Amount Paid</th>
                                <th class="border-top-0">Payment</th>
                                <th class="border-top-0">Paid From</th>
                                <th>Action</th>
>>>>>>> 797384e (adding size)
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($history as $bill)
                            <tr>
                                <td> {{ $bill->id }} </td>
                                <td>{{ $bill->date }}</td>
                                <td>@if (@$bill->vendor_account->title)
                                    {{ @$bill->vendor_account->title }} ({{  @$bill->vendor_account->type }})

                                @else
                                {{$bill->walking}} (Walk In)

                                @endif</td>

                                <td>
                                    <table class="table">
<<<<<<< HEAD
                                        <th>{{ __('lang.Product') }}</th>
                                        <th>{{ __('lang.Size') }}</th>
                                        <th>{{ __('lang.Qty') }}</th>
                                        <th>{{ __('lang.Price') }}</th>
                                        <th>{{ __('lang.Amount') }}</th>
=======
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Amount</th>
>>>>>>> 797384e (adding size)
                                        @foreach ($bill->details as $data1)
                                        @php
                                        $subTotal = $data1->qty * $data1->rate;
                                        @endphp
                                        <tr>
                                            <td>{{$data1->product->name}}</td>
                                            <td>{{$data1->product->size}}</td>
                                            <td>{{$data1->qty}}</td>
                                            <td>{{round($data1->rate,2)}}</td>
                                            <td>{{$subTotal}}</td>
                                        </tr>
                                        @endforeach

                                    </table>
                                </td>
                                <td>{{ getPurchaseBillTotal($bill->id) }}</td>
                                <td>@if($bill->isPaid == 'Yes') {{ "Full Payment" }} @elseif($bill->isPaid == 'No') {{ "UnPaid" }} @else {{ $bill->amount }} @endif</td>
                                <td>{{ $bill->isPaid}}</td>
                                <td>{{ @$bill->account->title}}</td>
                                <td>
                                    <a href="{{ url('/purchase/delete/') }}/{{ $bill->ref }}" class="btn btn-danger">Delete</a>
                                    <a href="{{ url('/purchase/edit/') }}/{{ $bill->id }}" class="btn btn-info">Edit</a>
                                </td>
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
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>

@endsection
