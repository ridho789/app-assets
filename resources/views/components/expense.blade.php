@extends('layouts.base')
@section('title', ucwords(strtolower($formattedCategory)))
@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">{{ ucwords(strtolower($formattedCategory)) }}</h1>
            <p class="mb-0">
                The following is a list of {{ strtolower($formattedCategory) }} of asset
                @if(isset($asset))
                <a href="{{ url('asset-edit', ['id' => Crypt::encrypt($asset->id_asset)]) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Back to asset form">
                    <b>{{ $asset->name }}</b> <i class="fa-solid fa-rotate-left"></i>
                </a>
                @else
                    <b>No asset found</b>
                @endif
            </p>
        </div>
    </div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-edit-{{ strtolower(str_replace(' ', '-', $category)) }}" tabindex="-1" role="dialog" 
    aria-labelledby="modal-edit-{{ strtolower(str_replace(' ', '-', $category)) }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">{{ ucwords(strtolower($formattedCategory)) }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/update-' . strtolower(str_replace(' ', '-', $category)) ) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div>
                        <input type="hidden" name="id" id="id">
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
                            <input type="text" class="form-control  @error('price') is-invalid @enderror" id="price" name="price" placeholder="Enter a price..." value="{{ old('price') }}" required>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Description</label>
                            <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="Enter a description..." required>
                            {{ old('desc') }}
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
            <table id="table-expense" class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">No.</th>
                        <th class="border-0">Name</th>
                        <th class="border-0">Date</th>
                        <th class="border-0">Price</th>
                        <th class="border-0">Description</th>
                        <th class="border-0 text-end rounded-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($expenses) > 0)
                    @foreach($expenses as $e)
                    <tr data-id="{{ $e->id_expense }}">
                        <td>{{ $loop->iteration }}.</td>
                        <td class="name">{{ $e->name }}</td>
                        <td class="date">{{ date('j M Y', strtotime($e->date)) }}</td>
                        <td class="price">{{ $e->price }}</td>
                        <td class="description">{{ $e->desc ?? '-' }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-icon-only btn btn-primary btn-sm edit-button" data-bs-toggle="modal" 
                                    data-bs-target="#modal-edit-{{ strtolower(str_replace(' ', '-', $category)) }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ url('/delete-expense-' . Crypt::encrypt($e->id_expense)) }}" 
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

    // Fungsi untuk memangkas spasi pada input #desc jika ada
    document.addEventListener('DOMContentLoaded', function() {
        const desc = document.getElementById('desc');
        if (desc && desc.value) {
            desc.value = desc.value.trim();
        }

        // Panggilan awal untuk memformat harga dan pemotongan teks
        formatPrices();
        applyTextTruncation();
    });

    // Fungsi untuk format mata uang IDR
    function formatCurrency(num) {
        num = num.toString().replace(/[^\d-]/g, ''); // Hapus karakter non-digit
        num = num.replace(/-+/g, (match, offset) => offset > 0 ? "" : "-"); // Menjaga tanda negatif di depan

        let isNegative = num.startsWith("-");
        if (isNegative) {
            num = num.slice(1);
        }

        let formattedNum = "IDR " + Math.abs(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return isNegative ? "-" + formattedNum : formattedNum;
    }

    function truncateText(cell, batasKarakter) {
        // Ambil teks asli dari atribut data jika tersedia
        var originalText = cell.getAttribute('data-original-text') || cell.textContent.trim();
        
        // Simpan teks asli di atribut data untuk digunakan di kemudian hari
        cell.setAttribute('data-original-text', originalText);

        // Mulai pemotongan teks
        var text = originalText; // Gunakan teks asli

        // Hapus konten sel sebelum pemotongan
        cell.innerHTML = '';

        while (text.length > batasKarakter) {
            // Potong teks sampai batas karakter yang ditentukan
            var truncatedText = text.slice(0, batasKarakter);
            // Cari batas kata terdekat
            var lastSpaceIndex = truncatedText.lastIndexOf(' ');

            // Jika ada spasi, potong sampai batas kata terdekat
            if (lastSpaceIndex > 0) {
                truncatedText = truncatedText.slice(0, lastSpaceIndex);
            }

            var remainingText = text.slice(truncatedText.length).trim();
            // Setel konten dengan teks yang dipotong dan sisanya di bawah
            cell.innerHTML += truncatedText + '<br>'; // Tambah pemotongan baru
            text = remainingText; // Update text untuk pemeriksaan selanjutnya
        }

        // Tambahkan sisa teks jika ada
        if (text.length > 0) {
            cell.innerHTML += text;
        }
    }

    // Fungsi untuk mengatur pemotongan teks pada kolom .description dan .name
    function applyTextTruncation() {
        var batasKarakterDescription = 55;
        var batasKarakterName = 55;

        var descriptionCells = document.querySelectorAll('td.description');
        descriptionCells.forEach(function(cell) {
            truncateText(cell, batasKarakterDescription);
        });

        var nameCells = document.querySelectorAll('td.name');
        nameCells.forEach(function(cell) {
            truncateText(cell, batasKarakterName);
        });
    }

    // Fungsi untuk memformat harga
    function formatPrices() {
        let Prices = document.querySelectorAll(".price");
        Prices.forEach(function(Price) {
            let price = Price.textContent;
            Price.textContent = formatCurrency(price);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tableData = $('#table-expense').DataTable({
            columnDefs: [
                { 
                    targets: [0, 5], 
                    orderable: false,
                    "defaultContent": "-"
                }
            ]
        });

        // Mengatur ulang pemformatan saat DataTables selesai di-draw
        tableData.on('draw', function() {
            applyTextTruncation();
            formatPrices();
        });

        // Event delegation untuk tombol edit
        document.querySelector('#table-expense tbody').addEventListener('click', function(event) {
            if (event.target.closest('.edit-button')) {
                event.preventDefault();
                var row = event.target.closest("tr");
                var id = row.getAttribute("data-id");
                var name = row.querySelector(".name").textContent;
                var date = row.querySelector(".date").textContent.trim();
                var price = row.querySelector(".price").textContent;
                var description = row.querySelector(".description").textContent;

                const dateFormat = moment(date, 'DD MMMM YYYY').format('MM/D/Y');
                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("name").value = name;
                document.getElementById("date").value = dateFormat;
                document.getElementById("price").value = price;
                document.getElementById("desc").value = description;
            }
        });

        // Event delegation untuk input format harga
        document.querySelector('#table-expense tbody').addEventListener('input', function(event) {
            if (event.target.matches('#price')) {
                event.target.value = formatCurrency(event.target.value);
            }
        });

        const priceInput = document.getElementById("price");
        if (priceInput) {
            priceInput.addEventListener('input', function() {
                this.value = formatCurrency(this.value);
            });
        }
    });
</script>
@endsection