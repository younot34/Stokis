@extends('layouts.admin')
@section('title', 'Tambah Deposit')

@section('content')
<h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">Tambah Deposit</h1>

<form action="{{ route('admin.deposits.store') }}" method="POST" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded shadow">
    @csrf
    <div>
        <label class="block mb-1 text-gray-700 dark:text-gray-200">Warehouse</label>
        <select name="warehouse_id" class="w-full border border-gray-300 dark:border-gray-600 px-3 py-2 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
            @endforeach
        </select>
        @error('warehouse_id') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block mb-1 text-gray-700 dark:text-gray-200">Nominal</label>
        <input type="text" name="nominal" id="nominal" placeholder="Nominal" class="w-full border border-gray-300 dark:border-gray-600 px-3 py-2 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
        @error('nominal') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>

@push('scripts')
<script>
    const nominalInput = document.getElementById('nominal');

    nominalInput.addEventListener('input', function(e) {
        // hapus semua karakter kecuali angka
        let value = e.target.value.replace(/\D/g, '');
        // format angka dengan titik ribuan
        e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

    // optional: saat submit, hapus titik agar bisa dikirim ke backend sebagai number
    document.querySelector('form').addEventListener('submit', function(e){
        nominalInput.value = nominalInput.value.replace(/\./g, '');
    });
</script>
@endpush
@endsection
