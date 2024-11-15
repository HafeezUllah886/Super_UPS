<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super UPS CENTER</title>
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
        <div class="container1">
            <div class="sub-container">
                <div class="logo" style="width: 37%;">
                    <img src="{{ asset('assets/images/app_logo.png') }}" alt="logo">
                </div>
                <div3 id="myDiv">

                    <span class="dot">
                        <p style="margin-top: 15px;">خوشحال خان</p>
                    </span>
                </div3>
                <div class="text1">
                    <h1 class="m-query1">Super UPS Center</h1>
                    <h3 class="m-query2">Shop No 12, insaf Solar Market, Angle Road, opp Civic Center, Quetta.
                        <br>Phone:&nbsp; 0300-3883054,&nbsp;0309-8105556,&nbsp;081-2827774</h3>

                </div>
            </div>
        </div>



        <div class="body-section">
            <div class="row">
                <div class="qoute">
                    <h2 style="text-align: center;">QUOTATION</h2>
                </div>
            </div>
            <div class="row" style="margin-top:20px;">
                <div class="col-6">
                   <table class="table" style="width: 60%">
                    <tr>
                        <td  style="text-align: left; width:20%; padding-left:10px;"> <strong>To:</strong> </td>
                        <td  style="text-align: left ">{{$quot->customer_account->title ?? $quot->walkIn." (Walk-in)"}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td  style="text-align: left ">{{$quot->customer_account->address?? $quot->address }}</td>
                    </tr>
                    <tr>
                        <td > </td>
                        <td  style="text-align: left ">{{$quot->customer_account->phone ?? $quot->phone}}</td>
                    </tr>
                   </table>
                </div>
                <div class="col-6">
                    <table class="table float-right" style="width: 60%;">
                        <tr>
                            <td  style="text-align: left; width:30%; padding-left:10px;"> <strong>Ref #</strong> </td>
                            <td  style="text-align: left ">{{$quot->ref}}</td>
                        </tr>
                        <tr>
                            <td  style="text-align: left; width:20%; padding-left:10px;"> <strong>Date</strong> </td>
                            <td  style="text-align: left ">{{ date("d M Y", strtotime($quot->date)) }}</td>
                        </tr>

                       </table>

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
                        <th class="w-10">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ser = 0;
                        $total = 0;
                        $amount = 0;
                    @endphp
                    @foreach ($quot->details as $details)
                    @php
                        $ser += 1;
                        $amount = $details->qty * $details->price;
                        $total += $amount;
                    @endphp
                    <tr>
                        <td>{{ $ser }}</td>
                        <td>{{ $details->product1->category->cat }}</td>
                        <td>{{ $details->product1->name }}</td>
                        <td>{{ $details->price }}</td>
                        <td>{{ $details->qty }}</td>
                        <td>{{ $amount }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Total</strong>
                        </td>
                        <td>
                            <strong>{{ $total }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Discount</strong>
                        </td>
                        <td>
                            <strong>{{ $quot->discount }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Net Total</strong>
                        </td>
                        <td>
                            <strong>{{ $total - $quot->discount }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <br>
            <strong>Note: </strong>
            This quotation is valid till <strong>{{ date("d M Y", strtotime($quot->validTill)) }}</strong>
            <br><br>
            <br><br>
            <br><br>
            <h4 class="">Authorize Signature ___________________</h4>
            <p style="text-align:right;margin-right:2px;">superupscenter@gmail.com</p>
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
        window.location.href = "{{ url('/quotation')}}";
    }, 5000);
</script>
