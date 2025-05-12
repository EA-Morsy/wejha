<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->string('type'); // image, video, link, text, article, ...
            $table->json('content')->nullable(); // dynamic content per type
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('article_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_items');
    }
};
