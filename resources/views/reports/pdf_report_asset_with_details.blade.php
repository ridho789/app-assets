<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Report Asset (With Details) - {{ $asset->name }}</title>

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
                border: 1px solid #ccc;
                text-align: left;
                font-size: 12px;
            }

            .total-sub-expense {
                font-size: x-small;
                float: right;
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
        <div style="width:100%; font-weight:bold;">
            <div style="float:right;">
                PT. SATRIA UTAMA GROUP
            </div>
            <div style="float: left; margin-top:30px;">
                <h4>Report Asset Details - {{ $asset->name }} ( {{ $asset->status }} )</h4>
            </div>
        </div>
        
        <div style="margin-top: 60px; margin-bottom:60px;">
            <table class="table table-responsive-sm">
                <tr>
                    <th>Location</th>
                    <td>{{ $asset->location }}</td>
                </tr>
                <tr>
                    <th>Purchase Date</th>
                    <td>{{ date('l, j F Y', strtotime($asset->purchase_date)) }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $asset->description }}</td>
                </tr>
            </table>
            
            @foreach ($expenses as $categoryName => $expenseGroup)
                <h5>{{ ucwords(strtolower($categoryName)) }}</h5>
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    @if(count($expenseGroup) > 0)
                        <tbody>
                            @php
                                $totalCategory = 0;
                            @endphp
                            @foreach($expenseGroup as $index => $expense)
                                @if(is_object($expense))
                                <tr>
                                    <td>{{ $index + 1 }}.</td>
                                    <td>{{ $expense->name }}</td>
                                    <td>{{ date('j F Y', strtotime($expense->date ?? '')) }}</td>
                                    <td>{{ 'IDR ' . number_format($expense->price ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $expense->desc ?? '-' }}</td>
                                </tr>
                                @php
                                    $totalCategory += $expense->price ?? 0;
                                @endphp
                                @endif
                            @endforeach
                        </tbody>
                    @else
                        <tbody>
                            <tr>
                                <td colspan="5" class="center-text">No data available</td>
                            </tr>
                        </tbody>
                    @endif
                </table>
    
                <div class="total-sub-expense">
                    <span>Total {{ ucwords(strtolower($categoryName)) }}: <b>{{ 'IDR ' . number_format($expenseGroup['total'] ?? $totalCategory, 0, ',', '.') }}</b></span>
                </div>
            @endforeach
            
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
