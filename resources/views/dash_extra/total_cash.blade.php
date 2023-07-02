@extends('layout.dashboard')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Total Cash</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <table class="table" id="datatable1">
                <thead>
                    <th>Id</th>
                    <th>Account</th>
                    <th>Date</th>
                    <th>Description</th>
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
                            <td>{{ $trans->account->title }}</td>
                            <td>{{ $trans->date }}</td>
                            <td>{!! $trans->desc !!}</td>
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
