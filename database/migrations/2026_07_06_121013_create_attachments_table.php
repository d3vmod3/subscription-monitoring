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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            // What record this attachment belongs to
            $table->string('module');
            $table->unsignedBigInteger('module_id');

            // Storage information
            $table->string('disk')->default('s3');
            $table->string('path');
            $table->string('original_filename');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();

            // Who uploaded it
            $table->foreignId('uploaded_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->index(['module', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
