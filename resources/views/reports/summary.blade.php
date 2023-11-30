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
                    <h4>Summary Report</h4>
                </div>
                <div class="col-md-6">
                    <table>
                        <tr>
                            <td>From: </td>
                            <td> <input type="date" class="form-control" value="{{ $from }}" id="from"> </td>
                            <td> &nbsp; - &nbsp; </td>
                            <td> To: </td>
                            <td> <input type="date" class="form-control" value="{{ $to }}" id="to"> </td>
                            <td> &nbsp;<button class="btn btn-info" id="btn">Filter</button> </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-white">
            <div class="card-body">
                <table class="w-100">
                    <tr>
                        <td>Purchases</td>
                        <td>{{ $purchases }}</td>
                    </tr>
                    <tr>
                        <td>Sales</td>
                        <td>{{ $sales }}</td>
                    </tr>
                    <tr>
                        <td>Sale Returns</td>
                        <td>{{ $sale_returns }}</td>
                    </tr>
                    <tr>
                        <td>Claims</td>
                        <td>{{ $claims }}</td>
                    </tr>
                    <tr>
                        <td>Scrap Purchases</td>
                        <td>{{ $scrap_purchase }}</td>
                    </tr>
                    <tr>
                        <td>Scrap Sales</td>
                        <td>{{ $scrap_sold }}</td>
                    </tr>
                    <tr>
                        <td>Expenses</td>
                        <td>{{ $expenses }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script>
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure to delete account?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

    $("#btn").click(function (){
        var from = $("#from").val();
        var to = $("#to").val();

        window.open("{{ url('/report/summary/') }}/"+from+"/"+to, '_self');
    });
</script>


@endsection
