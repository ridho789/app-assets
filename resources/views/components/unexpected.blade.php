@extends('layouts.base')
<!-- @section('title', 'Unexpected Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Unexpected Expenses</h1>
            <p class="mb-0">
                The following is a list of unexpected expense of asset
                <a href="{{ url('asset-edit', ['id' => Crypt::encrypt($asset->id_asset)]) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Back to asset form">
                    <b>{{ $asset->name }}</b> <i class="fa-solid fa-rotate-left"></i>
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-edit-unexpected" tabindex="-1" role="dialog" aria-labelledby="modal-edit-unexpected" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Unexpected Expenses</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('unexpected-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="form_unexpected_expense">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="ux_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="ux_name" name="ux_name" placeholder="Enter a name..." value="{{ old('ux_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="ux_date">Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                            1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                                <input data-datepicker="" class="form-control" id="ux_date" name="ux_date" type="text" placeholder="dd/mm/yyyy" value="" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ux_price" class="form-label">Price</label>
                            <input type="number" class="form-control  @error('ux_price') is-invalid @enderror" id="ux_price" name="ux_price" placeholder="Enter a price..." value="{{ old('ux_price') }}" required>
                            @error('ux_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ux_description" class="form-label">Description</label>
                            <textarea class="form-control" id="ux_description" name="ux_description" rows="3" placeholder="Enter a description..." required>
                            {{ old('ux_description') }}
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

@if (count($unexpected) > 0)
<div class="row justify-content-between align-items-center mb-4">
    <div class="col-9 col-lg-8 d-md-flex">
        <form action="{{ url('unexpected-search', ['id' => Crypt::encrypt($asset->id_asset)]) }}" method="GET">
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
                        <th class="border-0">Date</th>
                        <th class="border-0">Price</th>
                        <th class="border-0">Description</th>
                        <th class="border-0 rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($unexpected) > 0)
                    @foreach($unexpected as $u)
                    <tr data-id="{{ $u->id_unexpected_expenses }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="unexpected-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $u->name }}</td>
                        <td class="unexpected-date">{{ date('j F Y', strtotime($u->date)) }}</td>
                        <td class="unexpected-price">{{ 'IDR ' . number_format($u->price ?? 0, 0, ',', '.') }}</td>
                        <td class="unexpected-description" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $u->description }}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-icon-only btn btn-primary btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#modal-edit-unexpected">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ url('unexpected-delete/' . $u->id_unexpected_expenses) }}" class="btn btn-icon-only btn btn-danger btn-sm delete-button ms-2" onclick="return confirmDelete()"><i class="fa fa-trash"></i>
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

    const ux_description = document.getElementById('ux_description');

    if (ux_description && ux_description.value) {
        ux_description.value = ux_description.value.trim();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var name = row.querySelector(".unexpected-name").textContent;
                var date = row.querySelector(".unexpected-date").textContent.trim();
                var price = row.querySelector(".unexpected-price").textContent.replace(/[^\d]/g, '');
                var description = row.querySelector(".unexpected-description").textContent;

                const dateFormat = moment(date, 'DD MMMM YYYY').format('MM/D/Y');
                const priceConvert = parseFloat(price);

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("ux_name").value = name;
                document.getElementById("ux_date").value = dateFormat;
                document.getElementById("ux_price").value = price;
                document.getElementById("ux_description").value = description;
            });
        });
    });
</script>
@endsection