@extends('layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Available Stock</h4>
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
                                <th class="border-top-0">Ser</th>
                                <th class="border-top-0">Product Name</th>
                                <th class="border-top-0">Category</th>
                                <th class="border-top-0">Company</th>
                                <th class="border-top-0">Available Stock</th>
                                <th class="border-top-0">Unit Price</th>
                                <th class="border-top-0">Stock Value</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ser = 0;
                            $total = 0;
                            @endphp

                            @foreach ($data as $item)
                            @php
                                $ser += 1;
                                $total += $item['value'];
                            @endphp
                            <tr>
                                <td> {{ $ser }} </td>
                                <td>{{$item['product']}}</td>
                                <td>{{$item['cat']}}</td>
                                <td>{{$item['coy']}}</td>
                                <td>{{$item['balance']}}</td>
                                <td>{{$item['price']}}</td>
                                <td>{{$item['value']}}</td>

                            </tr>
                            @endforeach
                            <tr>
                            <td colspan="6" style="text-align: right;"> <strong>Total</strong> </td>
                            <td> <strong>{{ $total }}</strong> </td>
                        </tr>
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
