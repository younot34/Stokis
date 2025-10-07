@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
    <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
    <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">
        {{ $exception->getMessage() ?? 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
    </p>
    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Kembali ke halaman login</a>
</div>
@endsection
