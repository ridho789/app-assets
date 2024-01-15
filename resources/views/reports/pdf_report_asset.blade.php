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
                <h4>Report Asset - {{ $asset->name }} ( {{ $asset->status }} )</h4>
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
            
            <h5>Unexpected Expenses</h5>
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
                @if(count($unexpected) > 0)
                    <tbody>
                        @php
                            $totalUnexpected = 0;
                        @endphp
                        @foreach($unexpected as $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$u->name}}</td>
                            <td>{{ date('j F Y', strtotime($u->date)) }}</td>
                            <td>{{ 'IDR ' . number_format($u->price ?? 0, 0, ',', '.') }}</td>
                            <td>{{$u->description}}</td>
                        </tr>
                        @php
                            $totalUnexpected += $u->price;
                        @endphp
                        @endforeach 
                    </tbody>
                @else
                    <tbody>
                        <tr>
                            <td colspan="6" class="center-text">No data available</td>
                        </tr>
                    </tbody>
                @endif
            </table>

            <div class="total-sub-expense">
                <span>Total Unexpected Expenses: <b>{{ 'IDR ' . number_format($totalUnexpected ?? 0, 0, ',', '.') }}</b></span>
            </div>

            <h5>Materials Expenses</h5>
            <table class="table table-responsive-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Purchase Date</th>
                        <th>Amount</th>
                        <th>Purchase Price</th>
                        <th>Description</th>
                    </tr>
                </thead>
                @if(count($material) > 0)
                    <tbody>
                        @php
                            $totalMaterial = 0;
                        @endphp
                        @foreach($material as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$m->name}}</td>
                            <td>{{ date('j F Y', strtotime($m->purchase_date)) }}</td>
                            <td>{{$m->amount}}</td>
                            <td>{{ 'IDR ' . number_format($m->purchase_price ?? 0, 0, ',', '.') }}</td>
                            <td>{{$m->description}}</td>
                        </tr>
                        @php
                            $totalMaterial += $m->purchase_price;
                        @endphp
                        @endforeach 
                    </tbody>
                @else
                    <tbody>
                        <tr>
                            <td colspan="6" class="center-text">No data available</td>
                        </tr>
                    </tbody>
                @endif
            </table>

            <div class="total-sub-expense">
                <span>Total Materials Expenses: <b>{{ 'IDR ' . number_format($totalMaterial ?? 0, 0, ',', '.') }}</b></span>
            </div>

            <h5>Salary Expenses</h5>
            <table class="table table-responsive-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Period</th>
                        <th>Date</th>
                        <th>Amount Paid</th>
                        <th>Description</th>
                    </tr>
                </thead>
                @if(count($salary) > 0)
                    <tbody>
                        @php
                            $totalSalary = 0;
                        @endphp
                        @foreach($salary as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$s->period}}</td>
                            <td>{{ date('j F Y', strtotime($s->date)) }}</td>
                            <td>{{ 'IDR ' . number_format($s->amount_paid ?? 0, 0, ',', '.') }}</td>
                            <td>{{$s->description}}</td>
                        </tr>
                        @php
                            $totalSalary += $s->amount_paid;
                        @endphp
                        @endforeach 
                    </tbody>
                @else
                    <tbody>
                        <tr>
                            <td colspan="6" class="center-text">No data available</td>
                        </tr>
                    </tbody>
                @endif
            </table>

            <div class="total-sub-expense">
                <span>Total Salary Expenses: <b>{{ 'IDR ' . number_format($totalSalary ?? 0, 0, ',', '.') }}</b></span>
            </div>

            <h5>Spareparts Expenses</h5>
            <table class="table table-responsive-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Purchase Date</th>
                        <th>Price</th>
                        <th>Description</th>
                    </tr>
                </thead>
                @if(count($sparepart) > 0)
                    <tbody>
                        @php
                            $totalSparepart = 0;
                        @endphp
                        @foreach($sparepart as $sp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$sp->name}}</td>
                            <td>{{ date('j F Y', strtotime($sp->purchase_date)) }}</td>
                            <td>{{ 'IDR ' . number_format($sp->price ?? 0, 0, ',', '.') }}</td>
                            <td>{{$sp->description}}</td>
                        </tr>
                        @php
                            $totalSparepart += $sp->price;
                        @endphp
                        @endforeach 
                    </tbody>
                @else
                    <tbody>
                        <tr>
                            <td colspan="6" class="center-text">No data available</td>
                        </tr>
                    </tbody>
                @endif
            </table>

            <div class="total-sub-expense">
                <span>Total Spareparts Expenses: <b>{{ 'IDR ' . number_format($totalSparepart ?? 0, 0, ',', '.') }}</b></span>
            </div>

            <h5>Fuel Expenses</h5>
            <table class="table table-responsive-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Price</th>
                    </tr>
                </thead>
                @if(count($fuel) > 0)
                    <tbody>
                        @php
                            $totalFuel = 0;
                        @endphp
                        @foreach($fuel as $f)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$f->name}}</td>
                            <td>{{ date('j F Y', strtotime($f->date)) }}</td>
                            <td>{{ 'IDR ' . number_format($f->price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $totalFuel += $f->price;
                        @endphp
                        @endforeach 
                    </tbody>
                @else
                    <tbody>
                        <tr>
                            <td colspan="6" class="center-text">No data available</td>
                        </tr>
                    </tbody>
                @endif
            </table>

            <div class="total-sub-expense">
                <span>Total Fuel Expenses: <b>{{ 'IDR ' . number_format($totalFuel ?? 0, 0, ',', '.') }}</b></span>
            </div>
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
