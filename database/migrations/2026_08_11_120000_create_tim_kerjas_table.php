<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tim', 100);
            $table->timestamps();
        });

        Schema::create('tim_kerja_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_kerja_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tim_kerja_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_kerja_user');
        Schema::dropIfExists('tim_kerjas');
    }
};