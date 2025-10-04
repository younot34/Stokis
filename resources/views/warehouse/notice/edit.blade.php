@extends('layouts.warehouse')
@section('title','Edit Notice')
@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Update Proses {{ $notice->code }}</h2>

    {{-- Form Edit Status & Jasa Kirim --}}
    <form action="{{ route('warehouse.notice.update', $notice->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Status</label>
                <select name="status" class="border w-full border-gray-400 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="diproses" {{ $notice->status=='diproses'?'selected':'' }}>diproses</option>
                    <option value="dikirim" {{ $notice->status=='dikirim'?'selected':'' }}>Dikirim</option>
                    <option value="selesai" {{ $notice->status=='selesai'?'selected':'' }}>Selesai</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Jasa Pengiriman</label>
                <select name="jasa_pengiriman" class="border w-full border-gray-400 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="Instan Gojek" {{ $notice->jasa_pengiriman=='Instan Gojek'?'selected':'' }}>Instan Gojek</option>
                    <option value="Instan Grab" {{ $notice->jasa_pengiriman=='Instan Grab'?'selected':'' }}>Instan Grab</option>
                    <option value="Jnt" {{ $notice->jasa_pengiriman=='Jnt'?'selected':'' }}>Jnt</option>
                    <option value="Jne" {{ $notice->jasa_pengiriman=='Jne'?'selected':'' }}>Jne</option>
                    <option value="Sicepat" {{ $notice->jasa_pengiriman=='Sicepat'?'selected':'' }}>Sicepat</option>
                    <option value="Ninja Express" {{ $notice->jasa_pengiriman=='Ninja Express'?'selected':'' }}>Ninja Express</option>
                    <option value="Tiki" {{ $notice->jasa_pengiriman=='Tiki'?'selected':'' }}>Tiki</option>
                    <option value="Pos" {{ $notice->jasa_pengiriman=='Pos'?'selected':'' }}>Pos</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Nama Customer</label>
                <input type="text" name="customer_name"
                    value="{{ old('customer_name', $notice->customer_name) }}"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">No HP</label>
                <input type="text" name="customer_phone"
                    value="{{ old('customer_phone', $notice->customer_phone) }}"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Alamat Lengkap</label>
            <textarea name="customer_address"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    rows="3" required>{{ old('customer_address', $notice->customer_address) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Nomor Resi</label>
                <input type="text" name="resi_number"
                    value="{{ old('resi_number', $notice->resi_number) }}"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Upload Foto</label>
                <input type="file" name="image"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                @if($notice->image)
                    <div class="mt-2">
                        <img src="{{ asset($notice->image) }}" alt="Foto" class="w-32 rounded shadow">
                    </div>
                @endif
            </div>
        </div>


    {{-- Tabel Detail Items --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden mt-6">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 px-6 py-4 border-b border-gray-200 dark:border-gray-700">Detail Items</h3>
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
                    @forelse($notice->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->product_code ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->product_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->category_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->subcategory_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ number_format($item->price,0,',','.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->note ?? '-' }}</td>
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
    <div class="flex gap-2 mt-4">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('warehouse.notice.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Batal</a>
    </div>
    </form>
</div>
@endsection
