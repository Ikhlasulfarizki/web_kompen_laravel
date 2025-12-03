<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kompen') - Sistem Manajemen Kompensasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-purple-600">
                        Kompen
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">{{ Auth::user()->username }}</span>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-user-circle text-2xl"></i>
                        </button>
                        <div class="absolute right-0 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-10">
                            @if(Auth::user()->role_id == 1)
                                <a href="{{ route('admin.profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                            @else
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <nav class="p-4 space-y-2">
                @if(Auth::user()->role_id == 1)
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('dashboard')) bg-gray-700 @endif">
                        <i class="fas fa-chart-line mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.mahasiswa.*')) bg-gray-700 @endif">
                        <i class="fas fa-users mr-2"></i> Mahasiswa
                    </a>
                    <a href="{{ route('admin.dosen.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.dosen.*')) bg-gray-700 @endif">
                        <i class="fas fa-chalkboard-user mr-2"></i> Dosen
                    </a>
                    <a href="{{ route('admin.teknisi.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.kompen.*')) bg-gray-700 @endif">
                        <i class="fas fa-tasks mr-2"></i> Teknisi
                    </a>
                    <a href="{{ route('admin.kompen.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.kompen.*')) bg-gray-700 @endif">
                        <i class="fas fa-tasks mr-2"></i> Kompen
                    </a>

                    <!-- Master Data Section -->
                    <div class="mt-6 pt-4 border-t border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Master</p>
                        <a href="{{ route('admin.jurusan.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.jurusan.*')) bg-gray-700 @endif">
                            <i class="fas fa-sitemap mr-2"></i> Jurusan
                        </a>
                        <a href="{{ route('admin.prodi.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.prodi.*')) bg-gray-700 @endif">
                            <i class="fas fa-graduation-cap mr-2"></i> Program Studi
                        </a>
                        <a href="{{ route('admin.kelas.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.kelas.*')) bg-gray-700 @endif">
                            <i class="fas fa-door-open mr-2"></i> Kelas
                        </a>
                    </div>

                    <!-- Users Management Section -->
                    <div class="mt-6 pt-4 border-t border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Manajemen</p>
                        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.users.*')) bg-gray-700 @endif">
                            <i class="fas fa-user-shield mr-2"></i> Users
                        </a>
                    </div>
                @elseif(Auth::user()->role_id == 2)
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                        <i class="fas fa-home mr-2"></i> Home
                    </a>
                @elseif(Auth::user()->role_id == 3)
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                        <i class="fas fa-home mr-2"></i> Home
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Error Message -->
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4" role="alert">
                    <p class="font-bold">Error!</p>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
