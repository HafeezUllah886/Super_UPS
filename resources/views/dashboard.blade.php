@extends('layout.dashboard')

@section('content')
<style>
    td {
        font-size: 15px !important
    }

    table-responsive {
        height: 600px ! important overflow:scroll;
    }
</style>
<div class="card">
    {{-- Top card section --}}
    <div class="card-body">
        <div class="row">
            <div class="col-xl-2 col-md-2">
                <div class="card border-left-info shadow  py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Customer DUES</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            {{ customerDues() }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i style="color: red" class="fa fa-money fa-2x text-red text-red-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2 mb-0">
                <div class="card border-left-info shadow  py-2">
                    <a style="text-decoration:none;" href="">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Sale</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: grey" class="fa fa-list-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="card border-left-info shadow  py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Cash</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ totalCash() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: red" class="fa fa-money fa-2x text-red text-red-300"></i>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <div class="col-xl-2 col-md-2 mb-0">
                <div class="card border-left-info shadow  py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Cash</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ todayCash() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: purple" class="fa fa-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <div class="col-xl-2 col-md-2 mb-0">
                <div class="card border-left-info shadow  py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Bank</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ totalBank() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: blue" class="fa fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <div class="col-xl-2 col-md-2 mb-0">
                <div class="card border-left-info shadow  py-2">

                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today Bank</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ todayBank() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i style="color: green" class="fa fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>

                </div>
            </div>

        </div>
    </div>

    {{-- End Top card section --}}
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
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-6">
                <h5 class="text-danger">Ledger</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center" id="datatable">
                        <thead class="th-color">
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>Date</th>
                                <th>Vendor/Customer</th>
                                <th>Payment Type</th>

                                <th>Details</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="text-danger">Income & Expense Detail</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center" id="datatable">
                        <thead class="th-color">
                            <tr>
                                <th>Date</th>
                                <th>Customer/Vendor</th>
                                <th>Details</th>
                                <th>Payment Type</th>

                                {{-- <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Amount</th> --}}
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Total</th>

                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
@endsection
