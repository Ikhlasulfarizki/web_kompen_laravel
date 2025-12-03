@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Program Studi</h1>
        <a href="{{ route('admin.prodi.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
            <i class="fas fa-plus mr-2"></i> Tambah Prodi
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Program Studi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jurusan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prodi as $key => $p)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $key + 1 }}</td>
                        <td class="px-6 py-3">{{ $p->nama_prodi }}</td>
                        <td class="px-6 py-3">{{ $p->jurusan->nama_jurusan }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('admin.prodi.edit', $p->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.prodi.destroy', $p->id) }}" method="POST" style="display:inline;">
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
                        <td colspan="4" class="px-6 py-3 text-center text-gray-500">Tidak ada data program studi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
