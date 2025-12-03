@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Tambah Kompen Baru</h1>
        <a href="{{ route('admin.kompen.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
        <form action="{{ route('admin.kompen.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="judul" class="block text-gray-700 font-semibold mb-2">Judul <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="id_dosen" class="block text-gray-700 font-semibold mb-2">Dosen <span class="text-red-500">*</span></label>
                    <select id="id_dosen" name="id_dosen" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('id_dosen') border-red-500 @enderror">
                        <option value="">Pilih Dosen</option>
                        @foreach($dosen as $d)
                            <option value="{{ $d->id }}" {{ old('id_dosen') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                        @endforeach
                    </select>
                    @error('id_dosen')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="lokasi" class="block text-gray-700 font-semibold mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('lokasi') border-red-500 @enderror">
                    @error('lokasi')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="kuota" class="block text-gray-700 font-semibold mb-2">Kuota <span class="text-red-500">*</span></label>
                    <input type="number" id="kuota" name="kuota" value="{{ old('kuota') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('kuota') border-red-500 @enderror">
                    @error('kuota')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="jmlh_jam" class="block text-gray-700 font-semibold mb-2">Jumlah Jam <span class="text-red-500">*</span></label>
                    <input type="number" id="jmlh_jam" name="jmlh_jam" value="{{ old('jmlh_jam') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('jmlh_jam') border-red-500 @enderror">
                    @error('jmlh_jam')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_waktu" class="block text-gray-700 font-semibold mb-2">Tanggal & Waktu <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="tanggal_waktu" name="tanggal_waktu" value="{{ old('tanggal_waktu') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tanggal_waktu') border-red-500 @enderror">
                    @error('tanggal_waktu')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="jam_mulai" class="block text-gray-700 font-semibold mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('jam_mulai') border-red-500 @enderror">
                    @error('jam_mulai')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="jam_selesai" class="block text-gray-700 font-semibold mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('jam_selesai') border-red-500 @enderror">
                    @error('jam_selesai')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="deskripsi" class="block text-gray-700 font-semibold mb-2">Deskripsi <span class="text-red-500">*</span></label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('admin.kompen.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg text-center">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
