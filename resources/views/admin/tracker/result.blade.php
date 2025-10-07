@extends('layouts.admin')
@section('title', 'Hasil Track Resi')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b pb-3 border-gray-200 dark:border-gray-700">
        Hasil Pelacakan
    </h1>

    @if(!isset($data['status']) || $data['status'] !== 200)
        <p class="text-red-500 font-medium">Terjadi kesalahan: {{ $data['message'] ?? 'Tidak dapat mengambil data' }}</p>
    @else
        <div class="space-y-2">
            <p><strong>Kurir:</strong> {{ strtoupper($data['data']['summary']['courier'] ?? '-') }}</p>
            <p><strong>No Resi:</strong> {{ $data['data']['summary']['awb'] ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $data['data']['summary']['status'] ?? '-' }}</p>
        </div>

        <h3 class="mt-4 font-semibold">Riwayat Pengiriman:</h3>
        <ul class="mt-2 space-y-1 text-sm">
            @foreach($data['data']['history'] ?? [] as $h)
                <li class="border-b border-gray-200 dark:border-gray-700 py-2">
                    <div><strong>{{ $h['date'] }}</strong> — {{ $h['desc'] }}</div>
                    <div class="text-gray-500 dark:text-gray-400">{{ $h['location'] }}</div>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('tracker.index') }}"
       class="inline-block mt-4 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        ← Kembali
    </a>
</div>
@endsection