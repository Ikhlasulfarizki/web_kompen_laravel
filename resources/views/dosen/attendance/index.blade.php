@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Absensi Task</h2>
            <p class="text-gray-600 mt-2">{{ $task->judul }}</p>
        </div>
        <a href="{{ route('dosen.attendance.report', $task->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-chart-bar"></i> Lihat Laporan
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Total Peserta</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $task->participants->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Sudah Check-in Hari Ini</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $task->participants->filter(function ($p) {
                    return $p->attendance()
                        ->whereDate('waktu_masuk', today())
                        ->exists();
                })->count() }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Belum Check-in</p>
            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $task->participants->filter(function ($p) {
                    return !$p->attendance()
                        ->whereDate('waktu_masuk', today())
                        ->exists();
                })->count() }}
            </p>
        </div>
    </div>

    <!-- Participants List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h3 class="font-semibold text-gray-800">Daftar Peserta</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">NPM</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-in</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-out</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($task->participants as $participant)
                        @php
                            $today_attendance = $participant->attendance()
                                ->whereDate('waktu_masuk', today())
                                ->first();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $participant->mahasiswa->nama }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $participant->mahasiswa->npm }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($today_attendance && $today_attendance->waktu_keluar)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-check-circle"></i> Selesai
                                    </span>
                                @elseif ($today_attendance && !$today_attendance->waktu_keluar)
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-hourglass-start"></i> Check-in
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-times-circle"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if ($today_attendance)
                                    {{ $today_attendance->waktu_masuk->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if ($today_attendance && $today_attendance->waktu_keluar)
                                    {{ $today_attendance->waktu_keluar->format('H:i') }}
                                    <br>
                                    <span class="text-xs text-blue-600">({{ number_format($today_attendance->durasi_jam, 2) }} jam)</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                @if (!$today_attendance)
                                    <form action="{{ route('dosen.attendance.check-in', $participant->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-arrow-right"></i> Check-in
                                        </button>
                                    </form>
                                @elseif ($today_attendance && !$today_attendance->waktu_keluar)
                                    <form action="{{ route('dosen.attendance.check-out', $today_attendance->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-arrow-left"></i> Check-out
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('dosen.attendance.delete', $today_attendance->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus catatan absensi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada peserta untuk task ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('dosen.tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left"></i> Kembali ke Task
        </a>
    </div>
</div>
@endsection
