<table >
    <thead>
    <tr>
        <th>#</th>
        <th>Employee Name</th>
        <th>Designation</th>
        @foreach($incomeHeaders as $income)
            <th  >{{ $income }}</th>
        @endforeach
        <th >Gross Salary</th>
        @foreach($deductionHeaders as $deduction)
            <th >{{ $deduction }}</th>
        @endforeach
        <th >Total Deduction</th>
        <th>Net Pay</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tableData as $key => $row)
        <tr>

            <td>{{ $key + 1 }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['design'] }}</td>
            @foreach($incomeHeaders as $income)
                <td> {{ $row['income'][$income]['amount'] ?? 0 }} </td>
            @endforeach
            <td >{{ $row['total_income'] ?? 0 }}</td>
            @foreach($deductionHeaders as $deduction)
                <td> {{ $row['deductions'][$deduction]['amount'] ?? 0 }} </td>
            @endforeach
            <td >{{ $row['total_deduction'] ?? 0 }}</td>
            <td>{{ $row['net_pay'] ?? 0 }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="3">Totals</th>
        @foreach($incomeHeaders as $income)
            <th></th>
        @endforeach
        <th>
            {{ $totalIncome ?? 0 }}
        </th>
        @foreach($deductionHeaders as $deduction)
            <th></th>
        @endforeach
        <th >{{ $totalDeduction ?? 0}}</th>
        <th >{{ $totalNet ?? 0}}</th>
    </tr>
    </tfoot>
</table>
