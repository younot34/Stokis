@extends('layouts.warehouse')
@section('title','Stok stokis')

@section('content')
<h2 class="text-2xl font-bold mb-5 text-gray-900 dark:text-gray-100">Stok stokis</h2>
{{-- Filter berdasarkan kode dan tipe produk --}}
<div class="flex flex-col sm:flex-row justify-between items-center mb-4">
    <input type="text" id="filterInput"
           placeholder="Cari berdasarkan kode barang..."
           class="w-full sm:w-1/3 px-4 py-2 border rounded-lg text-gray-700 dark:text-gray-300
                  bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-2 sm:mb-0">

    <select id="filterType"
            class="px-4 py-2 border rounded-lg text-gray-700 dark:text-gray-300
                   bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600">
        <option value="all">Semua Barang</option>
        <option value="normal">Barang Normal</option>
        <option value="discount">Barang Diskon</option>
    </select>
</div>
<div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="stockTable">
        <thead class="bg-gray-200 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subkategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah Stok</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Harga</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
            @forelse($allItems as $item)
                @if($item->quantity_approved > 0)
                @php
                    $harga = $item->final_price ?? $item->price;
                    $totalHarga = $harga * $item->quantity_approved;
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    data-type="{{ isset($item->final_price) ? 'discount' : 'normal' }}">
                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-100">
                        {{ $item->product->code }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-100">
                        {{ $item->product->name }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        {{ $item->product->parentCategory->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        {{ $item->product->category->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        Rp {{ number_format($harga, 0, ',', '.') }}
                        @if(isset($item->final_price))
                            <span class="ml-2 text-xs text-green-600 dark:text-green-400">(Diskon)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        {{ $item->quantity_approved }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">
                        Belum ada stok di stokis ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Filter Script --}}
<script>
    document.getElementById('filterInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#stockTable tbody tr');

        rows.forEach(row => {
            let kode = row.cells[0].innerText.toLowerCase();
            row.style.display = kode.includes(filter) ? '' : 'none';
        });
    });
    const filterInput = document.getElementById('filterInput');
    const filterType = document.getElementById('filterType');
    const rows = document.querySelectorAll('#stockTable tbody tr');

    function applyFilters() {
        let search = filterInput.value.toLowerCase();
        let type = filterType.value;

        rows.forEach(row => {
            let kode = row.cells[0].innerText.toLowerCase();
            let rowType = row.getAttribute('data-type');

            let matchSearch = kode.includes(search);
            let matchType = (type === 'all') || (type === rowType);

            row.style.display = (matchSearch && matchType) ? '' : 'none';
        });
    }

    filterInput.addEventListener('keyup', applyFilters);
    filterType.addEventListener('change', applyFilters);
</script>
@endsection
