@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">{{ $task->judul }}</h2>
            <p class="text-gray-600 mt-1">
                <i class="fas fa-calendar"></i> {{ $task->tanggal_waktu->format('d M Y') }} |
                <i class="fas fa-map-pin"></i> {{ $task->lokasi }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dosen.tasks.edit', $task->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('dosen.tasks.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Task Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Task</h3>
            <table class="w-full text-sm">
                <tr class="border-b">
                    <td class="py-2 font-semibold text-gray-700">Deskripsi</td>
                    <td class="py-2 text-gray-600">{{ $task->deskripsi ?? '-' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold text-gray-700">Waktu</td>
                    <td class="py-2 text-gray-600">{{ $task->jam_mulai }} - {{ $task->jam_selesai }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold text-gray-700">Durasi</td>
                    <td class="py-2 text-gray-600">{{ formatJam($task->jmlh_jam) }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold text-gray-700">Kuota</td>
                    <td class="py-2 text-gray-600">{{ $task->kuota }} peserta</td>
                </tr>
            </table>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Peserta</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $task->participants->count() }}</p>
                    <p class="text-gray-600 text-sm">Total Pendaftar</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $task->participants->where('status_acc', 'Diterima')->count() }}</p>
                    <p class="text-gray-600 text-sm">Diterima</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-red-600">{{ $task->participants->where('status_acc', 'Ditolak')->count() }}</p>
                    <p class="text-gray-600 text-sm">Ditolak</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $task->participants->whereNull('status_acc')->count() }}</p>
                    <p class="text-gray-600 text-sm">Menunggu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants List -->
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Daftar Peserta</h3>

    @if ($participants->isEmpty())
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
            <p>Belum ada peserta untuk task ini.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">NPM</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Kelas</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Program Studi</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Jurusan</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Status Verifikasi</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Status Penyelesaian</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($participants as $participant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $participant->mahasiswa->nama }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $participant->mahasiswa->npm }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $participant->mahasiswa->kelas->nama_kelas ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $participant->mahasiswa->kelas->prodi->nama_prodi ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $participant->mahasiswa->kelas->prodi->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($participant->status_acc === 'Diterima')
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Diterima</span>
                                    @elseif ($participant->status_acc === 'Ditolak')
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">Ditolak</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($participant->status_penyelesaian === 'Selesai')
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Selesai</span>
                                    @elseif ($participant->status_penyelesaian === 'Tidak Selesai')
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">Tidak Selesai</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @if ($participant->status_acc !== 'Diterima')
                                            <form action="{{ route('dosen.participants.accept', $participant->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Terima">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($participant->status_acc !== 'Ditolak')
                                            <form action="{{ route('dosen.participants.reject', $participant->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Status Penyelesaian Dropdown -->
                                        <div class="dropdown inline">
                                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs dropdown-toggle" id="statusBtn{{ $participant->id }}" data-bs-toggle="dropdown">
                                                <i class="fas fa-pencil"></i> Status
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="statusBtn{{ $participant->id }}">
                                                <li>
                                                    <form action="{{ route('dosen.participants.update-status', $participant->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="status_penyelesaian" value="Selesai">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-check-circle"></i> Selesai
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('dosen.participants.update-status', $participant->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="status_penyelesaian" value="Tidak Selesai">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-times-circle"></i> Tidak Selesai
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $participants->links() }}
        </div>
    @endif
</div>

<!-- Bootstrap Script untuk Dropdown -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
