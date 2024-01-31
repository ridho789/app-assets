@extends('layouts.base')
<!-- @section('title', 'Spareparts Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Spareparts Expenses</h1>
            <p class="mb-0">
                The following is a list of spareparts expense of asset
                <a href="{{ url('asset-edit', ['id' => Crypt::encrypt($asset->id_asset)]) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Back to asset form">
                    <b>{{ $asset->name }}</b> <i class="fa-solid fa-rotate-left"></i>
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-edit-sparepart" tabindex="-1" role="dialog" aria-labelledby="modal-edit-sparepart" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Sparepart Expenses</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('sparepart-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="form_sparepart">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="sp_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="sp_name" name="sp_name" placeholder="Enter a name..." value="{{ old('sp_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="sp_purchase_date">Purchase Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                            1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                                <input data-datepicker="" class="form-control" id="sp_purchase_date" name="sp_purchase_date" type="text" placeholder="dd/mm/yyyy" value="{{ old('sp_purchase_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="sp_price" class="form-label">Price</label>
                            <input type="number" class="form-control  @error('sp_price') is-invalid @enderror" id="sp_price" name="sp_price" placeholder="Enter a price..." value="{{ old('sp_price') }}" required>
                            @error('sp_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="sp_description" class="form-label">Description</label>
                            <textarea class="form-control" id="sp_description" name="sp_description" rows="3" placeholder="Enter a description..." required>
                            {{ old('sp_description') }}
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">Update</button>
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal Content -->

@if (count($sparepart) > 0)
<div class="row justify-content-between align-items-center mb-4">
    <div class="col-9 col-lg-8 d-md-flex">
        <form action="{{ url('sparepart-search', ['id' => Crypt::encrypt($asset->id_asset)]) }}" method="GET">
            <div class="input-group me-2 me-lg-3 fmxw-300">
                <span class="input-group-text">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" name="search" class="form-control" placeholder="Search date">
            </div>
            <h6 class="mt-2">Format search date: <i>mm-dd-yy</i> / <i>mm</i> / <i>dd</i> / <i>yy</i></h6>
        </form>
    </div>
</div>
@endif

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
                        <th class="border-0 rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($sparepart) > 0)
                    @foreach($sparepart as $sp)
                    <tr data-id="{{ $sp->id_sparepart  }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="sparepart-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $sp->name }}</td>
                        <td class="sparepart-purchase-date">{{ date('j F Y', strtotime($sp->purchase_date)) }}</td>
                        <td class="sparepart-price">{{ 'IDR ' . number_format($sp->price ?? 0, 0, ',', '.') }}</td>
                        <td class="sparepart-description" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $sp->description }}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-icon-only btn btn-primary btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#modal-edit-sparepart">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ url('sparepart-delete/' . $sp->id_sparepart) }}" 
                                    class="btn btn-icon-only btn btn-danger btn-sm delete-button ms-2" onclick="return confirmDelete()"><i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No data available</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this data?');
    }

    const sp_description = document.getElementById('sp_description');
    if (sp_description && sp_description.value) {
        sp_description.value = sp_description.value.trim();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var name = row.querySelector(".sparepart-name").textContent;
                var date = row.querySelector(".sparepart-purchase-date").textContent.trim();
                var price = row.querySelector(".sparepart-price").textContent.replace(/[^\d]/g, '');
                var description = row.querySelector(".sparepart-description").textContent;

                const dateFormat = moment(date, 'DD MMMM YYYY').format('MM/D/Y');
                const priceConvert = parseFloat(price);

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("sp_name").value = name;
                document.getElementById("sp_purchase_date").value = dateFormat;
                document.getElementById("sp_price").value = price;
                document.getElementById("sp_description").value = description;
            });
        });
    });
</script>
@endsection