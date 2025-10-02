<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('air_quality_logs', function (Blueprint $table) {
            $table->id();
            $table->decimal('ppm', 8, 2);
            $table->integer('kategori'); // 1=Baik, 2=Sedang, 3=Tidak Sehat, 4=Berbahaya
            $table->integer('arah'); // -1=Turun, 0=Stabil, 1=Naik
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index('recorded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('air_quality_logs');
    }
};