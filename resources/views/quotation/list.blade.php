@php
    $ser = 0;
    $amount = 0;
    $total = 0;
@endphp
@foreach ($items as $item)
@php
    $ser += 1;
    $amount = $item->qty * $item->price;
    $total += $amount;
@endphp
<tr>
    <td>{{ $ser }}</td>
    <td>{{ $item->product1->name }}</td>
    <td>{{ $item->qty }}</td>
    <td>{{ round($item->price,0) }}</td>
    <td>{{ $amount }}</td>
    <td><button class="btn btn-danger" onclick="deleteList({{ $item->id }}, {{ $item->quot }})">Delete</button></td>
</tr>
@endforeach
<tr>
    <td colspan="4" style="text-align: right;"><strong>Total</strong></td>
    <td style="text-align: center;"><strong>{{ $total }}</strong></td>
    <td></td>
</tr>