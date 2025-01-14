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
            background-color: #b80000;
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
            border-left: 2px solid #b80000;
            border-right: 2px solid #b80000;

        }

        .body-section1 {
            background-color: #b80000;
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
            background-color: #b80000;
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
            background-color: #b80000;
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
            background-color: #b80000;
            color: white;
            /* color: #b80000; */
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
        <img style="margin:0;width:100%;" src="{{ asset('assets/images/bill.jpeg') }}" alt="">
        <div class="body-section">
            <div class="row">
                <div class="qoute">
                    <h2 style="text-align: center;">Target # &nbsp; {{ $target->id }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- <h2 class="heading">Invoice No.: 001</h2> -->
                    <h3 class="sub-heading">Target For:
                        {{$target->customer->title}}
                    </h3>
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <h3 class="text-dark">Start Date: {{ date('d M Y', strtotime($target->startDate)) }}</h3>
                        <h3 class="text-dark">End Date: {{ date('d M Y', strtotime($target->endDate)) }}</h3>
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
                        <th class="w-15">Product</th>
                        <th class="w-15">Target Qty</th>
                        <th class="w-10">Achieved Qty</th>
                        <th class="w-10">Percent</th>
                    </tr>
                </thead>
                    <tbody id="products-list">
                        @foreach ($target->details as $key => $product)
                            <tr class="border-1 border-dark">
                             <td class="m-1 p-1 border-1 border-dark">{{$key+1}}</td>
                             <td class="text-start m-1 p-1 border-1 border-dark">{{$product->product->name}}</td>
                             <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->qty)}} </td>
                             <td class="text-end m-1 p-1 border-1 border-dark">{{number_format($product->sold)}}</td>
                             <td class="text-end m-1 p-1 border-1 border-dark">{{$product->per}} %</td>
                            </tr>
                        @endforeach
                     </tbody>
                     <tfoot>
                         <tr>
                             <th colspan="2" class="text-end">Total</th>
                             <th class="text-end">{{number_format($target->details->sum('qty'))}}</th>
                             <th class="text-end">{{number_format($target->details->sum('sold'))}}</th>
                             <th class="text-end">{{number_format($target->totalPer)}}%</th>
                         </tr>
                     </tfoot>
            </table>


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
