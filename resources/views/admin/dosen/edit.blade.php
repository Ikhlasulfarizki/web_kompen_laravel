@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Dosen</h1>
        <a href="{{ route('admin.dosen.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="nip" class="block text-gray-700 font-semibold mb-2">NIP <span class="text-red-500">*</span></label>
                <input type="text" id="nip" name="nip" value="{{ old('nip', $dosen->nip) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nip') border-red-500 @enderror">
                @error('nip')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nama') border-red-500 @enderror">
                @error('nama')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="tgl_lahir" class="block text-gray-700 font-semibold mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', $dosen->tgl_lahir) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tgl_lahir') border-red-500 @enderror">
                @error('tgl_lahir')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('jenis_kelamin') border-red-500 @enderror">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="id_jurusan" class="block text-gray-700 font-semibold mb-2">Jurusan <span class="text-red-500">*</span></label>
                <select id="id_jurusan" name="id_jurusan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Jurusan</option>
                    @foreach($jurusan as $j)
                        <option value="{{ $j->id }}" {{ old('id_jurusan', $dosen->prodi->id_jurusan) == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="id_prodi" class="block text-gray-700 font-semibold mb-2">Program Studi <span class="text-red-500">*</span></label>
                <select id="id_prodi" name="id_prodi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('id_prodi') border-red-500 @enderror">
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodi as $p)
                        <option value="{{ $p->id }}" {{ old('id_prodi', $dosen->id_prodi) == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                    @endforeach
                </select>
                @error('id_prodi')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('admin.dosen.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg text-center">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('id_jurusan').addEventListener('change', function() {
            const jurusanId = this.value;
            const prodiSelect = document.getElementById('id_prodi');

            if (!jurusanId) {
                prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
                return;
            }

            // Fetch prodi berdasarkan jurusan yang dipilih
            fetch(`/admin/dosen/get-prodi/${jurusanId}`)
                .then(response => response.json())
                .then(data => {
                    prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
                    data.forEach(prodi => {
                        const option = document.createElement('option');
                        option.value = prodi.id;
                        option.textContent = prodi.nama_prodi;
                        prodiSelect.appendChild(option);
                    });

                    // Set nilai yang dipilih sebelumnya jika ada
                    const selectedProdi = '{{ old('id_prodi', $dosen->id_prodi) }}';
                    if (selectedProdi) {
                        prodiSelect.value = selectedProdi;
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Trigger change event saat halaman load
        if (document.getElementById('id_jurusan').value) {
            document.getElementById('id_jurusan').dispatchEvent(new Event('change'));
        }
    </script>
