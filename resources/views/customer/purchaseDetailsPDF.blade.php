<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insaf Electric Store</title>
    <style>


        body {
            -webkit-print-color-adjust: exact;
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
            width:100%;
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
            background-color: #f36b2c;
            padding: 10px 10px;
        }

        .logo {
            width: 20%;
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
            border-left: 2px solid #f36b2c;
            border-right: 2px solid #f36b2c;

        }

        .body-section1 {
            background-color: #f36b2c;
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
            border: 2px solid rgb(184, 0, 0);
            color: #ffffff;
            height: 90px;
            border-radius: 6px;
        }

        .sub-container {
            background-color: #f36b2c;
            ;
            margin: 5px;
            padding-bottom: 2px;
            display: flex;
            height: 78px;
            border-radius: 6px;
        }

        .m-query1 {
            font-size: 22px;
        }

        .m-query2 {
            font-size: 11px;
        }

        img {
            margin-top: -36px;
            padding: 2px;
            width: 100%;
            height: 148px;
            margin-left: 2px;
            float: left;
        }

        .text1 {
            text-align: center;
            width: 70%;
            padding-top: 11px;
            float: right;
        }

        .qoute {
            width: 40%;
            margin: auto;
            text-align: center;
            background-color: #f36b2c;
            color: white;
            border-radius: 5px;
            font-size: 12px;
            padding: 10px;
            line-height: 10px;
        }

        /* @media screen and (max-width: 1014px) {
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


        } */

        .div3 {}

        #myDiv {
            width: 128px;
            font-size: 18px;
            margin-top: 19px;


        }

        .dot {

            height: 60px;
            width: 65px;
            background-color: #f36b2c;
            color: white;
            /* color: #f36b2c; */
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
        <div class="container1">
            <div class="sub-container">
                <div class="logo" style="width: 37%;">
                    <img src="{{ asset('assets/images/app_logo.png') }}" alt="logo">
                </div>
                <div class="text1">
                    <h1 class="m-query1">Insaf Electric Store & Fancy Light House</h1>
                    <h3 class="m-query2">Suraj Gunj Bazar Quetta
                        <br>Phone:&nbsp; 0312-8386775,&nbsp;081-2838146
                    </h3>
                </div>
            </div>
        </div>
        <div class="body-section">
            <div class="row">
                <div class="qoute">
                    <h2 style="text-align: center;">PURCHASE DETAILS</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table style="margin-top:10px;">
                        <tr>
                            <td style="text-align: left; width:20%;">Customer: </td>
                            <td style="text-align: left; width:30%;">{{ $invoices[0]->customer_account->title }}</td>
                            <td style="text-align: left;">Date: </td>
                            <td style="text-align: left;">{{ date('d M Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="body-section">
            <!-- <h3 class="heading">Ordered Items</h3>
            <br> -->
            <table class="table-bordered">
                <thead class="th-color">
                    <tr>
                        <th class="border-top-0">Ser</th>
                        <th class="border-top-0">Date</th>
                        <th class="border-top-0">Product</th>
                        <th class="border-top-0">Watt</th>
                        <th class="border-top-0">Price</th>
                        <th class="border-top-0">Quantity</th>
                        <th class="border-top-0">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $ser = 0;
                    $amount = 0;
                    @endphp
                    @foreach ($invoices as $item)
                        @foreach ($item->details as $product)
                        @php
                        $ser += 1;
                        $amount = $product->price * $product->qty;
                        @endphp
                        <tr>
                            <td> {{ $ser }} </td>
                            <td>{{ date("d M Y", strtotime($item->date)) }}</td>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->product->watt }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->qty }}</td>
                            <td>{{ $amount }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>


        </div>

        <div class="body-section body-section1">

        </div>
    </div>

</body>

</html>
