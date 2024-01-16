@extends('layouts.base')
<!-- @section('title', 'Create New Asset') -->
@section('content')
@if(empty($asset))
<div class="card bg-white-100 border-0 shadow mt-4">
    <div class="card-header flex-row flex-0">
        <form action="{{ url('asset-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-xl-1"><span></span></div>
                <div class="col-xl-9">
                    <h4>Form Create New Asset</h4>
                    <div class="mb-3">
                        <label for="name" class="form-label">Asset Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter the asset name..." 
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter a location..." 
                            value="{{ old('location') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price</label>
                        <input type="number" class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price" 
                            name="purchase_price" placeholder="Enter a purchase price..." value="{{ old('purchase_price') }}" required>
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
                            <input data-datepicker="" class="form-control" id="purchase_date" name="purchase_date" type="text" placeholder="dd/mm/yyyy"
                                value="{{ old('purchase_date') }}" required>
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
    <div>
        <button class="btn btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-new-expense">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Expense
        </button>
    </div>
    <div class="btn-toolbar">
        <div class="btn-group ms-2 ms-lg-3">
            <a href="{{ url('asset-pdf-report', ['id' => $asset->id_asset]) }}" class="btn btn-outline-primary">
                PDF
            </a>
            
            <a href="{{ url('report-excel', ['id' => $asset->id_asset]) }}" class="btn btn-outline-tertiary">
                Excel
            </a>
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
                        <label class="my-1 me-2" for="expense_category">Expense Category</label>
                        <select class="form-select" id="expense_category" name="expense_category" required>
                            <option value="">Select a expense category...</option>
                            <option value="Unexpected">Unexpected</option>
                            <option value="Material">Material</option>
                            <option value="Salary">Salary</option>
                            <option value="Sparepart">Sparepart</option>
                            <option value="Fuel">Fuel</option>
                        </select>
                    </div>

                    <div id="form_unexpected_expense" style="display: none;">
                        <div class="mb-3">
                            <label for="ux_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="ux_name" name="ux_name" placeholder="Enter a name..." 
                                value="{{ old('ux_name') }}" required>
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
                                <input data-datepicker="" class="form-control" id="ux_date" name="ux_date" type="text" placeholder="dd/mm/yyyy"
                                value="{{ old('ux_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ux_price" class="form-label">Price</label>
                            <input type="number" class="form-control  @error('ux_price') is-invalid @enderror" id="ux_price" 
                                name="ux_price" placeholder="Enter a price..." value="{{ old('ux_price') }}" required>
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

                    <div id="form_material" style="display: none;">
                        <div class="mb-3">
                            <label for="m_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="m_name" name="m_name" placeholder="Enter a name..." 
                                value="{{ old('m_name') }}" required>
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
                                <input data-datepicker="" class="form-control" id="m_purchase_date" name="m_purchase_date" type="text" placeholder="dd/mm/yyyy"
                                value="{{ old('m_purchase_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="m_amount" class="form-label">Amount</label>
                            <input type="number" class="form-control  @error('m_amount') is-invalid @enderror" id="m_amount" 
                                name="m_amount" placeholder="Enter a amount..." value="{{ old('m_amount') }}" required>
                            @error('m_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="m_purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" class="form-control  @error('m_purchase_price') is-invalid @enderror" id="m_purchase_price" 
                                name="m_purchase_price" placeholder="Enter a purchase price..." value="{{ old('m_purchase_price') }}" required>
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

                    <div id="form_salary" style="display: none;">
                        <div class="mb-3">
                            <label for="s_period" class="form-label">Period</label>
                            <input type="text" class="form-control" id="s_period" name="s_period" placeholder="Enter a period..." 
                                value="{{ old('s_period') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="s_date">Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 
                                            1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                                <input data-datepicker="" class="form-control" id="s_date" name="s_date" type="text" placeholder="dd/mm/yyyy"
                                value="{{ old('s_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="s_amount_paid" class="form-label">Amount Paid</label>
                            <input type="number" class="form-control  @error('s_amount_paid') is-invalid @enderror" id="s_amount_paid" 
                                name="s_amount_paid" placeholder="Enter the amount paid..." value="{{ old('s_amount_paid') }}" required>
                            @error('s_amount_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="s_description" class="form-label">Description</label>
                            <textarea class="form-control" id="s_description" name="s_description" rows="3" placeholder="Enter a description..." required>
                                {{ old('s_description') }}
                            </textarea>
                        </div>
                    </div>

                    <div id="form_sparepart" style="display: none;">
                        <div class="mb-3">
                            <label for="sp_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="sp_name" name="sp_name" placeholder="Enter a name..." 
                                value="{{ old('sp_name') }}" required>
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
                                <input data-datepicker="" class="form-control" id="sp_purchase_date" name="sp_purchase_date" type="text" placeholder="dd/mm/yyyy"
                                value="{{ old('sp_purchase_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="sp_price" class="form-label">Price</label>
                            <input type="number" class="form-control  @error('sp_price') is-invalid @enderror" id="sp_price" 
                                name="sp_price" placeholder="Enter a price..." value="{{ old('sp_price') }}" required>
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

                    <div id="form_fuel" style="display: none;">
                        <div class="mb-3">
                            <label for="f_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="f_name" name="f_name" placeholder="Enter a name..." 
                                value="{{ old('f_name') }}" required>
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
                                <input data-datepicker="" class="form-control" id="f_date" name="f_date" type="text" placeholder="dd/mm/yyyy"
                                value="{{ old('f_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="f_price" class="form-label">Price</label>
                            <input type="number" class="form-control  @error('f_price') is-invalid @enderror" id="f_price" 
                                name="f_price" placeholder="Enter a price..." value="{{ old('f_price') }}" required>
                            @error('f_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
                            value="{{ old('name', $asset->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter a location..." 
                            value="{{ old('location', $asset->location) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price</label>
                        <div class="input-group">
                            <input type="number" class="form-control  @error('purchase_price') is-invalid @enderror" id="purchase_price" 
                                name="purchase_price" placeholder="Enter a purchase price..." 
                                value="{{ old('purchase_price', $asset->purchase_price) }}" required>
                            <span class="input-group-text">{{ 'IDR ' . number_format($asset->purchase_price, 0, ',', '.') }}</span>
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
                            <input data-datepicker="" class="form-control" id="purchase_date" name="purchase_date" type="text" 
                            value="{{ old('purchase_date', \Carbon\Carbon::parse($asset->purchase_date)->format('m/d/Y')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a description..." required>
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
                <div class="col-xl-4">
                    <li role="separator" class="d-md-none dropdown-divider mt-3 mb-3 border-gray-700"></li>
                    <h4>Expense Details</h4>
                    <div class="mb-3">
                        <label for="unexpected_expenses" class="form-label">Unexpected Expenses</label>
                        @if ($allUnexpectedExpenses)
                            <div class="input-group">
                                <input type="text" class="form-control" id="unexpected_expenses" 
                                    name="unexpected_expenses" value="{{ 'IDR ' . number_format($allUnexpectedExpenses, 0, ',', '.') }}" readonly>
                                <a href="{{ url('/unexpected-index', ['id' => Crypt::encrypt($asset->id_asset)]) }}" 
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Click to view details" class="btn btn-primary input-group-text">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        @else
                            <input type="text" class="form-control" id="unexpected_expenses" 
                                name="unexpected_expenses" value="{{ 'IDR ' . number_format($allUnexpectedExpenses, 0, ',', '.') }}" readonly>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="materials_expenses" class="form-label">Materials Expenses</label>
                        @if ($allMaterialExpenses)
                            <div class="input-group">
                                <input type="text" class="form-control" id="materials_expenses" 
                                    name="materials_expenses" value="{{ 'IDR ' . number_format($allMaterialExpenses, 0, ',', '.') }}" readonly>
                                <a href="{{ url('/material-index', ['id' => Crypt::encrypt($asset->id_asset)]) }}" 
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Click to view details" class="btn btn-primary input-group-text">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        @else
                            <input type="text" class="form-control" id="materials_expenses" 
                                name="materials_expenses" value="{{ 'IDR ' . number_format($allMaterialExpenses, 0, ',', '.') }}" readonly>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="salary_expenses" class="form-label">Salary Expenses</label>
                        @if ($allSalaryExpenses)
                            <div class="input-group">
                                <input type="text" class="form-control" id="salary_expenses" 
                                    name="salary_expenses" value="{{ 'IDR ' . number_format($allSalaryExpenses, 0, ',', '.') }}" readonly>
                                <a href="{{ url('/salary-index', ['id' => Crypt::encrypt($asset->id_asset)]) }}" 
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Click to view details" class="btn btn-primary input-group-text">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        @else
                            <input type="text" class="form-control" id="salary_expenses" 
                                name="salary_expenses" value="{{ 'IDR ' . number_format($allSalaryExpenses, 0, ',', '.') }}" readonly>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="spareparts_expenses" class="form-label">Spareparts Expenses</label>
                        @if ($allSparepartExpenses)
                            <div class="input-group">
                                <input type="text" class="form-control" id="spareparts_expenses" 
                                    name="spareparts_expenses" value="{{ 'IDR ' . number_format($allSparepartExpenses, 0, ',', '.') }}" readonly>
                                <a href="{{ url('/sparepart-index', ['id' => Crypt::encrypt($asset->id_asset)]) }}" 
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Click to view details" class="btn btn-primary input-group-text">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        @else
                            <input type="text" class="form-control" id="spareparts_expenses" 
                                name="spareparts_expenses" value="{{ 'IDR ' . number_format($allSparepartExpenses, 0, ',', '.') }}" readonly>
                        @endif
                    </div>
                    <div class="mb-5">
                        <label for="fuel_expenses" class="form-label">Fuel Expenses</label>
                        @if ($allFuelExpenses)
                            <div class="input-group">
                                <input type="text" class="form-control" id="fuel_expenses" 
                                    name="fuel_expenses" value="{{ 'IDR ' . number_format($allFuelExpenses, 0, ',', '.') }}" readonly>
                                <a href="{{ url('/fuel-index', ['id' => Crypt::encrypt($asset->id_asset)]) }}" 
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Click to view details" class="btn btn-primary input-group-text">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        @else
                            <input type="text" class="form-control" id="fuel_expenses" 
                                name="fuel_expenses" value="{{ 'IDR ' . number_format($allFuelExpenses, 0, ',', '.') }}" readonly>
                        @endif
                    </div>
                    <div>
                        <div class="col-xl-6"></div>
                        <div class="col-xl-6 mb-3">
                            <label for="total_expenses" class="form-label"><h5>Total Expenses</h5></label>
                            <input type="text" class="form-control" id="total_expenses" 
                                name="total_expenses" value="{{ 'IDR ' . number_format($totalExpenses, 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2"></div>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    // remove whitespace
    const description = document.getElementById('description');
    const ux_description = document.getElementById('ux_description');
    const m_description = document.getElementById('m_description');
    const s_description = document.getElementById('s_description');
    const sp_description = document.getElementById('sp_description');
        
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

        if(expenseCategorySelect) {
            expenseCategorySelect.addEventListener('change', function() {
                var selectedValue = expenseCategorySelect.value;
                showForm(selectedValue);
            });
        }
    });

</script>
@endsection