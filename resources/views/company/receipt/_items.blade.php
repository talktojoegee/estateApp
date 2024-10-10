<div class="table-responsive">
    <table class="table table-striped mb-0">

        <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>Quantity</th>
            <th style="text-align: right;">Cost({{env('APP_CURRENCY')}})</th>
            <th style="text-align: right;">Total({{env('APP_CURRENCY')}})</th>
            <input type="hidden" name="invoice" value="{{ $invoice->id }}" class="form-control">
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->getInvoiceDetail as $key => $detail)
            <tr>
                <th>{{ $key +1  }}</th>
                <td>{{$detail->description ?? '' }}</td>
                <td>{{ number_format($detail->quantity ?? 0) }}</td>
                <td style="text-align: right;">{{ number_format($detail->unit_cost ?? 0,2)  }}</td>
                <td style="text-align: right;">{{ number_format(($detail->quantity ?? 0) * ($detail->unit_cost ?? 0),2)  }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong>Sub-total</strong></td>
            <td class="border-0 text-end"> <span>{{env('APP_CURRENCY')}}</span><span id="totalAmount">{{number_format($invoice->sub_total ?? 0 ,2)}}</span></td>
        </tr>
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong>TAX({{$invoice->vat_rate ?? 0}}%)</strong></td>
            <td class="border-0 text-end"> <span>{{env('APP_CURRENCY')}}</span><span id="tax">{{number_format($invoice->vat ?? 0 ,2)}}</span></td>
        </tr>
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong>Discount{{$invoice->discount_type == 2 ? '('.$invoice->discount_rate.'%)' : ''}}</strong></td>
            <td class="border-0 text-end"> <span>{{env('APP_CURRENCY')}}</span><span id="discount">{{number_format($invoice->discount_amount ?? 0 ,2)}}</span></td>
        </tr>
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong>Total</strong></td>
            <td class="border-0 text-end"> <span>{{env('APP_CURRENCY')}}</span><span id="totalAmount">{{number_format($invoice->total ?? 0 ,2)}}</span></td>
        </tr>
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong> Paid</strong></td>
            <td class="border-0 text-end"><span>{{env('APP_CURRENCY')}}</span><span>{{number_format($invoice->amount_paid ?? 0 ,2)}}</span></td>
        </tr>
        <tr>
            <td colspan="4" class="border-0 text-end">
                <strong>Balance</strong></td>
            <td class="border-0 text-end"> <span>{{env('APP_CURRENCY')}}</span><span id="balance">{{number_format(($invoice->total ?? 0) - ($invoice->amount_paid ?? 0) ,2)}}</span></td>
        </tr>

        </tbody>
    </table>
</div>
