@extends('layouts.base')
<!-- @section('title', 'Create New Asset') -->
@section('content')
<div class="row mt-4">
    <div class="col-12">
        @if(empty($asset))
        <div class="card bg-white-100 border-0 shadow">
            <div class="card-header flex-row flex-0">
                <form action="{{ url('store-asset') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Asset Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter a asset name..." 
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter a location..." 
                            value="{{ old('location') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price</label>
                        <input type="text" class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price" 
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
                            <option value="no activity">No Activity</option>
                            <option value="on progress">On Progress</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
        @else
        <div class="card bg-white-100 border-0 shadow">
            <div class="card-header flex-row flex-0">
                <form action="{{ url('update-asset') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $asset->id_asset }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Asset Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter a asset name..." 
                            value="{{ old('name', $asset->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter a location..." 
                            value="{{ old('location', $asset->location) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price</label>
                        <input type="text" class="form-control  @error('purchase_price') is-invalid @enderror" id="purchase_price" 
                            name="purchase_price" placeholder="Enter a purchase price..." value="{{ old('purchase_price', $asset->purchase_price) }}" required>
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
                            <option value="Cancelled" {{ old('status', $asset->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="On Progress" {{ old('status', $asset->status) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="Finished" {{ old('status', $asset->status) == 'Finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                    </div>
                    <button class="btn btn-secondary" type="submit">Update</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // remove whitespace
    const description = document.getElementById('description');
        
    if (description.value) {
        description.value = description.value.trim();
    }
</script>
@endsection