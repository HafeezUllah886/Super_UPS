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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from">{{ __('lang.FromDate') }}</label>
                                    <input type="date" name="from" id="from" value="{{$from}}" onchange="update()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to">{{ __('lang.ToDate') }}</label>
                                    <input type="date" name="to" id="to" value="{{$to}}" onchange="update()" class="form-control">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @php
                $total = 0;
            @endphp
            @foreach ($cats as $cat)
            @php
                $exp = catExp($cat->id, $from, $to);
                $total += $exp;
            @endphp
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">{{$cat->exp_cat}}</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ $exp }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-usd fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            @endforeach
            <div class="col-xl-3 col-md-3 mt-3">
                <div class="card border-left-info shadow  py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Expense</div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="info_label h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                {{ $total }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-usd fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
@section('scripts')
<script>
    function update()
    {
        var start = $("#from").val();
        var end = $("#to").val();
        window.open("{{ url('/expense/dashboard/') }}/"+start+"/"+end, "_self");
    }
  </script>
@endsection
