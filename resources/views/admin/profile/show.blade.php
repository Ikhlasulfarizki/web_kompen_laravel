@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Profil Admin</h1>
        <a href="{{ route('admin.profile.edit') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">
            <i class="fas fa-edit mr-2"></i> Edit Profil
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="md:col-span-3 bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center space-x-6 mb-6">
                <div class="text-6xl text-gray-400">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ Auth::user()->username }}</h2>
                    <p class="text-gray-600">Role: {{ Auth::user()->role->nama_role }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Username</p>
                    <p class="text-lg font-semibold">{{ Auth::user()->username }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Role</p>
                    <p class="text-lg font-semibold">{{ Auth::user()->role->nama_role }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Dibuat Pada</p>
                    <p class="text-lg font-semibold">{{ Auth::user()->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Terakhir Diubah</p>
                    <p class="text-lg font-semibold">{{ Auth::user()->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
