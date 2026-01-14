<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Selamat Datang') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    📝 Tambah Tugas
                </a>
                <form action="{{ route('tasks.destroyOverdue') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua tugas yang sudah terlewat?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        🗑️ Hapus Tugas Terlewat
                    </button>
                </form>
                <div class="text-xs text-gray-600 dark:text-gray-400">
                    Selamat datang, {{ Auth::user()->name }}!
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- FILTER STATUS & STATISTIK -->
            <div class="mb-6">
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl sm:rounded-3xl border border-white/20 dark:border-gray-700/20 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                        Filter Status & Statistik:
                    </h3>
                    
                    @php
                        // Hitung semua kategori tugas
                        $allTasksCount = $tasks->where('is_completed', false)->count();
                        $urgentCount = $tasks->filter(function($task) {
                            return !$task->is_completed && ($task->deadline->isToday() || $task->deadline->isTomorrow()) && !$task->deadline->isPast();
                        })->count();
                        $overdueCount = $tasks->filter(function($task) {
                            return !$task->is_completed && $task->deadline->isPast();
                        })->count();
                        $completedCount = $tasks->where('is_completed', true)->count();
                        
                        // Total tugas keseluruhan
                        $totalTasks = $allTasksCount + $completedCount;
                        
                        // Hitung persentase
                        $urgentPercentage = $totalTasks > 0 ? round(($urgentCount / $totalTasks) * 100, 1) : 0;
                        $overduePercentage = $totalTasks > 0 ? round(($overdueCount / $totalTasks) * 100, 1) : 0;
                        $completedPercentage = $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100, 1) : 0;
                        $allTasksPercentage = $totalTasks > 0 ? round(($allTasksCount / $totalTasks) * 100, 1) : 0;
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <!-- Semua Tugas -->
                        <a href="{{ route('dashboard') }}" class="filter-btn-all p-4 rounded-2xl text-center border-2 {{ request('status') == '' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50' }} hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $allTasksCount }}
                            </div>
                            <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Semua</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500">{{ $allTasksPercentage }}%</div>
                        </a>

                        <!-- Segera (Jadwal Segera) -->
                        <a href="{{ route('dashboard', ['status' => 'urgent']) }}" class="filter-btn p-4 rounded-2xl text-center border-2 {{ request('status') == 'urgent' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/30' : 'border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50' }} hover:border-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 transition">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ $urgentCount }}
                            </div>
                            <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Segera</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500">{{ $urgentPercentage }}%</div>
                        </a>

                        <!-- Terlewat (Jadwal Terlewat) -->
                        <a href="{{ route('dashboard', ['status' => 'overdue']) }}" class="filter-btn p-4 rounded-2xl text-center border-2 {{ request('status') == 'overdue' ? 'border-red-500 bg-red-50 dark:bg-red-900/30' : 'border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50' }} hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ $overdueCount }}
                            </div>
                            <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Terlewat</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500">{{ $overduePercentage }}%</div>
                        </a>

                        <!-- Selesai (Tugas yang Sudah Diselesaikan) -->
                        <a href="{{ route('dashboard', ['status' => 'completed']) }}" class="filter-btn p-4 rounded-2xl text-center border-2 {{ request('status') == 'completed' ? 'border-green-500 bg-green-50 dark:bg-green-900/30' : 'border-gray-200 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50' }} hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-900/30 transition">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $completedCount }}
                            </div>
                            <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Selesai</div>
                            <div class="text-xs text-gray-500 dark:text-gray-500">{{ $completedPercentage }}%</div>
                        </a>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-400 via-green-500 to-yellow-400 h-2 rounded-full" style="width: {{ $completedPercentage }}%"></div>
                    </div>
                </div>
            </div>

            @php
                // Filter notifikasi hanya untuk tugas yang belum selesai
                $activeNotifications = $notifications->filter(function($notification) {
                    return !$notification->is_completed;
                });
            @endphp

            @if($activeNotifications->count() > 0)
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border-l-4 border-orange-500 dark:border-orange-400 p-4 rounded-lg shadow-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-orange-500 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-orange-800 dark:text-orange-200">
                                    ⏰ Reminder Deadline Dekat
                                </h3>
                                <div class="mt-2 text-sm text-orange-700 dark:text-orange-300">
                                    <p class="mb-2">Ada {{ $activeNotifications->count() }} tugas yang deadline-nya dalam 3 hari ke depan:</p>
                                    <ul class="space-y-1">
                                        @foreach($activeNotifications as $notification)
                                            <li class="flex items-center">
                                                <span class="inline-block w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                                                <strong>{{ $notification->title }}</strong> - 
                                                <span class="ml-1">{{ $notification->deadline->format('d M Y H:i') }}</span>
                                                
                                                {{-- LOGIKA NOTIFIKASI DIPERBAIKI --}}
                                                @if($notification->deadline->isToday())
                                                    <span class="ml-2 px-2 py-0.5 bg-red-200 dark:bg-red-700 text-red-800 dark:text-red-200 rounded text-xs font-semibold">Hari ini!</span>
                                                @elseif($notification->deadline->isTomorrow())
                                                    <span class="ml-2 px-2 py-0.5 bg-orange-200 dark:bg-orange-700 text-orange-800 dark:text-orange-200 rounded text-xs font-semibold">Besok!</span>
                                                @else
                                                    <span class="ml-2 px-2 py-0.5 bg-yellow-200 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-200 rounded text-xs font-semibold">{{ $notification->deadline->diffInDays(now()) }} hari lagi</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl sm:rounded-3xl border border-white/20 dark:border-gray-700/20 p-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                            📅 Tugas Deadline Mata Kuliah
                        </h3>

                        @php
                            $filteredTasks = $tasks;
                            $statusFilter = request('status', '');
                            
                            if($statusFilter === 'urgent') {
                                $filteredTasks = $tasks->filter(function($task) {
                                    return !$task->is_completed && ($task->deadline->isToday() || $task->deadline->isTomorrow()) && !$task->deadline->isPast();
                                });
                            } elseif($statusFilter === 'overdue') {
                                $filteredTasks = $tasks->filter(function($task) {
                                    return !$task->is_completed && $task->deadline->isPast();
                                });
                            } elseif($statusFilter === 'completed') {
                                $filteredTasks = $tasks->filter(function($task) {
                                    return $task->is_completed;
                                });
                            } else {
                                $filteredTasks = $tasks->where('is_completed', false);
                            }
                        @endphp

                        @if($filteredTasks->count() > 0)
                            <div class="space-y-3">
                                @foreach($filteredTasks as $task)
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl shadow-lg border border-white/20 dark:border-gray-600/20 hover:shadow-xl transition-shadow duration-300 {{ $task->is_completed ? 'opacity-75' : '' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-1 {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                                    {{ $task->title }}
                                                </h4>
                                                @if($task->description)
                                                    <p class="text-gray-600 dark:text-gray-300 mb-2 text-xs {{ $task->is_completed ? 'line-through' : '' }}">
                                                        {{ Str::limit($task->description, 80) }}
                                                    </p>
                                                @endif
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $task->deadline->format('d M Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="ml-3 grid grid-cols-2 gap-2">
                                                {{-- LOGIKA STATUS TUGAS --}}
                                                @if($task->is_completed)
                                                    {{-- Status Selesai --}}
                                                    <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 col-span-2">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Selesai
                                                    </span>
                                                @elseif($task->deadline->isPast())
                                                    {{-- Waktu SUDAH LEWAT --}}
                                                    <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 col-span-2">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Terlewat
                                                    </span>

                                                @elseif($task->deadline->isToday() || $task->deadline->isTomorrow())
                                                    {{-- HARI INI ATAU BESOK --}}
                                                    <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 col-span-2">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Segera
                                                    </span>

                                                @else
                                                    {{-- MASIH LAMA --}}
                                                    <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 col-span-2">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Aktif
                                                    </span>
                                                @endif
                                                
                                                @if(!$task->is_completed)
                                                    <form action="{{ route('tasks.markComplete', $task) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800 transition">
                                                            Selesai
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center justify-center px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                                                    ✏️ Edit
                                                </a>

                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')" class="col-span-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="status" value="{{ request('status', '') }}">
                                                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800 transition">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 dark:text-gray-500 mb-3">
                                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">
                                    Belum ada tugas deadline
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 text-xs">
                                    Tambahkan tugas deadline mata kuliah Anda untuk memulai monitoring.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</x-app-layout>