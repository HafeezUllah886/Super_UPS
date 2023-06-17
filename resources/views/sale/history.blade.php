@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Sale History</h4>
                <a href="{{url('/sale')}}" class="btn btn-success">New Sale</a>
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
                                <th class="border-top-0">Bill No.</th>
                                <th class="border-top-0">Customer</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Total Amount</th>
                                <th class="border-top-0">Amount Paid</th>
                                <th class="border-top-0">Payment</th>
                                <th class="border-top-0">Paid From</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($history as $bill)
                            <tr>
                                <td> {{ $bill->id }} </td>
                                <td>@if (@$bill->customer_account->title)
                                    {{ @$bill->customer_account->title }} ({{  @$bill->customer_account->type }})

                                @else
                                {{$bill->walking}} (Walk In)

                                @endif</td>
                                <td>{{ date('d M Y', strtotime($bill->date))}}</td>
                                <td>{{ getSaleBillTotal($bill->id) }}</td>
                                <td>@if($bill->isPaid == 'Yes') {{ "Full Payment" }} @elseif($bill->isPaid == 'No') {{ "UnPaid" }} @else {{ $bill->amount }} @endif</td>
                                <td>{{ $bill->isPaid}}</td>
                                <td>{{ @$bill->account->title}}</td>
                                <td>
                                    <a href="{{ url('/sale/print/') }}/{{ $bill->ref }}" class="btn btn-primary">Print</a>
                                    <a href="{{ url('/sale/delete/') }}/{{ $bill->ref }}" class="btn btn-danger confirmation">Delete</a>
                                    <a href="{{ url('/sale/edit/') }}/{{ $bill->id }}" class="btn btn-info">Edit</a>
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
