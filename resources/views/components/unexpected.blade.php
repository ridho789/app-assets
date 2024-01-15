@extends('layouts.base')
<!-- @section('title', 'Unexpected Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Unexpected Expenses</h1>
            <p class="mb-0">
                The following is a list of unexpected expense of asset <b>{{ $asset->name }}</b>
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
                        <th class="border-0">Date</th>
                        <th class="border-0">Price</th>
                        <th class="border-0">Description</th>
                        <!-- <th class="border-0 rounded-end">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($unexpected as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$u->name}}</td>
                        <td>{{ date('j F Y', strtotime($u->date)) }}</td>
                        <td>{{ 'IDR ' . number_format($u->price ?? 0, 0, ',', '.') }}</td>
                        <td>{{$u->description}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection