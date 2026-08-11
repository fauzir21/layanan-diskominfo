<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->foreignId('tim_kerja_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('riwayat_disposisis', function (Blueprint $table) {
            $table->foreignId('tim_kerja_id')->nullable()->after('pengajuan_id')->constrained()->nullOnDelete();
            $table->foreignId('handled_by')->nullable()->after('tim_kerja_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tim_kerja_id');
        });

        Schema::table('riwayat_disposisis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tim_kerja_id');
            $table->dropConstrainedForeignId('handled_by');
        });
    }
};