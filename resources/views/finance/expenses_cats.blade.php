@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.Expense') }}</h4>
                <div>
                    <a href="{{ url('/expense') }}" class="btn btn-info" >Expenses</a>
                    <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
                </div>

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
                                <th class="border-top-0">{{ __('lang.Ref') }}</th>
                                <th class="border-top-0 urdu">Name</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($cats as $key => $cat)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td> {{ $cat->exp_cat }}</td>
                                <td>
                                    <button class="btn btn-success" data-toggle="modal" data-target="#edit_{{$cat->id}}">Edit</button>
                                </td>
                            </tr>

                            {{-- Model Starts Here --}}
                            <div class="modal" id="edit_{{$cat->id}}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('expense_cat.update', $cat->id)}}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="cat">Name</label>
                                                <input type="text" value="{{$cat->exp_cat}}" required name="exp_cat" id="cat" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">{{ __('lang.Update') }}</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
                <h5 class="modal-title">Create Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('expense_cat.store')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cat">Name</label>
                       <input type="text" required name="exp_cat" id="cat" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('lang.Create') }}</button>
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

</script>
@endsection
