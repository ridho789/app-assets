@extends('layouts.base')
<!-- @section('title', 'Materials Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Materials Expenses</h1>
            <p class="mb-0">
                The following is a list of materials expense of asset
                <a href="{{ url('asset-edit', ['id' => Crypt::encrypt($asset->id_asset)]) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Back to asset form">
                    <b>{{ $asset->name }}</b> <i class="fa-solid fa-rotate-left"></i>
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-edit-material" tabindex="-1" role="dialog" aria-labelledby="modal-edit-material" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Material Expenses</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('material-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="form_material">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="m_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="m_name" name="m_name" placeholder="Enter a name..." value="{{ old('m_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="m_purchase_date">Purchase Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                            1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                                <input data-datepicker="" class="form-control" id="m_purchase_date" name="m_purchase_date" type="text" placeholder="dd/mm/yyyy" value="{{ old('m_purchase_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="m_amount" class="form-label">Amount</label>
                            <input type="number" class="form-control  @error('m_amount') is-invalid @enderror" id="m_amount" name="m_amount" placeholder="Enter a amount..." value="{{ old('m_amount') }}" required>
                            @error('m_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="m_purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" class="form-control  @error('m_purchase_price') is-invalid @enderror" id="m_purchase_price" name="m_purchase_price" placeholder="Enter a purchase price..." value="{{ old('m_purchase_price') }}" required>
                            @error('m_purchase_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="m_description" class="form-label">Description</label>
                            <textarea class="form-control" id="m_description" name="m_description" rows="3" placeholder="Enter a description..." required>
                            {{ old('m_description') }}
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

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">#</th>
                        <th class="border-0">Name</th>
                        <th class="border-0">Purchase Date</th>
                        <th class="border-0">Amount</th>
                        <th class="border-0">Purchase Price</th>
                        <th class="border-0">Description</th>
                        <th class="border-0 rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($material) > 0)
                    @foreach($material as $m)
                    <tr data-id="{{ $m->id_material }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="material-name">{{ $m->name }}</td>
                        <td class="material-purchase-date">{{ date('j F Y', strtotime($m->purchase_date)) }}</td>
                        <td class="material-amount">{{ $m->amount }}</td>
                        <td class="material-purchase-price">{{ 'IDR ' . number_format($m->purchase_price ?? 0, 0, ',', '.') }}</td>
                        <td class="material-description">{{ $m->description }}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-icon-only btn btn-primary btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#modal-edit-material">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ url('material-delete/' . $m->id_material) }}" 
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

    const m_description = document.getElementById('m_description');
    if (m_description && m_description.value) {
        m_description.value = m_description.value.trim();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var name = row.querySelector(".material-name").textContent;
                var date = row.querySelector(".material-purchase-date").textContent.trim();
                var amount = row.querySelector(".material-amount").textContent;
                var price = row.querySelector(".material-purchase-price").textContent.replace(/[^\d]/g, '');
                var description = row.querySelector(".material-description").textContent;

                const dateFormat = moment(date, 'DD MMMM YYYY').format('MM/D/Y');
                const priceConvert = parseFloat(price);

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("m_name").value = name;
                document.getElementById("m_purchase_date").value = dateFormat;
                document.getElementById("m_amount").value = amount;
                document.getElementById("m_purchase_price").value = price;
                document.getElementById("m_description").value = description;
            });
        });
    });
</script>
@endsection