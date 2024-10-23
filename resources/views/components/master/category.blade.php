@extends('layouts.base')
<!-- @section('title', 'Category Expenses') -->
@section('content')
<style>
    .create-category-link:hover {
        color: red;
        text-decoration: underline;
    }
</style>

<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Category Expenses</h1>
            <p class="mb-0">
                The following is a list of category expense and 
                click this to: <a href="#" class="create-category-link" data-bs-toggle="modal" data-bs-target="#modal-category"><u>create a new category expense</u></a>
            </p>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="modal-category" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Create Category Expense</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('category-store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="form_category">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter a category..." 
                            oninput="this.value = this.value.toUpperCase()" value="{{ old('name') }}" required>
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
<!-- End of Modal Content -->

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit-category" tabindex="-1" role="dialog" aria-labelledby="modal-edit-category" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Edit Category Expense</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('category-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="form_category">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter a category..." 
                            oninput="this.value = this.value.toUpperCase()" value="{{ old('name') }}" required>
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
                        <th class="border-0">Category</th>
                        <th class="border-0 text-end rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($category) > 0)
                    @foreach($category as $c)
                    <tr data-id="{{ $c->id_category }}"
                        data-name="{{ $c->name }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="category-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">{{ $c->name }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-icon-only btn btn-primary btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#modal-edit-category">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <!-- <a href="{{ url('category-delete/' . $c->id_category) }}" 
                                    class="btn btn-icon-only btn btn-danger btn-sm delete-button ms-2" onclick="return confirmDelete()"><i class="fa fa-trash"></i>
                                </a> -->
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

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var name = row.getAttribute("data-name");

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("name").value = name;
            });
        });
    });
</script>
@endsection