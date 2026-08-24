<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dms_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_id')->index();
            
            // Polymorphic columns (documentable_type & documentable_id)
            $table->morphs('documentable', 'dms_docs_morph_idx'); 
            
            // DMS Categorization
            $table->string('category'); // customer, property, project, legal_vendor
            $table->string('document_type'); // e.g., Booking Form, Sale Deed, etc.
            
            // Document details
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type');
            
            // Audit Trails
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
            
            // Foreign Key and Indexes
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
            $table->index(['category', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dms_documents');
    }
};
