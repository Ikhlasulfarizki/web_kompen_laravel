@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Profil Dosen</h2>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p class="font-bold">Ada kesalahan:</p>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('dosen.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pribadi</h3>

                <div class="mb-4">
                    <label for="nama_dosen" class="block text-sm font-semibold text-gray-700 mb-2">Nama Dosen <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_dosen" name="nama_dosen" value="{{ old('nama_dosen', $dosen->nama_dosen) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('nama_dosen') border-red-500 @enderror">
                    @error('nama_dosen')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nip" class="block text-sm font-semibold text-gray-700 mb-2">NIP <span class="text-red-500">*</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $dosen->nip) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('nip') border-red-500 @enderror">
                    @error('nip')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nomor_hp" class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP</label>
                    <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp', $dosen->nomor_hp) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('nomor_hp') border-red-500 @enderror">
                    @error('nomor_hp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Program Studi</label>
                    <p class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">{{ $dosen->prodi->nama_prodi }}</p>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="mb-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>

                <div class="mb-4">
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <p class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg">{{ $user->username }}</p>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Section -->
            <div class="mb-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h3>
                <p class="text-gray-600 text-sm mb-4">Kosongkan field password jika tidak ingin mengubahnya</p>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                        placeholder="Minimum 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('dosen.profile.show') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
