@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Kompen/Task</h1>
        <a href="{{ route('admin.kompen.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold">
            <i class="fas fa-plus mr-2"></i> Tambah Kompen
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Judul</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Dosen</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Lokasi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kuota</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jam</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Peserta</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $task->judul }}</td>
                        <td class="px-6 py-3">{{ $task->dosen->nama ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $task->lokasi }}</td>
                        <td class="px-6 py-3">{{ $task->kuota }}</td>
                        <td class="px-6 py-3">{{ $task->jmlh_jam }}</td>
                        <td class="px-6 py-3">{{ $task->participants->count() }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('admin.kompen.edit', $task->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.kompen.destroy', $task->id) }}" method="POST" style="display:inline;">
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
                        <td colspan="7" class="px-6 py-3 text-center text-gray-500">Tidak ada data kompen</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
