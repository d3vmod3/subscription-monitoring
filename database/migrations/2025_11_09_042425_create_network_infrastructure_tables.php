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
        });

        Schema::create('splitters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('napboxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('splitter_id')->constrained()->cascadeOnDelete();
            $table->string('napbox_code')->unique();
            $table->string('name');
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
