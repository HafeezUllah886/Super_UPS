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
            background-color: #025771;
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
            border-left: 2px solid #025771;
            border-right: 2px solid #025771;

        }

        .body-section1 {
            background-color: #025771;
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
            border: 2px solid #025771;
            color: #ffffff;
            height: 90px;
            border-radius: 6px;




        }

        .sub-container {
            background-color: #025771;
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
            background-color: #025771;
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
            background-color: #025771;
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
        <img style="margin:0;width:100%;"src="{{asset('assets/images/header.png')}}" alt="">

        <div class="body-section">
            <div class="row">
                <div class="qoute">
                    <h2 style="text-align: center;">Accounts Detail</h2>
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
                        <th class="w-15">Title</th>
                        <th class="w-15">Type</th>
                        <th class="w-15">Address</th>
                        <th class="w-15">Phone</th>
                        <th class="w-10">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($accounts as $key => $account)
                    @php
                        $balance = getAccountBalance($account->id);
                        $total += $balance;
                    @endphp
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $account->title }}</td>
                        <td>{{ $account->type }}</td>
                        <td>{{ $account->address }}</td>
                        <td>{{ $account->phone }}</td>
                        <td>{{ $balance }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-right">
                            <strong>Total</strong>
                        </td>
                        <td>
                            <strong>{{ $total }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
<script>
    window.print();
    setTimeout(function() {
        window.location.href = "{{ url('/dashboard')}}";
    }, 5000);
</script>
