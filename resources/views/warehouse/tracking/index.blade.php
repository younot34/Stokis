@extends('layouts.warehouse')
@section('title', 'Track Resi')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b pb-3 border-gray-200 dark:border-gray-700">Track Resi</h1>

    <form action="{{ route('tracking.tracking') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1 font-medium">Kurir</label>
            <select name="courier" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                <option value="">-- Pilih Kurir --</option>
                <option value="jne">JNE</option>
                <option value="jnt">J&T</option>
                <option value="sicepat">SiCepat</option>
                <option value="pos-indonesia">POS Indonesia</option>
                <option value="tiki">TIKI</option>
                <option value="wahana">Wahana</option>
                <option value="anteraja">Anteraja</option>
                <option value="ninja">Ninja</option>
                <option value="spx">Shopee Express</option>
                <option value="lex">Lazada Express</option>
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">No Resi</label>
            <input type="text" name="waybill" placeholder="Masukkan nomor resi"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                       bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
        </div>

        <button type="submit"
            class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
            Track
        </button>
    </form>
</div>
@endsection

@push('scripts')
<!-- Tambahkan Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#courier').select2({
        placeholder: 'Pilih atau ketik nama kurir',
        tags: true, // bisa ketik manual
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush
