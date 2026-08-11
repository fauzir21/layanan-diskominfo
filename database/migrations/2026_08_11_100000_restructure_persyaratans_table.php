<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persyaratans', function (Blueprint $table) {
            // layanan_id & urutan dihapus karena persyaratan sekarang
            // bisa dipakai ulang di banyak layanan (many-to-many),
            // urutan-nya dipindah ke tabel pivot layanan_persyaratan
            $table->dropForeign(['layanan_id']);
            $table->dropColumn(['layanan_id', 'urutan']);

            $table->enum('tipe', ['file', 'text'])->default('file')->after('nama_syarat');
            $table->boolean('wajib')->default(true)->after('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('persyaratans', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'wajib']);
            $table->foreignId('layanan_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
        });
    }
};