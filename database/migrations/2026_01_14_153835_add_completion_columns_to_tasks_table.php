<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Menambahkan kolom status selesai (default: false/0)
            // 'after' digunakan agar posisi kolom rapi di database
            $table->boolean('is_completed')->default(false)->after('deadline');
            
            // Menambahkan kolom waktu kapan tugas selesai
            $table->timestamp('completed_at')->nullable()->after('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['is_completed', 'completed_at']);
        });
    }
};