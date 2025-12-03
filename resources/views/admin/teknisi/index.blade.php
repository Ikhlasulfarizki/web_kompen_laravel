@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Teknisi</h1>
        <a href="{{ route('admin.teknisi.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
            <i class="fas fa-plus mr-2"></i> Tambah Teknisi
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NIP</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal Lahir</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jenis Kelamin</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teknisi as $t)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $t->nip }}</td>
                        <td class="px-6 py-3">{{ $t->nama }}</td>
                        <td class="px-6 py-3">{{ date('d-m-Y', strtotime($t->tgl_lahir)) }}</td>
                        <td class="px-6 py-3">{{ $t->jenis_kelamin }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('admin.teknisi.edit', $t->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.teknisi.destroy', $t->id) }}" method="POST" style="display:inline;">
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
                        <td colspan="5" class="px-6 py-3 text-center text-gray-500">Tidak ada data teknisi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
