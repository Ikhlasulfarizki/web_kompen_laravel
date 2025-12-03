@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manajemen Users</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <i class="fas fa-times-circle mr-2"></i>{{ $message }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-200 border-b-2 border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">No</th>
                    <th class="px-6 py-3 text-left font-semibold">Username</th>
                    <th class="px-6 py-3 text-left font-semibold">Role</th>
                    <th class="px-6 py-3 text-left font-semibold">Dibuat</th>
                    <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($users->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @else
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $user->username }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($user->role_id == 1) bg-red-100 text-red-800
                                    @elseif($user->role_id == 2) bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $user->role->nama_role ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($user->id !== auth()->user()->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Apakah Anda yakin?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed">
                                        <i class="fas fa-trash"></i> Hapus
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
