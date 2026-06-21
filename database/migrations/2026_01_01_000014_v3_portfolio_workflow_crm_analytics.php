<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_images', function (Blueprint $table) {
            if (!Schema::hasColumn('project_images', 'image_type')) {
                $table->enum('image_type', ['cover','before','progress','after','other'])->default('other')->after('image_path');
            }
            if (!Schema::hasColumn('project_images', 'is_cover')) {
                $table->boolean('is_cover')->default(false)->after('image_type');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'project_date')) {
                $table->date('project_date')->nullable()->after('budget');
            }
            if (!Schema::hasColumn('projects', 'source_work_order_id')) {
                $table->foreignId('source_work_order_id')->nullable()->after('service_id')->constrained('work_orders')->nullOnDelete();
            }
        });

        Schema::create('service_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption_th')->nullable();
            $table->string('caption_en')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('customer_timeline_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('quotation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note_type')->default('note');
            $table->string('title');
            $table->longText('note')->nullable();
            $table->timestamp('follow_up_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->string('locale', 5)->nullable();
            $table->string('device_type')->nullable();
            $table->string('ip_hash')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('customer_timeline_notes');
        Schema::dropIfExists('service_images');
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'source_work_order_id')) $table->dropConstrainedForeignId('source_work_order_id');
            if (Schema::hasColumn('projects', 'project_date')) $table->dropColumn('project_date');
        });
        Schema::table('project_images', function (Blueprint $table) {
            if (Schema::hasColumn('project_images', 'is_cover')) $table->dropColumn('is_cover');
            if (Schema::hasColumn('project_images', 'image_type')) $table->dropColumn('image_type');
        });
    }
};
