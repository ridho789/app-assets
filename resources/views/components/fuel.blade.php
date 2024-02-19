@extends('layouts.base')
<!-- @section('title', 'Fuel Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Fuel Expenses</h1>
            <p class="mb-0">
                The following is a list of fuel expense of asset
                <a href="{{ url('asset-edit', ['id' => Crypt::encrypt($asset->id_asset)]) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Back to asset form">
                    <b>{{ $asset->name }}</b> <i class="fa-solid fa-rotate-left"></i>
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-edit-fuel" tabindex="-1" role="dialog" aria-labelledby="modal-edit-fuel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Fuel Expenses</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('fuel-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="form_fuel">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="f_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="f_name" name="f_name" placeholder="Enter a name..." value="{{ old('f_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="f_date">Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                            1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                                <input data-datepicker="" class="form-control" id="f_date" name="f_date" type="text" placeholder="dd/mm/yyyy" value="{{ old('f_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="f_price" class="form-label">Price</label>
                            <input type="text" class="form-control  @error('f_price') is-invalid @enderror" id="f_price" name="f_price" placeholder="Enter a price..." value="{{ old('f_price') }}" required>
                            @error('f_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="f_description" class="form-label">Description</label>
                            <textarea class="form-control" id="f_description" name="f_description" rows="3" placeholder="Enter a description..." required>
                            {{ old('f_description') }}
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

@if (count($fuel) > 0)
<div class="row justify-content-between align-items-center mb-4">
    <div class="col-9 col-lg-8 d-md-flex">
        <form action="{{ url('fuel-search', ['id' => Crypt::encrypt($asset->id_asset)]) }}" method="GET">
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
                @if (count($fuel) > 0)
                    @foreach($fuel as $f)
                    <tr data-id="{{ $f->id_fuel }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="fuel-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $f->name }}</td>
                        <td class="fuel-date">{{ date('j F Y', strtotime($f->date)) }}</td>
                        <td class="fuel-price">{{ $f->price }}</td>
                        <td class="fuel-description" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $f->description }}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-icon-only btn btn-primary btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#modal-edit-fuel">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ url('fuel-delete/' . $f->id_fuel) }}" 
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

    const m_description = document.getElementById('f_description');
    if (f_description && f_description.value) {
        f_description.value = f_description.value.trim();
    }

    function formatCurrency(num) {
        num = num.toString().replace(/[^\d-]/g, '');

        num = num.replace(/-+/g, (match, offset) => offset > 0 ? "" : "-");

        let isNegative = false;
        if (num.startsWith("-")) {
            isNegative = true;
            num = num.slice(1);
        }

        let formattedNum = "IDR " + Math.abs(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        if (isNegative) {
            formattedNum = "-" + formattedNum;
        }

        return formattedNum;
    }

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var name = row.querySelector(".fuel-name").textContent;
                var date = row.querySelector(".fuel-date").textContent.trim();
                var price = row.querySelector(".fuel-price").textContent;
                var description = row.querySelector(".fuel-description").textContent;

                const dateFormat = moment(date, 'DD MMMM YYYY').format('MM/D/Y');
                const priceConvert = parseFloat(price);

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("f_name").value = name;
                document.getElementById("f_date").value = dateFormat;
                document.getElementById("f_price").value = price;
                document.getElementById("f_description").value = description;
            });
        });

        let inputPrices = document.querySelectorAll("#f_price");
        inputPrices.forEach(function(inputPrice) {
            inputPrice.addEventListener("input", function() {
                this.value = formatCurrency(this.value);
            });
        });

        let fuelPrices = document.querySelectorAll(".fuel-price");
        fuelPrices.forEach(function(fuelPrice) {
            let price = fuelPrice.textContent;
            fuelPrice.textContent = formatCurrency(price);
        });
    });
</script>
@endsection