@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Daftar Task Saya</h2>
        <a href="{{ route('dosen.tasks.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i> Buat Task Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Search & Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('dosen.tasks.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Search Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search"></i> Cari Task
                    </label>
                    <input type="text" name="search" placeholder="Judul, lokasi, atau deskripsi..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar"></i> Dari Tanggal
                    </label>
                    <input type="date" name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar"></i> Sampai Tanggal
                    </label>
                    <input type="date" name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-filter"></i> Status
                    </label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua</option>
                        <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="past" {{ request('status') === 'past' ? 'selected' : '' }}>Sudah Lewat</option>
                    </select>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sort"></i> Urutkan Berdasarkan
                    </label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="tanggal_waktu" {{ request('sort') === 'tanggal_waktu' ? 'selected' : '' }}>Tanggal</option>
                        <option value="judul" {{ request('sort') === 'judul' ? 'selected' : '' }}>Judul</option>
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Dibuat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-arrow-up-down"></i> Urutan
                    </label>
                    <select name="direction" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Terbaru</option>
                        <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('dosen.tasks.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    @if ($tasks->isEmpty())
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
            <p>Belum ada task. <a href="{{ route('dosen.tasks.create') }}" class="underline font-bold">Buat task pertama Anda</a></p>
        </div>
    @else
        <!-- Results Counter -->
        <div class="mb-4 text-gray-600">
            <p>Menampilkan <span class="font-semibold">{{ $tasks->count() }}</span> dari <span class="font-semibold">{{ $tasks->total() }}</span> task</p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Judul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Lokasi</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Durasi</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kuota</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Peserta</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($tasks as $task)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $task->judul }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $task->tanggal_waktu->format('d M Y') }}
                                @if ($task->tanggal_waktu < now())
                                    <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Sudah Lewat</span>
                                @else
                                    <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Akan Datang</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $task->lokasi }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ formatJam($task->jmlh_jam) }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $task->kuota }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $task->participants->count() }}/{{ $task->kuota }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2 flex">
                                <a href="{{ route('dosen.tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dosen.tasks.edit', $task->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dosen.tasks.destroy', $task->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus task ini? Partisipan akan otomatis terlepas.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection
