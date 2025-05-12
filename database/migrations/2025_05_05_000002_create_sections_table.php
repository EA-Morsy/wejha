<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('section_type'); // hero, gallery, articles_list, etc
            $table->string('title_ar')->nullable(); // main content for the section
            $table->string('title_en')->nullable(); // main content for the section
            $table->text('description_ar')->nullable(); // main content for the section
            $table->text('description_en')->nullable(); // main content for the section

            $table->longText('content_ar')->nullable(); // main content for the section
            $table->longText('content_en')->nullable(); // main content for the section
            $table->text('image')->nullable(); // main content for the section
            $table->integer('order')->default(0); // section order in page
            $table->json('settings')->nullable(); // for any future settings
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
