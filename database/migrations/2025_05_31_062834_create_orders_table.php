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

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id');
            $table->string('status')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('shipping_description')->nullable();
            $table->boolean('is_gift')->default(0);
            $table->integer('total_item_count')->nullable();
            $table->integer('total_qty_ordered')->nullable();
            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
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
