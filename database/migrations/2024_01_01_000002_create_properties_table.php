<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->enum('type', ['sale', 'rent'])->default('sale');
            $table->enum('status', ['active', 'inactive', 'sold', 'rented'])->default('active');
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('location');
            $table->string('city', 100);
            $table->string('country', 100)->default('Turkey');
            $table->decimal('area', 10, 2)->comment('m²');
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            // SEO
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'type']);
            $table->index(['is_featured', 'status']);
            $table->index('city');
        });

        Schema::create('property_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description');
            $table->string('address', 500)->nullable();
            $table->json('features')->nullable(); // ['Pool', 'Parking', ...]

            $table->unique(['property_id', 'locale']);
            $table->index('locale');
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt', 255)->nullable();
            $table->boolean('is_cover')->default(false);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->index(['property_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('property_translations');
        Schema::dropIfExists('properties');
    }
};
