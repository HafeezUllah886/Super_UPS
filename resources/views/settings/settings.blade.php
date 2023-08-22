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
                <h4>Settings</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Profile Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/settings/profile/update')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="userName">{{ __('lang.UserName') }}</label>
                                <input type="text" class="form-control" value="{{auth()->user()->name}}" name="userName" id="">
                            </div>
                            <div class="form-group">
                                <label for="userName">Email</label>
                                <input type="email" class="form-control" value="{{auth()->user()->email}}" name="email" id="">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/settings/password/update')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="userName">Current Password</label>
                                <input type="password" class="form-control" autocomplete="new-password"  name="cPassword" id="">
                            </div>
                            <div class="form-group">
                                <label for="userName">New Password</label>
                                <input type="password" class="form-control" autocomplete="new-password"  name="nPassword" id="">
                            </div>
                            <div class="form-group">
                                <label for="userName">Confirm Password</label>
                                <input type="password" class="form-control" autocomplete="new-password"  name="rPassword" id="">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Language Settings</h5>

                    </div>
                    <div class="card-body">
                        <form action="{{url('/settings/language/update')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="userName">Select Language</label>
                                <select name="lang" class="form-control">
                                    <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : ''}}>English</option>
                                    <option value="ur" {{ session()->get('locale') == 'ur' ? 'selected' : ''}}>اردو</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
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
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });

</script>
@endsection
