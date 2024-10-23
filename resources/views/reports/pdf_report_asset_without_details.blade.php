<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Asset (Without Details) - {{ $asset->name }}</title>

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
            <h4>Report Asset Without Details - {{ $asset->name }} ( {{ $asset->status }} )</h4>
            <h5 style="margin-top: -15px;">
                {{ $asset->location }} <br>
                {{ date('l, j F Y', strtotime($asset->purchase_date)) }} <br>
                {{ $asset->description }}
            </h5>
        </div>
    </div>

    @php
    $totalExpenses = [];
    @endphp
    
    @php
    $totalExpenses = []; // Inisialisasi array totalExpenses

    // Menghitung total untuk setiap kategori pengeluaran
    foreach ($expenses as $categoryName => $items) {
        foreach ($items as $item) {
            if (!isset($totalExpenses[$categoryName])) {
                $totalExpenses[$categoryName] = 0;
            }
            $totalExpenses[$categoryName] += $item->price; // Tambahkan harga item ke total
        }
    }
    @endphp

    @php
    $years = [];
    $totalPricesPerYear = [];

    foreach ($expenses as $categoryName => $items) {
        $totalPricesPerYear[$categoryName] = $items->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->date)->format('Y');
        })->map(function ($itemGroup) {
            return $itemGroup->sum('price'); // Ganti 'amount' dengan field yang sesuai
        });
        // Mengumpulkan tahun yang digunakan
        $years = array_merge($years, array_keys($totalPricesPerYear[$categoryName]->toArray()));
    }

    $years = array_unique($years);
    sort($years);
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

            @foreach ($totalPricesPerYear as $categoryName => $totals)
            <tr>
                <td><b>{{ ucwords(strtolower($categoryName)) }}</b></td>
                @foreach ($years as $year)
                <td>
                    {{ 'IDR ' . number_format($totals[$year] ?? 0, 0, ',', '.') }} <br>
                </td>
                @endforeach
                <td><b>{{ 'IDR ' . number_format($totalExpenses[$categoryName] ?? 0, 0, ',', '.') }}</b></td>
            </tr>
            @endforeach
        </table>
    </div>

    <div style="display: flex; flex-direction: column; align-items: flex-start; float:right;">
        <div style="display: flex; align-items: baseline; margin-bottom: 10px; font-size: small;">
            <span style="width: 150px; display: inline-block;">Purchase Price</span>
            <span style="display: inline-block;">: {{ 'IDR ' . number_format($asset->purchase_price ?? 0, 0, ',', '.') }}</span>
        </div>

        <div style="display: flex; align-items: baseline; margin-bottom: 20px; font-size: small;">
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