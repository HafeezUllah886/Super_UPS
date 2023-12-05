<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    <style>


        body {
            -webkit-print-color-adjust: exact;
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
        }

        .brand-section {
            background-color: #730c0c;
            padding: 10px 40px;
        }

        .logo {
            width: 50%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            width: 50%;
            flex: 0 0 auto;
        }

        .text-white {
            color: #fff;
        }

        .company-details {
            float: right;
            text-align: right;
        }

        .body-section {
            padding: 16px;
            border-left: 2px solid #730c0c;
            border-right: 2px solid #730c0c;

        }

        .body-section1 {
            background-color: #730c0c;
            color: white;
            border-radius: 4px;
        }

        .heading {
            font-size: 20px;
            margin-bottom: 08px;
        }

        .sub-heading {
            color: #262626;
            margin-bottom: 05px;
        }

        table {
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            border: 1px solid #111;
            background-color: #f2f2f2;
        }

        table td {
            vertical-align: middle !important;
            text-align: center;
        }

        table th,
        table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }

        .table-bordered {
            box-shadow: 0px 0px 5px 0.5px gray;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .text-right {
            text-align: end;
            padding-right: 3px;
            ;
        }

        .w-20 {
            width: 10%;
        }

        .w-15 {
            width: 22%;
        }

        .w-5 {
            width: 5%;
        }

        .w-10 {
            width: 18%;
        }

        .float-right {
            float: right;
        }

        .container1 {
            border: 2px solid #730c0c;
            color: #ffffff;
            height: 120px;
            border-radius: 6px;
        }

        .sub-container {
            background-color: #730c0c;
            ;
            margin: 5px;
            padding-bottom: 2px;
            display: flex;
            height: 108px;
            border-radius: 6px;


        }

        .m-query1 {
            font-size: 22px;
        }

        .m-query2 {
            font-size: 18px;
        }

        img {
            margin-top: -36px;
            padding: 2px;
            width: 92%;
            height: 148px;
            margin-left: 2px;

        }

        .text1 {
            text-align: center;
            width: 70%;
            padding-top: 11px;
        }

        .qoute {
            width: 21%;
            margin: auto;
            text-align: center;
            background-color: #730c0c;
            color: white;
            border-radius: 5px;
            font-size: 12px;
        }

        @media screen and (max-width: 1014px) {
            .m-query1 {
                margin-top: 6PX;
                font-size: 28px;
            }

            .m-query2 {
                font-size: 11px;
            }
        }

        @media screen and (max-width: 900px) {
            .m-query1 {
                font-size: 24px;
            }

            .m-query2 {
                font-size: 14px;
            }

            img {
                width: 99%;
                height: 171%;
                margin-top: -50px;
                margin-left: 8px;
            }


        }

        .div3 {}

        #myDiv {
            width: 128px;
            font-size: 18px;
            margin-top: 19px;
        }

        .dot {
            height: 60px;
            width: 65px;
            background-color: #730c0c;
            color: white;
            /* color: #025771; */
            border-radius: 50%;
            display: inline-block;
            border: 5px solid white;
            margin: -14px;
            margin-left: 7px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <img style="margin:0;width:100%;"src="{{asset('assets/images/abvc.png')}}" alt="">
        {{-- <div class="container1">
            <div class="sub-container">
                <div class="logo" style="width: 37%;">
                    <img src="{{ asset('assets/images/app_logo.png') }}" alt="logo">
                </div>
                <div id="myDiv">
                    <span class="dot">
                        <p style="margin-top: 15px;">خوشحال خان</p>
                    </span>
                </div>
                <div class="text1">
                    <h1 class="m-query1">Abu Zahir Zafar Machinery</h1>
                    <h1 class="m-query1">ابوظاھر ظفر مشینری</h1>
                    <h3 class="m-query2">دکان نمبر 24 انصاف مارکیٹ ایئگل روڈ کوئٹہ
                        <br>Phone:&nbsp; 0302-3824634,&nbsp;0318-5000145
                    </h3>
                </div>
            </div>
        </div> --}}

        <div class="body-section">
            <div class="row">
                <div class="qoute">
                    <h2 style="text-align: center;">INVOICE# &nbsp; {{ $invoice->id }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- <h2 class="heading">Invoice No.: 001</h2> -->
                    <h3 class="sub-heading">Invoice to:
                        @if (@$invoice->customer_account->title)
                            {{ @$invoice->customer_account->title }} ({{ @$invoice->customer_account->type }})
                        @else
                            {{ $invoice->walking }} (Walk In)

                        @endif
                    </h3>


                </div>
                <div class="col-6">
                    <div class="company-details">
                        <h3 class="text-dark">Date: {{ date('d M Y', strtotime($invoice->date)) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <!-- <h3 class="heading">Ordered Items</h3>
            <br> -->
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="w-5">#</th>
                        <th class="w-15">Category</th>
                        <th class="w-15">Item</th>
                        <th class="w-10">Price</th>
                        <th class="w-10">Quantity</th>
                        <th class="w-10">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $ser = 0;
                        $dollarRate = auth()->user()->doller;
                    @endphp

                    @foreach ($details as $item)
                        @php
                            $ser += 1;
                            $amount = $item->price * $item->qty;
                            $total += $amount;
                        @endphp
                        <tr>
                            <th scope="row">{{ $ser }}</th>
                            <td>{{ $item->product->category->cat }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->price * auth()->user()->doller}}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $amount * $dollarRate }}</td>
                        </tr>

                    @endforeach

                    <tr>
                        <td colspan="5" class="text-right">
                            <strong>Total</strong>
                        </td>
                        <td>
                            <strong>{{ $total * $dollarRate}}</strong>
                        </td>
                    </tr>
                    @if($invoice->discount > 0)
                    <tr>
                        <td colspan="5" class="text-right">
                            <strong>Discount</strong>
                        </td>
                        <td>
                            <strong>{{ $invoice->discount == 0 ? 0 : $invoice->discount}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">
                            <strong>Net Total</strong>
                        </td>
                        <td>
                            <strong>{{ ($total - $invoice->discount) * $dollarRate }}</strong>
                        </td>
                    </tr>
                    @endif

                    @if (@$invoice->customer_account->title)
                    <tr>
                        @php
                        $paidAmount = $invoice->amount;
                        if(!$invoice->paidIn){
                             $paidAmount = 0;
                        }
                        else{

                            if($invoice->amount == 0){
                                $paidAmount = $total - ($invoice->amount + $invoice->discount);
                            }
                        }

                        @endphp
                        <td colspan="5" class="text-right">
                            <strong>Paid Amount</strong>
                        </td>
                        <td>
                            <strong>{{ $paidAmount * $dollarRate }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">
                            <strong>Remaining</strong>
                        </td>
                        <td>
                            <h3> {{ ($total - $paidAmount - $invoice->discount) * $dollarRate}}</h3>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <br>
            <div class="row">
                <div class="col-6">
                    <table style="width:500px;">
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Payment type:</strong> </td>
                            <td style="text-align: left">{{$invoice->account->title ?? "Unpaid"}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Details:</strong> </td>
                            <td style="text-align: left">{{$invoice->desc}}</td>
                        </tr>
                        @if (@$invoice->customer_account->title)
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Previous Balance:</strong> </td>
                            <td style="text-align: left">{{($prev_balance ?? 0) * $dollarRate}}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Current Balance:</strong> </td>
                            <td style="text-align: left">{{ ($total - $paidAmount - $invoice->discount) * $dollarRate }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width:40%;"> <strong>Total Balance:</strong> </td>
                            <td style="text-align: left">{{ $cur_balance * $dollarRate}}</td>
                        </tr>
                        @endif
                    </table>
                </div>
               <div class="col-6" style="margin-top:100px;">
               {{--  <img src="{{asset('assets/images/stamp.jpeg')}}" style="width:200px;margin-left:100px;" alt=""> --}}
                {{-- <h4 class="">Authorize Signature ___________________</h4> --}}
               </div>


            </div>


            <br><br>

           {{--  <p style="text-align:right;margin-right:2px;">superupscenter@gmail.com</p> --}}
            <br>
        </div>

        <div class="body-section body-section1">
            <p style="text-align: center;">Thank You For Your Business
            </p>
        </div>
    </div>
    <div style="text-align: right">
        <div class="mt-2" style="font-size: 10px">Powered by Diamond Software 03202565919</p>
        </div>
</body>

</html>
<script>
    window.print();

        setTimeout(function() {
        window.location.href = "{{ url('/sale/history')}}";
    }, 5000);

</script>
