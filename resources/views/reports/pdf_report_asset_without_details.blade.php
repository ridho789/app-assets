<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Asset - {{ $asset->name }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px auto;
        }

        th {
            color: black;
            font-weight: bold;
        }

        td,
        th {
            padding: 5px;
            border: 0px solid #ccc;
            text-align: left;
            font-size: 12.5px;
        }

        .total-sub-expense {
            float: left;
            margin-bottom: 15px;
        }

        p {
            font-size: smaller;
        }

        .center-text {
            text-align: center;
        }
    </style>

</head>

<body>
    <div style="width:100%; font-weight:bold; margin-left:5px;">
        <div style="float:right;">
            PT. SATRIA UTAMA GROUP
        </div>
        <div style="float: left; margin-top:30px;">
            <h4>Report Asset - {{ $asset->name }} ( {{ $asset->status }} )</h4>
            <h5 style="margin-top: -15px;">
                {{ $asset->location }} <br>
                {{ date('l, j F Y', strtotime($asset->purchase_date)) }} <br>
                {{ $asset->description }}
            </h5>
        </div>
    </div>

    @php
    $totalUnexpected = 0;
    $totalMaterial = 0;
    $totalSalary = 0;
    $totalSparepart = 0;
    $totalFuel = 0;
    @endphp

    @foreach($unexpected as $u)
    @php
    $totalUnexpected += $u->price;
    @endphp
    @endforeach

    @foreach($material as $m)
    @php
    $totalMaterial += $m->purchase_price;
    @endphp
    @endforeach

    @foreach($salary as $s)
    @php
    $totalSalary += $s->amount_paid;
    @endphp
    @endforeach

    @foreach($sparepart as $sp)
    @php
    $totalSparepart += $sp->price;
    @endphp
    @endforeach

    @foreach($fuel as $f)
    @php
    $totalFuel += $f->price;
    @endphp
    @endforeach

    @php
    $materialYears = $totalMaterialPricePerYear->keys()->toArray();
    $fuelYears = $totalFuelPricePerYear->keys()->toArray();
    $salaryYears = $totalSalaryPricePerYear->keys()->toArray();
    $sparepartYears = $totalSparepartPricePerYear->keys()->toArray();
    $unexpectedYears = $totalUnexpectedPricePerYear->keys()->toArray();
    $years = array_unique(array_merge($materialYears, $fuelYears, $salaryYears, $sparepartYears, $unexpectedYears));
    sort($years); // Mengurutkan tahun dari terkecil
    @endphp

    <div style="margin-bottom: 60px; margin-top:150px;">
        <table>
            <tr>
                <td></td>
                @foreach ($years as $year)
                <td><b>{{ $year }}</b></td>
                @endforeach
                <td><b>Total</b></td>
            </tr>
            <tr>
                <td><b>Unexpected Expenses</b></td>
                @foreach ($years as $year)
                <td>
                    {{ 'IDR ' . number_format($totalUnexpectedPricePerYear[$year] ?? 0, 0, ',', '.') }} <br>
                </td>
                @endforeach
                <td><b>{{ 'IDR ' . number_format($totalUnexpected ?? 0, 0, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td><b>Material Expenses</b></td>
                @foreach ($years as $year)
                <td>
                    {{ 'IDR ' . number_format($totalMaterialPricePerYear[$year] ?? 0, 0, ',', '.') }} <br>
                </td>
                @endforeach
                <td><b>{{ 'IDR ' . number_format($totalMaterial ?? 0, 0, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td><b>Salary Expenses</b></td>
                @foreach ($years as $year)
                <td>
                    {{ 'IDR ' . number_format($totalSalaryPricePerYear[$year] ?? 0, 0, ',', '.') }} <br>
                </td>
                @endforeach
                <td><b>{{ 'IDR ' . number_format($totalSalary ?? 0, 0, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td><b>Sparepart Expenses</b></td>
                @foreach ($years as $year)
                <td>
                    {{ 'IDR ' . number_format($totalSparepartPricePerYear[$year] ?? 0, 0, ',', '.') }} <br>
                </td>
                @endforeach
                <td><b>{{ 'IDR ' . number_format($totalSparepart ?? 0, 0, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td><b>Fuel Expenses</b></td>
                @foreach ($years as $year)
                <td>
                    {{ 'IDR ' . number_format($totalFuelPricePerYear[$year] ?? 0, 0, ',', '.') }}
                </td>
                @endforeach
                <td><b>{{ 'IDR ' . number_format($totalFuel ?? 0, 0, ',', '.') }}</b></td>
            </tr>
        </table>
    </div>

    <div style="display: flex; flex-direction: column; align-items: flex-start; float:right;">
        <div style="display: flex; align-items: baseline; margin-bottom: 10px; font-size: small; ">
            <span style="width: 150px; display: inline-block;">Purchase Price</span>
            <span style="display: inline-block;">: {{ 'IDR ' . number_format($asset->purchase_price ?? 0, 0, ',', '.') }}</span>
        </div>

        <div style="display: flex; align-items: baseline; margin-bottom: 20px; font-size: small; ">
            <span style="width: 150px; display: inline-block;">Total Expense</span>
            <span style="display: inline-block;">: {{ 'IDR ' . number_format($asset->tot_expenses ?? 0, 0, ',', '.') }}</span>
        </div>
        <hr>
        <div style="display: flex; align-items: baseline; margin-bottom: 10px; margin-top:20px;">
            <span style="width: 150px; display: inline-block;">Overall Expense</span>
            <span style="display: inline-block;">: <b>{{ 'IDR ' . number_format($asset->tot_overall_expenses ?? 0, 0, ',', '.') }}</b></span>
        </div>
    </div>

</body>

</html>