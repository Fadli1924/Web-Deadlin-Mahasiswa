<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pertama atau buat jika belum ada
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Task::create([
            'title' => 'Tugas Algoritma dan Pemrograman',
            'description' => 'Implementasi sorting algorithm dalam bahasa C++',
            'deadline' => Carbon::now()->addDays(3),
            'user_id' => $user->id,
        ]);

        Task::create([
            'title' => 'Laporan Praktikum Fisika',
            'description' => 'Analisis data percobaan gerak parabola',
            'deadline' => Carbon::now()->addDays(7),
            'user_id' => $user->id,
        ]);

        Task::create([
            'title' => 'Presentasi Sistem Operasi',
            'description' => 'Presentasi tentang memory management',
            'deadline' => Carbon::now()->addHours(12),
            'user_id' => $user->id,
        ]);

        Task::create([
            'title' => 'Tugas Basis Data',
            'description' => 'Desain ERD untuk sistem akademik',
            'deadline' => Carbon::now()->subDays(1), // Sudah terlewat
            'user_id' => $user->id,
        ]);
    }
}
