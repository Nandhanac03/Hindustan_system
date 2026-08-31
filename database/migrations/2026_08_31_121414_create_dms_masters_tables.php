<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dms_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('dms_document_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dms_category_id')->constrained('dms_categories')->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed existing categories and types
        $categoriesInfo = [
            'project' => [
                'label' => 'Project Documents',
                'icon' => 'office-building',
                'types' => [
                    'Approved Building Plan',
                    'Municipality Approval',
                    'RERA Certificate',
                    'Fire NOC',
                    'Environmental Clearance',
                    'Occupancy Certificate',
                    'Completion Certificate',
                    'Site Layout Drawing',
                    'Master Plan',
                    'Other'
                ],
            ],
            'legal' => [
                'label' => 'Legal Documents',
                'icon' => 'scale',
                'types' => [
                    'Land Deed',
                    'Encumbrance Certificate',
                    'Power of Attorney',
                    'Partnership Agreement',
                    'Legal Opinion',
                    'Court Document',
                    'Government Approval',
                    'Other'
                ],
            ],
            'company' => [
                'label' => 'Company Documents',
                'icon' => 'briefcase',
                'types' => [
                    'GST Registration',
                    'PAN Card',
                    'Trade License',
                    'Company Incorporation Certificate',
                    'ISO Certificate',
                    'Insurance Policy',
                    'Audit Report',
                    'Other'
                ],
            ],
            'hr' => [
                'label' => 'HR & Employee Documents',
                'icon' => 'users',
                'types' => [
                    'Appointment Letter',
                    'Employment Contract',
                    'NDA Agreement',
                    'Policy Document',
                    'Employee ID Proof',
                    'Experience Certificate',
                    'Other'
                ],
            ],
            'partner' => [
                'label' => 'Partner & Investor Documents',
                'icon' => 'handshake',
                'types' => [
                    'Investment Agreement',
                    'MOU Document',
                    'Share Allocation Document',
                    'Settlement Agreement',
                    'Board Resolution',
                    'Other'
                ],
            ],
            'drawings' => [
                'label' => 'Drawings & Technical Documents',
                'icon' => 'photograph',
                'types' => [
                    'Architectural Drawing',
                    'Structural Drawing',
                    'Electrical Layout',
                    'Plumbing Layout',
                    'BOQ Document',
                    'Engineering Report',
                    'Other'
                ],
            ],
            'templates' => [
                'label' => 'Templates & Standard Documents',
                'icon' => 'document-duplicate',
                'types' => [
                    'Sale Agreement Template',
                    'Possession Letter Template',
                    'Cancellation Letter Template',
                    'Demand Letter Template',
                    'Payment Reminder Template',
                    'Other'
                ],
            ],
        ];

        foreach ($categoriesInfo as $code => $info) {
            $categoryId = DB::table('dms_categories')->insertGetId([
                'name' => $info['label'],
                'code' => $code,
                'icon' => $info['icon'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($info['types'] as $typeName) {
                DB::table('dms_document_types')->insert([
                    'dms_category_id' => $categoryId,
                    'name' => $typeName,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dms_document_types');
        Schema::dropIfExists('dms_categories');
    }
};
