@extends('layouts.base')
<!-- @section('title', 'Salary Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Salary Expenses</h1>
            <p class="mb-0">
                The following is a list of salary expense of asset <b>{{ $asset->name }}</b>
            </p>
        </div>
    </div>
</div>
<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">#</th>
                        <th class="border-0">Period</th>
                        <th class="border-0">Date</th>
                        <th class="border-0">Amount Paid</th>
                        <th class="border-0">Description</th>
                        <!-- <th class="border-0 rounded-end">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($salary as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$s->period}}</td>
                        <td>{{ date('j F Y', strtotime($s->date)) }}</td>
                        <td>{{ 'IDR ' . number_format($s->amount_paid ?? 0, 0, ',', '.') }}</td>
                        <td>{{$s->description}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection