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
                <h4>Accounts Listing</h4>
                <div class="d-flex justify-content-end">
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">Add Account</button>
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
                                <th class="border-top-0">Ser</th>
                                <th class="border-top-0">Title</th>
                                <th class="border-top-0">Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $ser = 0;
                            @endphp
                            @foreach ($accounts as $account)
                            @php
                            $ser += 1;
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td>{{ $account->title }}</td>
                                <td> {{ getAccountBalance($account->id) }}</td>
                                <td class="text-left">
                                    <button onclick='edit_cat({{ $account->id }}, "{{ $account->title }}"' class="btn btn-primary">Edit</button>
                                    <a href="{{ url('accounts/statement/') }}/{{ $account->id }}" class="btn btn-info">View Statement</button>
                                    @if(getAccountBalance($account->id) == 0)
                                    <a href="{{ url('/account/delete/') }}/{{ $account->id }}" class="btn btn-danger confirmation">Delete</a>
                                    @endif

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
                <h5 class="modal-title">Add New Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="title">Account Title</label>
                        <input type="text" required name="title" id="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="amount">Initial Amount</label>
                        <input type="number" required min="0" name="amount" value="0" id="amount" class="form-control">
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

{{-- Model Starts Here --}}
<div class="modal" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

                <div class="modal-body">
                    <form action="{{ url('/product/edit') }}" method="post">
                        @csrf
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" required id="edit_name"  name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cat">Select Category</label>
                        <select name="cat" class="form-control" id="edit_cat">

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="coy">Select Company</label>
                        <select name="coy" class="form-control" id="edit_coy">

                        </select>
                    </div>

                </div>
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Save</button>
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

    function edit_cat(id, name, coy, cat) {
        $('#edit_name').val(name);
        $('#edit_coy').val(coy);
        $('#edit_cat').val(cat);
        $('#edit_id').val(id);
        $('#edit').modal('show');
    }


    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    /* function save_edit(){
        var id = $('#edit_id').val();
        var cat = $('#edit_cat').val();

        $.ajax({
            'method': 'get',
            'url': '{{ url("/category/edit/") }}/'+id+'/'+cat,
            'success' : function(data){

            }
        });
    } */
</script>
@endsection
