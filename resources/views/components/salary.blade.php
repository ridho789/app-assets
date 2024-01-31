@extends('layouts.base')
<!-- @section('title', 'Salary Expenses') -->
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Salary Expenses</h1>
            <p class="mb-0">
                The following is a list of salary expense of asset
                <a href="{{ url('asset-edit', ['id' => Crypt::encrypt($asset->id_asset)]) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Back to asset form">
                    <b>{{ $asset->name }}</b> <i class="fa-solid fa-rotate-left"></i>
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-edit-salary" tabindex="-1" role="dialog" aria-labelledby="modal-edit-salary" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Salary Expenses</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('salary-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="form_salary">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="s_period" class="form-label">Period</label>
                            <input type="text" class="form-control" id="s_period" name="s_period" placeholder="Enter a period..." value="{{ old('s_period') }}" required>
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
                                <input data-datepicker="" class="form-control" id="s_date" name="s_date" type="text" placeholder="dd/mm/yyyy" value="{{ old('s_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="s_amount_paid" class="form-label">Amount Paid</label>
                            <input type="number" class="form-control  @error('s_amount_paid') is-invalid @enderror" id="s_amount_paid" name="s_amount_paid" placeholder="Enter the amount paid..." value="{{ old('s_amount_paid') }}" required>
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

@if (count($salary) > 0)
<div class="row justify-content-between align-items-center mb-4">
    <div class="col-9 col-lg-8 d-md-flex">
        <form action="{{ url('salary-search', ['id' => Crypt::encrypt($asset->id_asset)]) }}" method="GET">
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
                        <th class="border-0">Period</th>
                        <th class="border-0">Date</th>
                        <th class="border-0">Amount Paid</th>
                        <th class="border-0">Description</th>
                        <th class="border-0 rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($salary) > 0)
                    @foreach($salary as $s)
                    <tr data-id="{{ $s->id_salary }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="salary-period" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $s->period }}</td>
                        <td class="salary-date">{{ date('j F Y', strtotime($s->date)) }}</td>
                        <td class="salary-amount-paid">{{ 'IDR ' . number_format($s->amount_paid ?? 0, 0, ',', '.') }}</td>
                        <td class="salary-description" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $s->description }}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-icon-only btn btn-primary btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#modal-edit-salary">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ url('salary-delete/' . $s->id_salary) }}" 
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
    
    const s_description = document.getElementById('s_description');
    if (s_description && s_description.value) {
        s_description.value = s_description.value.trim();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var name = row.querySelector(".salary-period").textContent;
                var date = row.querySelector(".salary-date").textContent.trim();
                var amount_paid = row.querySelector(".salary-amount-paid").textContent.replace(/[^\d]/g, '');
                var description = row.querySelector(".salary-description").textContent;

                const dateFormat = moment(date, 'DD MMMM YYYY').format('MM/D/Y');
                const priceConvert = parseFloat(amount_paid);

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("s_period").value = name;
                document.getElementById("s_date").value = dateFormat;
                document.getElementById("s_amount_paid").value = amount_paid;
                document.getElementById("s_description").value = description;
            });
        });
    });
</script>
@endsection