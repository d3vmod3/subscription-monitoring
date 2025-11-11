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
        Schema::create('pons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('sector_id')->constrained('sectors')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('splitters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
        });

        Schema::create('napboxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pon_id')->constrained('pons')->cascadeOnDelete();
            $table->foreignId('splitter_id')->nullable()->constrained('splitters')->cascadeOnDelete();
            $table->string('napbox_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('napboxes');
        Schema::dropIfExists('splitters');
        Schema::dropIfExists('pons');
    }
};
