@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Mahasiswa</h1>
        <a href="{{ route('admin.mahasiswa.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">
            <i class="fas fa-plus mr-2"></i> Tambah Mahasiswa
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NPM</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jenis Kelamin</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Prodi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jurusan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa as $mhs)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $mhs->npm }}</td>
                        <td class="px-6 py-3">{{ $mhs->nama }}</td>
                        <td class="px-6 py-3">{{ $mhs->jenis_kelamin }}</td>
                        <td class="px-6 py-3">{{ $mhs->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $mhs->kelas->prodi->nama_prodi ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $mhs->kelas->prodi->jurusan->nama_jurusan ?? '-' }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-3 text-center text-gray-500">Tidak ada data mahasiswa</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
