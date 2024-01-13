@extends('layouts.base')
<!-- @section('title', 'Dashboard App Assets') -->
@section('content')
@if (count($assets) > 0)
<div class="py-4">
    <a href="{{ url('/asset-create') }}" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        New Asset
    </a>
</div>
@endif
<div class="row">
    @if (count($assets) > 0)
        @foreach($assets as $asset)
        <div class="col-12 col-sm-6 col-xl-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <a href="{{ url('asset-edit', ['id' => Crypt::encrypt($asset->id_asset)]) }}">
                        <div class="row d-block d-xl-flex align-items-center">
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="d-sm-none">
                                    <h2 class="h6">Asset Name</h2>
                                    <h3 class="fw-extrabold mb-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 185px;">{{$asset->name}}</h3>
                                    <span class="badge mb-1 
                                        @if($asset->status == 'No Activity') bg-primary
                                        @elseif($asset->status == 'Cancelled') bg-danger
                                        @elseif($asset->status == 'On Progress') bg-secondary
                                        @elseif($asset->status == 'Finished') bg-success
                                        @endif">
                                        {{ $asset->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">Asset Name</h2>
                                    <h3 class="fw-extrabold mb-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{$asset->name}}</h3>
                                    <span class="badge mb-1 
                                        @if($asset->status == 'No Activity') bg-primary
                                        @elseif($asset->status == 'Cancelled') bg-danger
                                        @elseif($asset->status == 'On Progress') bg-secondary
                                        @elseif($asset->status == 'Finished') bg-success
                                        @endif">
                                        {{ $asset->status }}
                                    </span>
                                </div>
                                <small class="d-flex align-items-center text-gray-500">
                                    {{ date('j F Y', strtotime($asset->purchase_date)) }},
                                    <svg class="icon icon-xxs text-gray-500 ms-2 me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 
                                                0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 
                                                10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                    {{$asset->location}}
                                </small>
                                <div class="small d-flex mt-1">
                                    <div>Overall Expenses 
                                        <span>{{ 'IDR ' . number_format($asset->tot_overall_expenses ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endforeach 
    @else
    <div class="col-12 mb-4" style="margin-top: 20%;">
        <span class="text-center">
            <p>Sorry, no data that can be displayed yet. <br>
                <a href="{{ url('/asset-create') }}" type="submit" class="btn btn-primary mt-2" id="new-asset">Create new asset</a>
            </p>
        </span>
    </div>
    @endif
</div>
@endsection