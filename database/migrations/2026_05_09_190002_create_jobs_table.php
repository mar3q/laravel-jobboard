<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();

            $table->string('seniority', 32);
            $table->string('contract_type', 32);
            $table->string('status', 32)->default('draft');

            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('salary_currency', 3)->default('PLN');

            $table->string('location_city')->nullable();
            $table->string('location_country', 2)->default('PL');
            $table->boolean('remote')->default(false);
            $table->boolean('hybrid')->default(false);

            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('applications_count')->default(0);

            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index(['seniority', 'contract_type']);
            $table->index(['remote', 'location_country']);

            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $table->fullText(['title', 'description']);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
