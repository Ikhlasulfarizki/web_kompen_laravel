@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Task</h2>

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
        <form action="{{ route('dosen.tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">Judul Task <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $task->judul) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $task->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="lokasi" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $task->lokasi) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('lokasi') border-red-500 @enderror">
                @error('lokasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal_waktu" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_waktu" name="tanggal_waktu"
                    value="{{ old('tanggal_waktu', $task->tanggal_waktu->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('tanggal_waktu') border-red-500 @enderror">
                @error('tanggal_waktu')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="jam_mulai" class="block text-sm font-semibold text-gray-700 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', $task->jam_mulai) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('jam_mulai') border-red-500 @enderror">
                    @error('jam_mulai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jam_selesai" class="block text-sm font-semibold text-gray-700 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', $task->jam_selesai) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('jam_selesai') border-red-500 @enderror">
                    @error('jam_selesai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Task <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="jam" class="block text-xs font-medium text-gray-600 mb-1">Jam</label>
                        <input type="number" id="jam" name="jam"
                            value="{{ old('jam', intdiv($task->jmlh_jam, 60)) }}" required min="0" max="24"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('jam') border-red-500 @enderror">
                        @error('jam')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="menit" class="block text-xs font-medium text-gray-600 mb-1">Menit</label>
                        <input type="number" id="menit" name="menit"
                            value="{{ old('menit', $task->jmlh_jam % 60) }}" required min="0" max="59"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('menit') border-red-500 @enderror">
                        @error('menit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-gray-500 text-sm mt-2">Saat ini: {{ formatJam($task->jmlh_jam) }}</p>
            </div>

            <div class="mb-6">
                <label for="kuota" class="block text-sm font-semibold text-gray-700 mb-2">Kuota Peserta <span class="text-red-500">*</span></label>
                <input type="number" id="kuota" name="kuota" value="{{ old('kuota', $task->kuota) }}" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('kuota') border-red-500 @enderror">
                @error('kuota')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('dosen.tasks.show', $task->id) }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
