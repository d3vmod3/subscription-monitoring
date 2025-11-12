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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete();
            $table->string('reference_number')->nullable();
            $table->dateTime('paid_at');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_category', ['advanced payment', 'monthly bill']);
            $table->enum('is_approved', ['pending', 'approved'])->default('pending');
            $table->boolean('is_first_payment')->default(true);
            $table->boolean('is_discounted')->default(true);
            $table->boolean('has_balance')->default(true); // <- updated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
