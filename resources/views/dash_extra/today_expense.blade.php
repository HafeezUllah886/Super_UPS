@extends('layout.dashboard')

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Today Expenses</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">New Expense</button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">Reference</th>
                                <th class="border-top-0">Account</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Description</th>
                                <th class="border-top-0">Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($expenses as $dep)
                            <tr>
                                <td> {{ $dep->ref }} </td>
                                <td>{{ $dep->account->title }} ({{ $dep->account->Category }})</td>
                                <td>{{ $dep->date }}</td>
                                <td>{{ $dep->desc}}</td>
                                <td>{{ $dep->amount}}</td>
                                <td>
                                    <a href="{{ url('expense/delete/') }}/{{ $dep->ref }}" class="btn btn-danger">Delete</a>
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
                <h5 class="modal-title">Create Expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('/expense') }}">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="account">Select Account</label>
                        <select name="account" id="account" class="select2" required id="">
                            <option value=""></option>
                            @foreach ($accounts as $account)
                               <option value="{{ $account->id }}">{{ $account->title }} ({{ $account->Category }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cat">Amount</label>
                       <input type="number" required name="amount" id="amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                       <input type="datetime-local" name="date" id="date" value="{{ now() }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea name="desc" required id="desc" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });

</script>
@endsection