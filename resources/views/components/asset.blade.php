@extends('layouts.base')
<!-- @section('title', 'Create New Asset') -->
@section('content')
@if(empty($asset))
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Form New Asset</h1>
            <p class="mb-0">
                Fill in the form below and all fields must be completed
            </p>
        </div>
    </div>
</div>
<div class="card bg-white-100 border-0 shadow">
    <div class="card-header flex-row flex-0">
        <form action="{{ url('asset-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-1"><span></span></div>
                <div class="col-xl-9">
                    <div class="mb-3">
                        <label for="name" class="form-label">Asset Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter the asset name..." 
                        oninput="this.value = this.value.toUpperCase()" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter a location..." 
                        oninput="this.value = this.value.toUpperCase()" value="{{ old('location') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price</label>
                        <input type="text" class="form-control @error('purchase_price') is-invalid @enderror" 
                        id="purchase_price" name="purchase_price" placeholder="Enter a purchase price..." value="{{ old('purchase_price') }}" required>
                        @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="purchase_date">Purchase Date</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                        1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </span>
                            <input data-datepicker="" class="form-control" id="purchase_date" name="purchase_date" type="text" placeholder="dd/mm/yyyy" value="{{ old('purchase_date') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a description..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="my-1 me-2" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select a status...</option>
                            <option value="No Activity">No Activity</option>
                            <option value="On Progress">On Progress</option>
                            <option value="Finished">Finished</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
                <div class="col-xl-2"><span></span></div>
            </div>
        </form>
    </div>
</div>
@else
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <h2 class="h4">Asset Breakdown Form</h2>
        <p class="mb-0">You can get complete data about the asset on this page.</p>
    </div>
    <div class="btn-toolbar">
        <div>
            <button class="btn btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-new-expense">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Expense
            </button>
        </div>
        <div class="btn-group ms-2 ms-lg-3">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-preview-pdf">
                PDF
            </button>

            <!-- <a href="{{ url('report-excel', ['id' => $asset->id_asset]) }}" class="btn btn-outline-tertiary">
                Excel
            </a> -->

            <button class="btn btn-outline-tertiary" data-bs-toggle="modal" data-bs-target="#modal-excel">
                Excel
            </button>
        </div>
    </div>
</div>

<!-- modal new expense -->
<div class="modal fade" id="modal-new-expense" tabindex="-1" role="dialog" aria-labelledby="modal-new-expense" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">New Expense</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('expense-store') }}" method="POST" id="form-store-new-expense">
                <div class="modal-body mb-2">
                    @csrf
                    <input type="hidden" name="id" value="{{ $asset->id_asset }}">
                    <div class="mb-4">
                        <label class="my-1 me-2 mb-2" for="expense_category">Expense Category</label>
                        <select class="form-select" name="id_category" id="id_category" required>
                            <option value="">...</option>
                            @foreach ($category as $c)
                                <option value="{{ $c->id_category }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter a name..." value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="date">Date</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                        1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </span>
                            <input data-datepicker="" class="form-control" id="date" name="date" type="text" placeholder="dd/mm/yyyy" value="{{ old('date') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control input-ux-price  @error('price') is-invalid @enderror" id="price" name="price" placeholder="Enter a price..." value="{{ old('price') }}" required>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a description..." required>
                        {{ old('description') }}
                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal pdf -->
<div class="modal fade" id="modal-preview-pdf" tabindex="-1" role="dialog" aria-labelledby="modal-preview-pdf" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Select PDF Report - <b>{{ $asset->name }} ({{ $asset->status }})</b></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <a href="{{ url('report-pdf-' . urlencode(Crypt::encrypt($asset->id_asset)) . '?type=with-details') }}" target="_blank" class="btn btn-primary">
                    Report With Details
                </a>
                <a href="{{ url('report-pdf-' . urlencode(Crypt::encrypt($asset->id_asset)) . '?type=without-details') }}" target="_blank" class="btn btn-secondary ms-auto">
                    Report Without Details
                </a>
            </div>
        </div>
    </div>
</div>

<!-- modal excel -->
<div class="modal fade" id="modal-excel" tabindex="-1" role="dialog" aria-labelledby="modal-excel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Select Excel Report - <b>{{ $asset->name }} ({{ $asset->status }})</b></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <a href="{{ url('report-excel-' . urlencode(Crypt::encrypt($asset->id_asset)) . '?type=details') }}" class="btn btn-primary">Report With Details</a>
                <a href="{{ url('report-excel-' . urlencode(Crypt::encrypt($asset->id_asset)) . '?type=without-details') }}" class="btn btn-secondary ms-auto">Report Without Details</a>
            </div>
        </div>
    </div>
</div>


<div class="card bg-white-100 border-0 shadow">
    <div class="card-header flex-row flex-0">
        <form action="{{ url('asset-update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-1"></div>
                <div class="col-xl-5">
                    <h4>Asset Details</h4>
                    <input type="hidden" name="id" value="{{ $asset->id_asset }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Asset Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter the asset name..." 
                        oninput="this.value = this.value.toUpperCase()" value="{{ old('name', $asset->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter a location..." 
                        oninput="this.value = this.value.toUpperCase()" value="{{ old('location', $asset->location) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price</label>
                        <div class="input-group">
                            <input type="text" class="form-control  @error('purchase_price') is-invalid @enderror" id="purchase_price" 
                            name="purchase_price" placeholder="Enter a purchase price..." value="{{ 'IDR ' . number_format($asset->purchase_price, 0, ',', '.') }}">
                        </div>
                        @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="purchase_date">Purchase Date</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                        1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </span>
                            <input data-datepicker="" class="form-control" id="purchase_date" name="purchase_date" type="text" value="{{ old('purchase_date', \Carbon\Carbon::parse($asset->purchase_date)->format('m/d/Y')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="desc" name="description" rows="3" placeholder="Enter a description..." required>
                        {{ old('description', $asset->description) }}
                        </textarea>
                    </div>
                    <div class="mb-3">
                        <label class="my-1 me-2" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select a status...</option>
                            <option value="No Activity" {{ old('status', $asset->status) == 'No Activity' ? 'selected' : '' }}>No Activity</option>
                            <option value="On Progress" {{ old('status', $asset->status) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="Finished" {{ old('status', $asset->status) == 'Finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                    </div>
                    <button class="btn btn-secondary mb-3" type="submit">Update</button>
                </div>
                <div class="col-xl-1"></div>
                <div class="col-xl-4">
                    <li role="separator" class="d-md-none dropdown-divider mt-3 mb-3 border-gray-700"></li>
                    <h4>Expense Details</h4>
                    @foreach ($expenses as $categoryName => $expenseGroup)
                        <div class="mb-3">
                            <label for="{{ strtolower(str_replace(' ', '_', $categoryName)) }}_expenses" class="form-label">{{ $categoryName }}</label>
                            @if ($expenseGroup['total'])
                                <div class="input-group">
                                    <input type="text" class="form-control" id="{{ strtolower(str_replace(' ', '_', $categoryName)) }}_expenses" 
                                        name="{{ strtolower(str_replace(' ', '_', $categoryName)) }}_expenses" 
                                        value="{{ 'IDR ' . number_format($expenseGroup['total'], 0, ',', '.') }}" readonly>
                                    <a href="{{ url('/' . strtolower(str_replace(' ', '-', $categoryName)) . '/' . Crypt::encrypt($asset->id_asset)) }}"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="Click to view details" class="btn btn-primary input-group-text">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            @else
                                <input type="text" class="form-control" id="{{ strtolower(str_replace(' ', '_', $categoryName)) }}_expenses" 
                                    name="{{ strtolower(str_replace(' ', '_', $categoryName)) }}_expenses" 
                                    value="{{ 'IDR ' . number_format(0, 0, ',', '.') }}" readonly>
                            @endif
                        </div>
                    @endforeach
                    <div class="col-xl-6">
                        <label for="total_expenses" class="form-label" style="margin-bottom: -2px;">
                            <h5>Total Expenses</h5>
                        </label>
                        <input type="text" class="form-control" id="total_expenses" name="total_expenses" 
                            value="{{ 'IDR ' . number_format($totalExpenses, 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if(session('error_type') == 'sweet-alert')
    <div class="alert" id="errorAlert" style="display: none;">
        <strong>Error!</strong> {{ session('error') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var error = "{{ session('error') }}";
            if (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error,
                });
            }
        });
    </script>
@endif

@endif

<script>
    // remove whitespace
    const desc = document.getElementById('desc');
    const description = document.getElementById('description');
    const ux_description = document.getElementById('ux_description');
    const m_description = document.getElementById('m_description');
    const s_description = document.getElementById('s_description');
    const sp_description = document.getElementById('sp_description');
    const f_description = document.getElementById('f_description');

    if (desc && desc.value) {
        desc.value = desc.value.trim();
    }

    if (description && description.value) {
        description.value = description.value.trim();
    }

    if (ux_description && ux_description.value) {
        ux_description.value = ux_description.value.trim();
    }

    if (m_description && m_description.value) {
        m_description.value = m_description.value.trim();
    }

    if (s_description && s_description.value) {
        s_description.value = s_description.value.trim();
    }

    if (sp_description && sp_description.value) {
        sp_description.value = sp_description.value.trim();
    }

    if (f_description && f_description.value) {
        f_description.value = f_description.value.trim();
    }

    function showForm(selectedValue) {
        // Mendapatkan nilai kategori yang dipilih
        var selectedCategory = selectedValue;

        // Menyembunyikan semua form
        hideAllForms();

        // Menampilkan form yang sesuai dengan kategori
        if (selectedCategory === 'Unexpected') {
            document.getElementById('form_unexpected_expense').style.display = 'block';
            setRequired('form_unexpected_expense', true);
        } else if (selectedCategory === 'Material') {
            document.getElementById('form_material').style.display = 'block';
            setRequired('form_material', true);
        } else if (selectedCategory === 'Salary') {
            document.getElementById('form_salary').style.display = 'block';
            setRequired('form_salary', true);
        } else if (selectedCategory === 'Sparepart') {
            document.getElementById('form_sparepart').style.display = 'block';
            setRequired('form_sparepart', true);
        } else if (selectedCategory === 'Fuel') {
            document.getElementById('form_fuel').style.display = 'block';
            setRequired('form_fuel', true);
        }
    }

    function hideAllForms() {
        // Menyembunyikan semua form
        document.getElementById('form_unexpected_expense').style.display = 'none';
        document.getElementById('form_material').style.display = 'none';
        document.getElementById('form_salary').style.display = 'none';
        document.getElementById('form_sparepart').style.display = 'none';
        document.getElementById('form_fuel').style.display = 'none';

        // Menghapus atribut 'required' dari semua elemen input dalam semua formulir
        setRequired('form_unexpected_expense', false);
        setRequired('form_material', false);
        setRequired('form_salary', false);
        setRequired('form_sparepart', false);
        setRequired('form_fuel', false);
    }

    function setRequired(formId, isRequired) {
        // Mendapatkan semua elemen input dan textarea dalam formulir
        var formElements = document.getElementById(formId).querySelectorAll('input, textarea');

        // Menetapkan atau menghapus atribut 'required' untuk setiap elemen
        for (var i = 0; i < formElements.length; i++) {
            if (isRequired) {
                formElements[i].setAttribute('required', 'required');
            } else {
                formElements[i].removeAttribute('required');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var expenseCategorySelect = document.getElementById('expense_category');

        if (expenseCategorySelect) {
            expenseCategorySelect.addEventListener('change', function() {
                var selectedValue = expenseCategorySelect.value;
                showForm(selectedValue);
            });
        }
    });

    // currency
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
        let inputPrices = document.querySelectorAll("#ux_price, #m_purchase_price, #s_amount_paid, #sp_price, #f_price, #purchase_price, #price");
        inputPrices.forEach(function(inputPrice) {
            inputPrice.addEventListener("input", function() {
                this.value = formatCurrency(this.value);
            });
        });
    });
</script>
@endsection