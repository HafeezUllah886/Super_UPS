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
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{ __('lang.CreateReturn') }}</button>
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
                                <th class="border-top-0">{{ __('lang.InvoiceNo') }}</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">{{ __('lang.Customer') }}</th>
                                <th class="border-top-0">{{ __('lang.Details') }}</th>
                                <th class="border-top-0">{{ __('lang.PaidBy') }}</th>
                                <th class="border-top-0">{{ __('lang.PayableAmount') }}</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($saleReturns as $return)
                            <tr>
                                <td> {{ $return->bill_id}} </td>
                                <td>{{ $return->date }}</td>
                                <td>@if (@$return->bill->customer_account->title)
                                    {{ @$return->bill->customer_account->title }}
                                @else
                                {{$return->bill->walking}} (Walk In)
                                @endif</td>
                                <td>
                                    <table>
                                        <thead>
                                        <th>{{ __('lang.Product') }}</th>
                                        <th>{{ __('lang.Qty') }}</th>
                                        <th>{{ __('lang.Price') }}</th>
                                        <th>{{ __('lang.Amount') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($return->details as $details)
                                            @php
                                                $amount = $details->qty * $details->price;
                                            @endphp
                                            <tr>
                                                <td>{{ $details->product->name }}</td>
                                                <td>{{ $details->qty }}</td>
                                                <td>{{ round($details->price) }}</td>
                                                <td>{{ $amount }}</td>
                                            </tr>
                                            @if($return->deduction > 0)
                                            <tr>
                                                <td colspan="4">{{ __('lang.Deduction') }}: <strong>{{ round($return->deduction) }}</strong></td>
                                            </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                    </table>
                                </td>
                                <td>{{ @$return->account->title }}</td>
                                <td>{{ $return->amount }}</td>
                                <td> <a href="{{url('/return/delete/')}}/{{$return->ref}}" class="text-danger ">Delete</a> </td>
                                <td>
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
{{-- Model Starts Here --}}
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('lang.CreateReturn') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="bill">{{ __('lang.InvoiceNo') }}</label>
                        <input type="number" required name="bill" id="bill" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('lang.Create') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
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
</script>

@endsection
