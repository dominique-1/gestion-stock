<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('file_path');
            $table->string('document_type')->default('fiche_technique'); // fiche_technique, manuel, certificat, autre
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_documents');
    }
};
