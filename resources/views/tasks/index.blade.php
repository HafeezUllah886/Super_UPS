@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
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
                <h4>Tasks</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
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
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">Description</th>
                                <th class="border-top-0">Status</th>
                                <th class="border-top-0">Due Date</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $key => $task)
                            @php
                                $color = null;
                                if($task->status == "Pending" && $task->due < now())
                                {
                                    $color = "bg-danger";
                                }
                            @endphp
                            <tr class="{{$color}}">
                                <td> {{ $key+1 }} </td>
                                <td>{{ $task->description }}</td>
                                <td>{{ $task->status }}</td>
                                <td>{{ date('d M Y', strtotime($task->due)) }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <div class="btn-group" role="group">
                                          <button id="btnGroupDrop1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            @if($task->status == 'Pending')
                                                <a class="dropdown-item text-success" href="{{url('/task/mark/')}}/{{$task->id}}" >Mark Completed</a>
                                            @endif
                                            <a class="dropdown-item" href="#" onclick="edit_task({{ $task->id }}, '{{ $task->description }}', '{{$task->due}}')">Edit</a>
                                            <a class="dropdown-item text-danger" href="{{ url('/task/delete/') }}/{{ $task->id }}">Delete</a>
                                          </div>
                                        </div>
                                    </div>
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
                <h5 class="modal-title">Create Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('/task/store')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="desc">Description</label>
                        <textarea required name="description" id="desc" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="due">Due Date</label>
                        <input type="date" required name="due" id="due" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

                <div class="modal-body">
                    <form action="{{ url('/task/update') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea required name="description" id="edit_desc" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="due">Due Date</label>
                                <input type="date" required name="due" id="edit_due" class="form-control">
                            </div>
                        </div>

                </div>
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">{{ __('lang.Save') }}</button>
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
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });

    function edit_task(id, desctiption, due) {
        $('#edit_desc').val(desctiption);
        $('#edit_due').val(due);
        $('#edit_id').val(id);
        $('#edit').modal('show');
    }

</script>
@endsection
