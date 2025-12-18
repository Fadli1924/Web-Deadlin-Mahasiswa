<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Selamat datang, {{ Auth::user()->name }}!
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <!-- Main Content Area -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-2xl sm:rounded-3xl border border-white/20 dark:border-gray-700/20 p-12">
                <div class="text-center">
                    <h3 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Selamat Datang di Dashboard
                    </h3>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        Kelola aktivitas akademik Anda dengan mudah dan efisien.
                    </p>
                    <!-- Decorative Line -->
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto rounded-full"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for animations -->
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</x-app-layout>
