@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Scrap Purchases</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">Create Purchase</button>
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
                                <th>{{ __('lang.InvoiceNo') }}</th>
                                <th>{{ __('lang.Customer') }}</th>
                                <th>{{ __('lang.Date') }}</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                       {{--  <tbody>
                            @foreach ($claims as $claim)
                            <tr>
                                <td> {{ $claim->salesID}} </td>
                                <td>@if (@$claim->bill->customer_account->title)
                                    {{ @$claim->bill->customer_account->title }}
                                @else
                                {{$claim->bill->walking}} (Walk In)
                                @endif</td>
                                <td>{{ $claim->date }}</td>
                                <td> {{ $claim->product->name}} </td>
                                <td>{{ $claim->qty }}</td>
                                <td>{{ $claim->reason }}</td>
                                <td>{{ $claim->status }}</td>
                                <td>
                                    <a href="{{url('/claim/delete/')}}/{{$claim->ref}}" class="text-danger ">Delete</a>
                                    @if($claim->status == 'Pending')
                                        <a href="{{url('/claim/approve/')}}/{{$claim->ref}}" class="text-success "> / Approve</a>
                                    @endif
                                </td>
                                <td>
                                </td>
                            </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Model Starts Here --}}
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Scrap Purchase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/scrap/purchase/create') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bill">Customer Name</label>
                                <input type="text" name="customerName" id="customerName" class="form-control">
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
                                <label for="rate">Rate</label>
                                <input type="Number" name="rate" required oninput="calculateRate()" id="rate" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="Number" name="amount" id="amount" readonly class="form-control">
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
                                    @foreach ($accounts as $account)
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
                    <button type="submit" class="btn btn-primary">{{ __('lang.Create') }}</button>
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
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete return?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    function calculateRate(){
        var weight = $("#weight").val();
        var rate = $("#rate").val();

        var amount = weight * rate;

        $("#amount").val(amount);
    }
</script>

@endsection
