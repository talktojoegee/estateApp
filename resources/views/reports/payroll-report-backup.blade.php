<table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
    <thead style="text-transform: uppercase;">
    <tr>
        <th>#</th>
        <th class="wd-15p">Name</th>
        <th class="wd-15p">Design</th>
        @foreach($headers as $header)
            <th class="green-highlight" style="text-align: right !important;">{{ $header }}</th>
        @endforeach
        <th style="text-align: right !important;">Total</th>

    </tr>
    </thead>
    <tbody>
    @foreach($tableData as $key => $row)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['design'] }}</td>
            @foreach($headers as $header)
                <td style="text-align: right !important;">{{ number_format($row[$header], 2) }}</td>
            @endforeach
            <td style="text-align: right !important;"><strong>{{ number_format($row['Total'],2) }}</strong></td>
        </tr>
    @endforeach

    </tbody>
</table>
