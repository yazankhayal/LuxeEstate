<?php
// ============================================================
// Migration: 2024_01_01_000001_create_languages_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();     // en, ar, tr
            $table->string('name', 50);              // English, Arabic
            $table->string('native_name', 50);       // English, العربية
            $table->string('direction', 3)->default('ltr'); // ltr / rtl
            $table->string('flag', 10)->nullable();  // 🇬🇧
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
