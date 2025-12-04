@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Laporan Absensi</h2>
            <p class="text-gray-600 mt-2">{{ $task->judul }}</p>
        </div>
        <a href="{{ route('dosen.attendance.index', $task->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Total Peserta</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $participants->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Total Absensi</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ collect($statistics)->sum('total_attendance') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Total Jam Hadir</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format(collect($statistics)->sum('total_duration'), 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Durasi Task</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ formatJam($task->jmlh_jam) }}</p>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b">
            <h3 class="font-semibold text-gray-800">Detail Absensi Peserta</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">NPM</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Jumlah Hadir</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Total Jam</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Persentase</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Riwayat</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($participants as $index => $participant)
                        @php
                            $stat = $statistics[$participant->id];
                            $attendance_percentage = $task->jmlh_jam > 0
                                ? round(($stat['total_duration'] / ($task->jmlh_jam / 60)) * 100, 2)
                                : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $participant->mahasiswa->nama }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $participant->mahasiswa->npm }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $stat['total_attendance'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold text-gray-800">{{ number_format($stat['total_duration'], 2) }}</span>
                                <span class="text-xs text-gray-500"> jam</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $bar_color = $attendance_percentage >= 80 ? 'bg-green-500' : ($attendance_percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="{{ $bar_color }} h-2 rounded-full" style="width: {{ min($attendance_percentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold">{{ $attendance_percentage }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($participant->attendance->count() > 0)
                                    <details class="cursor-pointer">
                                        <summary class="text-blue-600 hover:text-blue-700 font-semibold">
                                            Lihat ({{ $participant->attendance->count() }})
                                        </summary>
                                        <div class="mt-3 p-3 bg-gray-50 rounded border">
                                            @foreach ($participant->attendance->sortByDesc('waktu_masuk') as $att)
                                                <div class="text-xs text-gray-600 mb-2">
                                                    <div class="font-semibold">
                                                        {{ $att->waktu_masuk->format('d M Y H:i') }}
                                                        @if ($att->waktu_keluar)
                                                            - {{ $att->waktu_keluar->format('H:i') }}
                                                        @else
                                                            (masih aktif)
                                                        @endif
                                                    </div>
                                                    @if ($att->durasi_jam)
                                                        <div class="text-green-600">Durasi: {{ number_format($att->durasi_jam, 2) }} jam</div>
                                                    @endif
                                                    @if ($att->catatan)
                                                        <div class="text-gray-600">Catatan: {{ $att->catatan }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <span class="text-gray-500 text-xs">Belum ada absensi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada peserta
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Button -->
    <div class="mt-6 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
        <a href="{{ route('dosen.tasks.show', $task->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Kembali ke Task
        </a>
    </div>
</div>
@endsection
