@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="col-md-6">
                    <h4>Stock Alert</h4>
                </div>
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
                                <th class="border-top-0">{{__('lang.Product')}}</th>
                                <th class="border-top-0">{{__('lang.Category')}}</th>
                                <th class="border-top-0">{{__('lang.Company')}}</th>
                                <th class="border-top-0">Stock Alert</th>
                                <th class="border-top-0">Available Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr>
                                <td> {{ $product->name }} </td>
                                <td> {{ $product->category->cat }} </td>
                                <td> {{ $product->company->name }} </td>
                                <td> {{ $product->alert }} </td>
                                <td> {{ $product->availStock }} </td>
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
        "order": [[4, "desc"]]
    });

</script>

@endsection
