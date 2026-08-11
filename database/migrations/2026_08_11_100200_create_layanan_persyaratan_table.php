<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan_persyaratan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('persyaratan_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->unique(['layanan_id', 'persyaratan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_persyaratan');
    }
};