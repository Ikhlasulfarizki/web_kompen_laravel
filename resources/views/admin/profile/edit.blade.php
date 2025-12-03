@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Profil Admin</h1>
        <a href="{{ route('admin.profile.show') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="username" class="block text-gray-700 font-semibold mb-2">Username <span class="text-red-500">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', Auth::user()->username) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('username') border-red-500 @enderror">
                @error('username')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('password_confirmation') border-red-500 @enderror">
                @error('password_confirmation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('admin.profile.show') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg text-center">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
