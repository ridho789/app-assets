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
                width: 95%;
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
        </style>

    </head>

    <body>
        <div style="width:100%; font-weight:bold;">
            <div style="float:right;">
                PT. SATRIA UTAMA GROUP
            </div>
            <div style="float: left; margin-top:30px;">
                <h4>Report Asset {{ $asset->name }} ( {{ $asset->status }} )</h4>
            </div>
        </div>
        
        <div style="margin-top: 60px;">
            <table class="table table-responsive-sm" class="display" style="width:100%;">
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
            <table class="table table-responsive-sm" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unexpected as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$u->name}}</td>
                        <td>{{ date('j F Y', strtotime($u->date)) }}</td>
                        <td>{{$u->price}}</td>
                        <td>{{$u->description}}</td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>

            <h5>Materials Expenses</h5>
            <table class="table table-responsive-sm" class="display" style="width:100%">
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
                <tbody>
                    @foreach($material as $m)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$m->name}}</td>
                        <td>{{ date('j F Y', strtotime($m->purchase_date)) }}</td>
                        <td>{{$m->amount}}</td>
                        <td>{{$m->purchase_price}}</td>
                        <td>{{$m->description}}</td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>

            <h5>Salary Expenses</h5>
            <table class="table table-responsive-sm" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Period</th>
                        <th>Date</th>
                        <th>Amount Paid</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salary as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$s->period}}</td>
                        <td>{{ date('j F Y', strtotime($s->date)) }}</td>
                        <td>{{$s->amount_paid}}</td>
                        <td>{{$s->description}}</td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>

            <h5>Spareparts Expenses</h5>
            <table class="table table-responsive-sm" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Purchase Date</th>
                        <th>Price</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sparepart as $sp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$sp->name}}</td>
                        <td>{{ date('j F Y', strtotime($sp->purchase_date)) }}</td>
                        <td>{{$sp->price}}</td>
                        <td>{{$sp->description}}</td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>

            <h5>Fuel Expenses</h5>
            <table class="table table-responsive-sm" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fuel as $f)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$f->name}}</td>
                        <td>{{ date('j F Y', strtotime($f->date)) }}</td>
                        <td>{{$f->price}}</td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>

        <div style="display: flex; flex-direction: column; align-items: flex-start;">
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
