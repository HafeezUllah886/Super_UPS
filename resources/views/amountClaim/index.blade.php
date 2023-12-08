@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Claims (Amount)</h4>
                <a href="{{url('/claim/amount/create')}}" class="btn btn-success">Create Claim</a>
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
                                <th>{{ __('lang.Customer') }}</th>
                                <th>{{ __('lang.Date') }}</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($claims as $claim)
                            <tr>
                                <td>@if (@$claim->customer_account->title)
                                    {{ @$claim->customer_account->title }} ({{  @$claim->customer_account->type }})
                                @else
                                {{$claim->walkin}} (Walk In)

                                @endif</td>

                                <td>{{ $claim->date }}</td>
                                <td> {{ $claim->product->name}} </td>
                                <td>{{ $claim->qty }}</td>
                                <td>{{ $claim->amount }}</td>
                                <td>{{ $claim->reason }}</td>
                                <td>{{ $claim->status }}</td>
                                <td>{{ $claim->payment_status }}</td>
                                <td>
                                    <a href="{{url('/claim/amount/delete/')}}/{{$claim->ref}}" class="text-danger ">Delete</a>
                                    @if($claim->status == 'Pending')
                                        <span class="text-success" onclick="approvalp({{ $claim->id }})"> / Approve</span>
                                    @endif
                                    @if($claim->status == 'Claimed' && $claim->payment_status == "Unpaid")
                                    <span class="text-warning " onclick="payment({{ $claim->id }})"> / Pay</span>
                                @endif
                                </td>
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
<div class="modal" id="approval" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark as Approved</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/claim/amount/approve') }}">
                @csrf
                <input type="hidden" id="approvalID" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="payment_status">Payment Status</label>
                        <select name="payment_status" class="form-control" id="payment_status">
                            <option value="Unpaid">Unpaid</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Account</label>
                        <select name="account" required class="form-control" id="">
                            @foreach ($accounts as $account)
                                <option value="{{$account->id}}">{{$account->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="payment" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make a Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/claim/amount/payment') }}">
                @csrf
                <input type="hidden" id="paymentID" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Account</label>
                        <select name="account" required class="form-control" id="">
                            @foreach ($accounts as $account)
                                <option value="{{$account->id}}">{{$account->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Pay</button>
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
   function approvalp(id)
   {
        $("#approvalID").val(id);
        $("#approval").modal("show");
   }

   function payment(id)
   {
        $("#paymentID").val(id);
        $("#payment").modal("show");
   }
</script>

@endsection
