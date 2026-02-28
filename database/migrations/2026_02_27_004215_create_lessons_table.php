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
        Schema::create('lessons', function (Blueprint $table) {
          $table->id();
        $table->foreignId('course_id')->constrained()->onDelete('cascade'); // مربوط بجدول الكورسات
        $table->string('title');
        $table->string('video_url'); // رابط الفيديو
        $table->integer('order')->default(1); // ترتيب الدرس
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
