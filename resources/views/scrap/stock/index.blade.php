@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Scrap Stock Details</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="myTable">
                        <thead class="th-color">
                            <tr>
                                <th>Ref#</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = 0;
                            @endphp
                            @foreach ($stocks as $stock)
                            @php
                                $balance += $stock->cr;
                                $balance -= $stock->db;
                                $amount = 0;
                                if($stock->cr)
                                {
                                    $amount = $stock->cr * $stock->rate;
                                }
                                if($stock->db)
                                {
                                    $amount = $stock->db * $stock->rate;
                                }
                            @endphp
                            <tr>
                                <td>{{ $stock->ref}} </td>
                                <td>{{ $stock->date }}</td>
                                <td>{{ $stock->desc }}</td>
                                <td>{{ $stock->cr ? $stock->cr. "Kg" : "-"}}</td>
                                <td>{{ $stock->db ? $stock->db. "Kg" : "-"}}</td>
                                <td>{{ $stock->rate }}</td>
                                <td>{{ $amount }}</td>
                                <td>{{ $balance }}Kg</td>

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
 $('#myTable').DataTable({

"bSort": true,
"order": [[0, "desc"]]
});
</script>
@endsection
