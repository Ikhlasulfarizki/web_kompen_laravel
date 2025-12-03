@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Tambah Mahasiswa Baru</h1>
        <a href="{{ route('admin.mahasiswa.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="npm" class="block text-gray-700 font-semibold mb-2">NPM <span class="text-red-500">*</span></label>
                <input type="text" id="npm" name="npm" value="{{ old('npm') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('npm') border-red-500 @enderror">
                @error('npm')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nama') border-red-500 @enderror">
                @error('nama')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tgl_lahir" class="block text-gray-700 font-semibold mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tgl_lahir') border-red-500 @enderror">
                @error('tgl_lahir')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('jenis_kelamin') border-red-500 @enderror">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="id_jurusan" class="block text-gray-700 font-semibold mb-2">Jurusan <span class="text-red-500">*</span></label>
                <select id="id_jurusan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Jurusan</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="id_prodi" class="block text-gray-700 font-semibold mb-2">Program Studi <span class="text-red-500">*</span></label>
                <select id="id_prodi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Program Studi</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="id_kelas" class="block text-gray-700 font-semibold mb-2">Kelas <span class="text-red-500">*</span></label>
                <select id="id_kelas" name="id_kelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('id_kelas') border-red-500 @enderror">
                    <option value="">Pilih Kelas</option>
                </select>
                @error('id_kelas')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg text-center">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('id_jurusan').addEventListener('change', function() {
        const jurusanId = this.value;
        const prodiSelect = document.getElementById('id_prodi');
        prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';

        if (jurusanId) {
            fetch(`/admin/get-prodi/${jurusanId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(prodi => {
                        const option = document.createElement('option');
                        option.value = prodi.id;
                        option.textContent = prodi.nama_prodi;
                        prodiSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching prodi:', error));
        }
    });

    document.getElementById('id_prodi').addEventListener('change', function() {
        const prodiId = this.value;
        const kelasSelect = document.getElementById('id_kelas');
        kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';

        if (prodiId) {
            fetch(`/admin/get-kelas/${prodiId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kelas => {
                        const option = document.createElement('option');
                        option.value = kelas.id;
                        option.textContent = kelas.nama_kelas;
                        kelasSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching kelas:', error));
        }
    });
</script>
@endsection
