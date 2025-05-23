<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description_en')->nullable();
            $table->string('description_ar')->nullable();
            $table->string('brand_logo')->nullable();
            $table->string('brand_name')->nullable();
            $table->decimal('value',10,0);
            $table->text('location_url')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pendding','available','used'])->default('available');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
