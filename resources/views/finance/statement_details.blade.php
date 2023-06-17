<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Previous Balance</h5>
                <h4>{{ $p_balance }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Current Balance</h5>
                <h4>{{ getAccountBalance($id) }}</h4>
            </div>
        </div>
    </div>
</div>
<div class="card-body">

    <div class="table-responsive" >
        <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
            <thead>
                <th>Reference</th>
                <th>Date</th>
                <th>Description</th>
                <th class="text-end">Cradit +</th>
                <th class="text-end">Debit -</th>
                <th class="text-end">Balance</th>
            </thead>
            <tbody >
                @php
                    $total_cr = 0;
                    $total_db = 0;
                    $balance = $p_balance;
                @endphp
                @foreach ($items as $item)
                @php
                    $total_cr += $item->cr;
                    $total_db += $item->db;
                    $balance -= $item->db;
                    $balance += $item->cr;

                @endphp
                <tr>
                <td>{{ $item->ref }}</td>
                <td>{{ date("d M Y",strtotime($item->date)) }}</td>
                <td>{!! $item->desc !!}</td>
                <td class="text-end">{{ $item->cr == null ? '-' : round($item->cr,2)}}</td>
                <td class="text-end">{{ $item->db == null ? '-' : round($item->db,2)}}</td>
                <td class="text-end">{{ round($balance,2) }}</td>

                </tr>
            @endforeach

        </tbody>
        </table>

    </div>
</div>

<script>
      $('#datatable1').DataTable({
        "bSort": true,
        "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        "order": [[0, 'desc']],

    });

</script>
