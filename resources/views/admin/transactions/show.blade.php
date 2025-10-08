@extends('layouts.admin')
@section('title', 'Detail Notice')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600 pb-3">
        Detail Notice
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-gray-700 dark:text-gray-300">
        <p><span class="font-semibold dark:text-gray-200">Kode:</span> {{ $transaction->code }}</p>
        <p><span class="font-semibold dark:text-gray-200">Stockist:</span> {{ $transaction->warehouse->name }}</p>
        <p><span class="font-semibold dark:text-gray-200">Tanggal:</span> {{ $transaction->updated_at->format('d-m-Y') }}</p>
        <p>
            <span class="font-semibold dark:text-gray-200">Status:</span>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @if($transaction->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                @elseif($transaction->status == 'approved') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                @elseif($transaction->status == 'rejected') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                {{ ucfirst($transaction->status) }}
            </span>
        </p>
        <p><span class="font-semibold dark:text-gray-200">Jasa Pengiriman:</span> {{ $transaction->jasa_pengiriman }}</p>
        <p><span class="font-semibold dark:text-gray-200">No resi:</span> {{ $transaction->resi_number }}</p>
        <p><span class="font-semibold dark:text-gray-200">Nama:</span> {{ $transaction->customer_name }}</p>
        <p><span class="font-semibold dark:text-gray-200">No Hp:</span> {{ $transaction->customer_phone }}</p>
        <p><span class="font-semibold dark:text-gray-200">Alamat:</span> {{ $transaction->customer_address }}</p>
        <p><span class="font-semibold dark:text-gray-200">Bukti:
        @if($transaction->image)
            <img src="{{ asset($transaction->image) }}"
                alt="Bukti Kirim"
                class="w-16 h-16 object-cover rounded cursor-pointer"
                onclick="openImageModal('{{ asset($transaction->image) }}')">
        @else
            <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada bukti upload</p>
        @endif
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
            <span class="absolute top-4 right-6 text-white text-3xl cursor-pointer" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" class="max-h-full max-w-full rounded shadow-lg">
        </div>
        </span></p>
        <p><span class="font-semibold dark:text-gray-200">Biaya Pengiriman:</span> {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</p>
    </div>

    {{-- Detail Items --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden mt-6">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            Detail Items
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
                    @forelse($transaction->items as $item)
                        <tr>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">{{ $item->product_code ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">{{ $item->product_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">{{ $item->category_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">{{ $item->subcategory_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-200">{{ $item->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400 dark:text-gray-500">Belum ada item</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.transactions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
            Kembali
        </a>
    </div>
</div>
<script>
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modalImg.src = src;
    modal.classList.remove('hidden');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}
</script>
@endsection