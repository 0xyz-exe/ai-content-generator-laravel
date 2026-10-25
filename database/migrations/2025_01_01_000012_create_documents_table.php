<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('prompt');
            $table->string('tone')->default('professional');
            $table->string('model')->default('gpt-4o');
            $table->longText('generated_content');
            $table->integer('tokens_used')->default(0);
            $table->integer('credits_used')->default(1);
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
