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
        Schema::create('lunar_product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('star_rating')->nullable();
            $table->string('review')->nullable();
            $table->unsignedBigInteger('product_id'); //
            $table->foreign('product_id')->references('id')->on('lunar_products');
            $table->unsignedBigInteger('product_variant_id'); //
            $table->foreign('product_variant_id')->references('id')->on('lunar_product_variants');
            $table->unsignedBigInteger('customer_id'); //
            $table->foreign('customer_id')->references('id')->on('lunar_customers');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lunar_product_reviews');
    }
};
