@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Jurusan</h1>
        <a href="{{ route('admin.jurusan.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
        <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="nama_jurusan" class="block text-gray-700 font-semibold mb-2">Nama Jurusan <span class="text-red-500">*</span></label>
                <input type="text" id="nama_jurusan" name="nama_jurusan" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nama_jurusan') border-red-500 @enderror">
                @error('nama_jurusan')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('admin.jurusan.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg text-center">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
