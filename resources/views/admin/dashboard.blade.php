@extends('layouts.app')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold mb-8">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card Total Mahasiswa -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Mahasiswa</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalMahasiswa }}</p>
                </div>
                <div class="text-4xl text-blue-200">👥</div>
            </div>
        </div>

        <!-- Card Total Dosen -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Dosen</p>
                    <p class="text-3xl font-bold text-green-600">{{ $totalDosen }}</p>
                </div>
                <div class="text-4xl text-green-200">👨‍🏫</div>
            </div>
        </div>

        <!-- Card Total Task -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Task/Kompen</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalTask }}</p>
                </div>
                <div class="text-4xl text-purple-200">📋</div>
            </div>
        </div>

        <!-- Card Total Participants -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Peserta</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $totalParticipant }}</p>
                </div>
                <div class="text-4xl text-orange-200">📝</div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Menu Utama</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.mahasiswa.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition">
                Kelola Mahasiswa
            </a>
            <a href="{{ route('admin.dosen.index') }}" class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition">
                Kelola Dosen
            </a>
            <a href="{{ route('admin.kompen.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition">
                Kelola Kompen
            </a>
            <a href="{{ route('admin.profile.show') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition">
                Profil Saya
            </a>
        </div>
    </div>
</div>
@endsection
