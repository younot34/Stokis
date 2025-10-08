@extends('layouts.admin')
@section('title', 'Notices Warehouse '.$warehouse->name)

@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-4">📄 Notices {{ $warehouse->name }}</h2>
        <a href="{{ route('admin.deposits.index') }}" class="text-sm text-gray-300 hover:underline mb-4 inline-block">← Kembali</a>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Bukti Pengiriman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Biaya Pengiriman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
                    @forelse($notices as $notice)
                    <tr class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $notice->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($notice->image)
                                <img
                                src="{{ asset($notice->image) }}"
                                    alt="image"
                                    class="w-16 h-16 object-cover rounded cursor-pointer"
                                    onclick="openImageModal('{{ asset($notice->image) }}')">
                                    @else
                                    -
                                    @endif
                                    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
                                        <span class="absolute top-4 right-6 text-white text-3xl cursor-pointer" onclick="closeImageModal()">&times;</span>
                                        <img id="modalImage" src="" class="max-h-full max-w-full rounded shadow-lg">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($notice->shipping_cost ?? 0,0,',','.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $notice->updated_at->format('d-m-Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada notice</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
