@extends('layout.dashboard')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ $stocks[0]->product->name }}</h4>
                {{-- <button id="download" class="btn btn-success">PDF</button> --}}
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from">{{ __('lang.FromDate') }}</label>
                            <input type="date" name="from" id="from" value="{{ $from }}" onchange="abc()" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to">{{ __('lang.ToDate') }}</label>
                            <input type="date" name="to" id="to" value="{{ $to }}" onchange="abc()" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        Price: <input type="number" oninput="checkValue()" class="form-control" id="price">
        <div class="card bg-white m-b-30" id="items">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ __('lang.PreviousBalance') }}</h5>
                            <h4>{{ numberFormat($prev_bal) }}</h4>
                            <h4> Value in PKR: <span id="prev_balance"></span></h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ __('lang.CurrentBalance') }}</h5>
                            <h4>{{ numberFormat($cur_bal) }}</h4>
                            <h4> Value in PKR: <span id="curr_balance"></span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive" >
                    <table class="table table-bordered table-striped table-hover text-center mb-0 display" id="datatable1">
                        <thead>
                            <th>{{ __('lang.Ref') }}</th>
                            <th>{{ __('lang.Date') }}</th>
                            <th>{{ __('lang.Desc') }}</th>
                            <th class="text-end">{{ __('lang.Credit') }}</th>
                            <th class="text-end">{{ __('lang.Debit') }}</th>
                            <th class="text-end">{{ __('lang.Balance') }}</th>
                        </thead>
                        <tbody >
                            @php
                                $total_cr = 0;
                                $total_db = 0;
                                $balance = $prev_bal;
                            @endphp
                            @foreach ($stocks as $item)
                            @php
                                $total_cr += $item->cr;
                                $total_db += $item->db;
                                $balance -= $item->db;
                                $balance += $item->cr;

                            @endphp
                            <tr>
                            <td>{{ $item->ref }}</td>
                            <td>{{ $item->date }}</td>
                            <td>{!! $item->desc !!}</td>
                            <td class="text-end">{{ $item->cr == null ? '-' : numberFormat($item->cr)}}</td>
                            <td class="text-end">{{ $item->db == null ? '-' : numberFormat($item->db)}}</td>
                            <td class="text-end">{{ numberFormat($balance) }}</td>

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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .dataTables_paginate {
        display: block
    }

</style>
<script>

    $('#datatable1').DataTable({
        "bSort": true,
        "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        "order": [[0, 'desc']],
    });

    function abc(){
        var from = $('#from').val();
        var to = $('#to').val();

    window.open("{{ url('/stock/details/') }}/"+{{ $stocks[0]->product_id }}+"/"+from+"/"+to, '_self');
    }
    function checkValue()
    {
        var prev = "{{$prev_bal}}";
        var curr = "{{$cur_bal}}";
        var sym = "{{ $stocks[0]->product->sym }}";
        var price = $("#price").val();

       var curr_bal = 0;
       var prev_bal = 0;

       if(sym == '*')
        {
            prev_bal = prev * price;
            curr_bal = curr * price;
        }
        if(sym == '/')
        {
            prev_bal = prev / price;
            curr_bal = curr / price;
        }

        $("#curr_balance").html(parseFloat(curr_bal).toLocaleString());
        $("#prev_balance").html(parseFloat(prev_bal).toLocaleString());
    }

</script>
@endsection