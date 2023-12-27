@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Claim</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">Create</button>
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
                                <th class="border-top-0">#</th>
                                <th class="border-top-0">Customer</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Return Pro</th>
                                <th class="border-top-0">Qty</th>
                                <th class="border-top-0">Issue Pro</th>
                                <th class="border-top-0">Qty</th>
                                <th class="border-top-0">Vendor</th>
                                <th class="border-top-0">Amount</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($claims as $key => $claim )
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $claim->customer }}</td>
                                    <td>{{ $claim->date }}</td>
                                    <td>{{ $claim->returnProduct->name }}</td>
                                    <td>{{ $claim->return_qty }}</td>
                                    <td>{{ $claim->issueProduct->name }}</td>
                                    <td>{{ $claim->issue_qty }}</td>
                                    <td>{{ $claim->vendorDetails->title }}</td>
                                    <td>{{ $claim->amount }}</td>
                                    <td><a href="{{ url('/claim/delete/') }}/{{ $claim->ref }}" class="btn btn-danger">Delete</a></td>
                                </tr>
                                @if($claim->notes != '')
                                <tr>
                                    <td colspan="10">Notes: {{ $claim->notes }}</td>
                                </tr>
                                @endif

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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Claim</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/claim/store') }}">
                @csrf
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label for="customer">Customer</label>
                        <input type="text" required name="customer" id="customer" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date">Date</label>
                        <input type="date" required name="date" value="{{ date("Y-m-d") }}" id="date" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="returnPro">Returned Product</label>
                        <select name="returnPro" id="returnPro" class="form-control">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="returnQty">Returned Qty</label>
                        <input type="number" name="returnQty" id="returnQty" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="issuePro">Issue Product</label>
                        <select name="issuePro" id="issuePro" class="form-control">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="issueQty">Issue Qty</label>
                        <input type="number" name="issueQty" id="issueQty" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="vendor">Vendor</label>
                        <select name="vendor" id="vendor" class="form-control">
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="amount">Deduction from Vendor</label>
                        <input type="number" name="amount" placeholder="Enter Amount" id="amount" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" cols="30" rows="3" class="form-control"></textarea>
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
</script>

@endsection
