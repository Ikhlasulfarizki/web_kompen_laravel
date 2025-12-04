@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Profil Dosen</h2>
        <a href="{{ route('dosen.profile.edit') }}" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            <i class="fas fa-edit mr-2"></i> Edit Profil
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pribadi</h3>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Dosen</label>
                    <p class="text-lg text-gray-800">{{ $dosen->nama_dosen }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">NIP</label>
                    <p class="text-lg text-gray-800">{{ $dosen->nip }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nomor HP</label>
                    <p class="text-lg text-gray-800">{{ $dosen->nomor_hp ?? '-' }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Program Studi</label>
                    <p class="text-lg text-gray-800">{{ $dosen->prodi->nama_prodi }}</p>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Akun</h3>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                    <p class="text-lg text-gray-800">{{ $user->username }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <p class="text-lg text-gray-800">{{ $user->email }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Role</label>
                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                        Dosen
                    </span>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Status Akun</label>
                    <p class="text-lg text-gray-800">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            Aktif
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Lainnya</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Task</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ \App\Models\Task::where('id_dosen', $dosen->id)->count() }}
                    </p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600 mb-1">Peserta Aktif</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ \App\Models\Participant::whereHas('task', function($q) use($dosen) { $q->where('id_dosen', $dosen->id); })->count() }}
                    </p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-600 mb-1">Task Selesai</p>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ \App\Models\Participant::whereHas('task', function($q) use($dosen) { $q->where('id_dosen', $dosen->id); })->where('status_penyelesaian', 'Selesai')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('dosen.dashboard') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
