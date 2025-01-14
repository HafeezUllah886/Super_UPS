@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Targets</h4>
                <a href="{{ route('target.create')}}" class="btn btn-success">{{ __('lang.CreateNew') }}</a>
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
                                <thead>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Achieved</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($targets as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->customer->title }}</td>
                                <td>{{ $item->totalPer }}%</td>
                                <td>{{ date('d M Y', strtotime($item->startDate)) }} <br>{{ date('d M Y', strtotime($item->endDate)) }}</td>
                                <td>
                                    <span class="badge bg-{{$item->campain_color}}">{{$item->campain}}</span>
                                    <br>
                                    <span class="badge bg-{{$item->goal_color}}">{{$item->goal}}</span>
                                </td>
                                <td>
                                   <a href="{{route('targets.delete', $item->id)}}" class="btn btn-danger">Delete</a>
                                   <a href="{{route('target.show', $item->id)}}" class="btn btn-success">View</a>
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
