@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Data Kelas</h1>
        <a href="{{ route('admin.kelas.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i> Tambah Kelas
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ $message }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-200 border-b-2 border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">No</th>
                    <th class="px-6 py-3 text-left font-semibold">Nama Kelas</th>
                    <th class="px-6 py-3 text-left font-semibold">Program Studi</th>
                    <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($kelas->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @else
                    @foreach ($kelas as $k)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $k->nama_kelas }}</td>
                            <td class="px-6 py-4">{{ $k->prodi->nama_prodi ?? '-' }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('admin.kelas.edit', $k->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Apakah Anda yakin?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
