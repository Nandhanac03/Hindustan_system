<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dms_documents', function (Blueprint $table) {
            $table->date('issue_date')->nullable()->after('document_type');
            $table->date('expiry_date')->nullable()->after('issue_date');
            $table->string('document_number')->nullable()->after('expiry_date');
            $table->string('revision_number')->nullable()->after('document_number');
            $table->string('drawing_type')->nullable()->after('revision_number');
            $table->string('department')->nullable()->after('drawing_type');
            $table->string('legal_category')->nullable()->after('department');
            $table->string('template_category')->nullable()->after('legal_category');
            $table->string('tower')->nullable()->after('template_category');
            $table->unsignedBigInteger('reference_project_id')->nullable()->after('tower');

            $table->foreign('reference_project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dms_documents', function (Blueprint $table) {
            $table->dropForeign(['reference_project_id']);
            $table->dropColumn([
                'issue_date',
                'expiry_date',
                'document_number',
                'revision_number',
                'drawing_type',
                'department',
                'legal_category',
                'template_category',
                'tower',
                'reference_project_id',
            ]);
        });
    }
};
