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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_charge', 10, 2);
            $table->decimal('advance_paid_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('restrict');
            $table->string('transaction_id')->unique()->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('admin_discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->unsignedBigInteger('delivery_option_id')->nullable();
            $table->foreign('delivery_option_id')->references('id')->on('delivery_options')->onDelete('set null');
            $table->foreignId('courier_service_id')->nullable()->constrained('courier_services')->nullOnDelete();
            $table->string('tracking_code')->nullable();
            $table->string('consignment_id')->nullable();
            $table->json('courier_response')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('custom_link')->nullable();
            $table->enum('status', ['incomplete', 'pending', 'hold', 'processing','shipped', 'courier_delivered', 'delivered', 'cancelled', 'in_review', 'unknown'])->default('pending');
            $table->text('comment')->nullable();
            $table->string('admin_comment')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
