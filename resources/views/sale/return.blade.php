@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Sale Returns</h4>
                <a href="{{url('/sale')}}" class="btn btn-success">Create New</a>
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
                                <th class="border-top-0">Invoice #</th>
                                <th class="border-top-0">Customer</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">PaidBy</th>
                                <th class="border-top-0">Amount paid</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($saleReturns as $return)
                            <tr>
                                <td> {{ $return->bill_no}} </td>
                                <td>@if (@$return->bill->customer_account->title)
                                    {{ @$return->bill->customer_account->title }}

                                @else
                                {{$return->bill->walking}} (Walk In)

                                @endif</td>
                                <td>{{ $return->date }}</td>
                                <td>{{ $return->amount }}</td>

                              
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
