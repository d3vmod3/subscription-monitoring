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
            $table->string('account_name'); // 🟢 who paid (can be subscriber or other person)
            $table->string('reference_number')->nullable();
            $table->dateTime('paid_at');
            $table->date('date_cover_from');
            $table->date('date_cover_to');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['Approved', 'Disapproved', 'Pending'])->default('Pending');
            $table->boolean('is_discounted')->default(false);
            $table->boolean('is_first_payment')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Advance Payments table
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });

        Schema::create('advance_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_payments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('advance_bill_payments');
    }
};
