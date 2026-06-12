<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('website')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->text('address')->nullable();
            $table->enum('type', ['school', 'college', 'coaching', 'university', 'other'])->default('school');
            $table->foreignId('source_id')->nullable()->constrained('lead_sources')->nullOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('lead_stages')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->integer('lead_score')->default(0);
            $table->decimal('estimated_value', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('ai_data')->nullable();
            $table->json('custom_fields')->nullable();
            $table->enum('status', [
                'new', 'contacted', 'qualified', 'proposal_sent',
                'negotiation', 'demo_scheduled', 'won', 'lost', 'on_hold'
            ])->default('new');
            $table->date('follow_up_date')->nullable();
            $table->string('lost_reason')->nullable();
            $table->integer('student_count')->nullable();
            $table->string('board')->nullable(); // CBSE, ICSE, State Board etc
            $table->string('medium')->nullable(); // English, Hindi etc
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'team_id']);
            $table->index(['assigned_to', 'status']);
            $table->index(['city', 'state']);
            $table->index('lead_score');
            $table->index('follow_up_date');
        });

        Schema::create('lead_tags', function (Blueprint $table) {
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['lead_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_tags');
        Schema::dropIfExists('leads');
    }
};
