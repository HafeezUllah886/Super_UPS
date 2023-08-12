@extends('layout.dashboard')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Customer Dues</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <table class="table" id="datatable1">
                <thead>
                    <th>Id</th>
                    <th>Date</th>
                    <th>Account</th>

                    <th>Description</th>
                    <th>Details</th>
                    <th>Credit</th>
                    <th>Debit</th>
                    <th>Balance</th>
                </thead>
                <tbody>
                    @php
                        $bal = 0;
                    @endphp
                    @foreach ($transactions as $trans)
                    @php
                        $bal += $trans->cr - $trans->db;
                    @endphp
                        <tr>
                            <td>{{ $trans->id }}</td>
                            <td>{{ $trans->date }}</td>
                            <td>{{ $trans->account->title }}</td>
                            <td>{!! $trans->desc !!}</td>
                            <td>
                                @if ($trans->type == 'Sale')
                                @php
                                    $data = \App\Models\sale_details::with('product')->where('ref', $trans->ref)->get();
                                    $subTotal = 0;
                                @endphp
                                <table class="table">
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                    @foreach ($data as $data1)
                                    @php
                                        $subTotal = $data1->qty * $data1->price;
                                    @endphp
                                        <tr>
                                            <td>{{$data1->product->name}}</td>
                                            <td>{{$data1->qty}}</td>
                                            <td>{{round($data1->price,0)}}</td>
                                            <td>{{$subTotal}}</td>
                                        </tr>
                                    @endforeach

                                </table>
                                <strong>Discount: </strong>{{$data[0]->bill->discount ?? '0'}}
                            @endif
                            @if ($trans->type == 'Purchase')
                            @php
                                $data = \App\Models\purchase_details::with('product')->where('ref', $trans->ref)->get();
                                $subTotal = 0;
                            @endphp
                            <table class="table">
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Amount</th>
                                @foreach ($data as $data1)
                                @php
                                    $subTotal = $data1->qty * $data1->rate;
                                @endphp
                                    <tr>
                                        <td>{{$data1->product->name}}</td>
                                        <td>{{$data1->qty}}</td>
                                        <td>{{round($data1->rate,2)}}</td>
                                        <td>{{$subTotal}}</td>
                                    </tr>
                                @endforeach

                            </table>
                            @endif
                            @if ($trans->type == 'Sale Return')
                            @php
                            $data = \App\Models\saleReturnDetails::with('product')->where('ref', $trans->ref)->get();
                            $subTotal = 0;
                            @endphp
                            <table class="table">
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Amount</th>
                                @foreach ($data as $data1)
                                @php
                                $subTotal = $data1->qty * $data1->price;
                                @endphp
                                <tr>
                                    <td>{{$data1->product->name}}</td>
                                    <td>{{$data1->qty}}</td>
                                    <td>{{round($data1->price,2)}}</td>
                                    <td>{{$subTotal}}</td>
                                </tr>
                                @endforeach

                            </table>
                        @endif
                            </td>
                            <td>{{ $trans->cr }}</td>
                            <td>{{ $trans->db }}</td>
                            <td>{{ $bal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .dataTables_paginate {
        display: block
    }

</style>
<script>
    $(document).ready(function() {

    });
    $('#datatable1').DataTable({
        "bSort": true,
        "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        "order": [[0, 'desc']],
    });

</script>
@endsection
