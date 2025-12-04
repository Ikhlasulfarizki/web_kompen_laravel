@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Selamat Datang, {{ $dosen->nama }}!</h1>
        <p class="text-gray-600 mt-2">Program Studi: {{ $dosen->prodi->nama_prodi ?? '-' }}</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Tasks -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Task</p>
                    <p class="text-4xl font-bold mt-2">{{ $totalTasks }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 p-4 rounded-full">
                    <i class="fas fa-tasks text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('dosen.tasks.index') }}" class="mt-4 inline-block text-blue-100 hover:text-white text-sm">
                Lihat semua →
            </a>
        </div>

        <!-- Pending Participants -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Menunggu Verifikasi</p>
                    <p class="text-4xl font-bold mt-2">{{ $pendingParticipants }}</p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 p-4 rounded-full">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
            <p class="mt-4 text-yellow-100 text-sm">Peserta yang perlu di-acc</p>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Tugas Selesai</p>
                    <p class="text-4xl font-bold mt-2">{{ $completedTasks }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 p-4 rounded-full">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
            <p class="mt-4 text-green-100 text-sm">Total peserta yang selesai</p>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Tasks -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i>
                    Task yang Akan Datang
                </h2>
            </div>

            <div class="p-6">
                @if ($upcomingTasks->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p>Tidak ada task yang akan datang</p>
                        <a href="{{ route('dosen.tasks.create') }}" class="text-blue-600 hover:text-blue-700 mt-2 inline-block">
                            Buat task baru →
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($upcomingTasks as $task)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-gray-800">{{ $task->judul }}</h3>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                        {{ $task->participants->count() }}/{{ $task->kuota }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-map-pin"></i> {{ $task->lokasi }}
                                </p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">
                                        <i class="fas fa-calendar"></i> {{ $task->tanggal_waktu->format('d M Y') }}
                                    </span>
                                    <a href="{{ route('dosen.tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-700">
                                        Lihat detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Participants List -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-user-clock"></i>
                    Peserta Menunggu Verifikasi
                </h2>
            </div>

            <div class="p-6">
                @if ($recentPending->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-check text-4xl mb-4 text-green-500"></i>
                        <p>Semua peserta sudah diverifikasi</p>
                    </div>
                @else
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach ($recentPending as $participant)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $participant->mahasiswa->nama }}</h3>
                                        <p class="text-xs text-gray-500">{{ $participant->mahasiswa->npm }}</p>
                                    </div>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">
                                        {{ $participant->task->judul }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ $participant->mahasiswa->kelas->prodi->nama_prodi ?? '-' }} |
                                    {{ $participant->mahasiswa->kelas->nama_kelas ?? '-' }}
                                </p>
                                <div class="flex gap-2">
                                    <form action="{{ route('dosen.participants.accept', $participant->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-xs py-2 rounded">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('dosen.participants.reject', $participant->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white text-xs py-2 rounded">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Completed History -->
    <div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-history"></i>
                Riwayat Tugas Selesai (5 Terbaru)
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">NPM</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Task</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Durasi</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kelas</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($completedHistory as $history)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $history->mahasiswa->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $history->mahasiswa->npm }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $history->task->judul }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                    {{ formatJam($history->task->jmlh_jam) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $history->mahasiswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $history->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada tugas yang selesai
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 flex gap-4 justify-center">
        <a href="{{ route('dosen.tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i> Buat Task Baru
        </a>
        <a href="{{ route('dosen.tasks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-list"></i> Lihat Semua Task
        </a>
    </div>
</div>
@endsection
