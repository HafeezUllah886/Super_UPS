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
                <h5 class="modal-title">Create Claim</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/claim/create') }}">
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
