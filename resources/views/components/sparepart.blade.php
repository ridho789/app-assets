@extends('layouts.base')
<!-- @section('title', 'Spareparts Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Spareparts Expenses</h1>
            <p class="mb-0">
                The following is a list of spareparts expense of asset <b>{{ $asset->name }}</b>
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
                        <th class="border-0">Name</th>
                        <th class="border-0">Purchase Date</th>
                        <th class="border-0">Price</th>
                        <th class="border-0">Description</th>
                        <!-- <th class="border-0 rounded-end">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($sparepart as $sp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$sp->name}}</td>
                        <td>{{ date('j F Y', strtotime($sp->purchase_date)) }}</td>
                        <td>{{ 'IDR ' . number_format($sp->price ?? 0, 0, ',', '.') }}</td>
                        <td>{{$sp->description}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection