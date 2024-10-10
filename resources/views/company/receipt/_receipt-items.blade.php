
<div class="row mt-3" >
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <tbody>
                <tr>
                    <td style=""><strong>Name: </strong> {{$receipt->getCustomer->first_name ?? '' }} {{$receipt->getCustomer->last_name ?? '' }}</td>
                    <td style=""><strong>Mobile No.: </strong> {{$receipt->getCustomer->phone ?? ''}}</td>
                    <td style=""><strong> Email: </strong>{{$receipt->getCustomer->email ?? '' }}</td>
                    <td style=""><strong>Address: </strong> {{$receipt->getCustomer->street ?? ''}}</td>
                </tr>
                <tr>
                    <td style=""><strong>Estate: </strong> {{ $receipt->getProperty->getEstate->e_name ?? '' }}</td>
                    <td style=""><strong>House No.: </strong> {{ $receipt->getProperty->house_no ?? '' }}</td>
                    <td style=""><strong> Street: </strong>{{ $receipt->getProperty->street ?? '' }}</td>
                    <td style=""><strong>Property Code: </strong> {{ $receipt->getProperty->property_code ?? '' }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped mb-0">

        <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>Quantity</th>
            <th style="text-align: right;">Cost({{env('APP_CURRENCY')}})</th>
            <th style="text-align: right;">Total({{env('APP_CURRENCY')}})</th>
        </tr>
        </thead>
        <tbody>
        @foreach($receipt->getInvoice->getInvoiceDetail as $key => $detail)
            <tr>
                <th>{{ $key +1  }}</th>
                <td style="text-align: left">{{$detail->description ?? '' }}</td>
                <td style="text-align: center;">{{ number_format($detail->quantity ?? 0) }}</td>
                <td style="text-align: right;">{{ number_format($detail->unit_cost ?? 0,2)  }}</td>
                <td style="text-align: right;">{{ number_format(($detail->quantity ?? 0) * ($detail->unit_cost ?? 0),2)  }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong>Total</strong></td>
            <td class="border-0 text-end"> <span></span><span id="totalAmount">{{env('APP_CURRENCY')}}{{number_format($receipt->getInvoice->total,2)}}</span></td>
        </tr>
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong> Paid</strong></td>
            <td class="border-0 text-end"><span></span><span>{{env('APP_CURRENCY')}}{{number_format($receipt->sub_total,2)}}</span></td>
        </tr>

        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong>Refund Rate</strong></td>
            <td class="border-0 text-end"> <span></span><span id="refund-rate">{{ number_format($refundRate,2).'%' ?? 0 }}</span></td>
        </tr>
        <tr>
            <td colspan="4" class="border-0 text-end">
                <input type="hidden" name="rate" value="{{$refundRate}}">
                <input type="hidden" name="receipt" value="{{$receipt->id}}">
                <strong>Balance</strong></td>
            <td class="border-0 text-end"> <span></span><span id="refu">{{env('APP_CURRENCY')}}{{number_format(($receipt->getInvoice->total ?? 0) - ($receipt->sub_total ?? 0) ,2)}}</span></td>
        </tr>
        <tr class="bg-dark">
            <td colspan="4" class="border-0 text-end">
                <strong class="text-white">Proposed Refund Amount</strong></td>
            <td class="border-0 text-end">
                <input name="amount" type="number" step="0.01" placeholder="Refund Amount" value="{{ $receipt->sub_total - (($refundRate/100) * $receipt->sub_total) }}" class="form-control">
            </td>
        </tr>
        </tbody>
    </table>
</div>
