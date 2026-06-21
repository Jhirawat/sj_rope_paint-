<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('work_orders', function(Blueprint $table){
   $table->id(); $table->string('work_order_no')->unique();
   $table->foreignId('quotation_id')->nullable()->constrained()->nullOnDelete();
   $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
   $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
   $table->string('customer_name'); $table->string('phone',30); $table->string('line_id')->nullable(); $table->string('email')->nullable();
   $table->text('address')->nullable(); $table->string('map_link')->nullable(); $table->decimal('latitude',10,7)->nullable(); $table->decimal('longitude',10,7)->nullable();
   $table->string('job_type')->nullable(); $table->unsignedSmallInteger('floors')->nullable(); $table->longText('details')->nullable();
   $table->string('team_leader')->nullable(); $table->text('team_members')->nullable();
   $table->dateTime('scheduled_survey_at')->nullable(); $table->date('start_at')->nullable(); $table->date('finish_at')->nullable();
   $table->enum('status',['pending_survey','surveyed','waiting_start','working','completed','cancelled'])->default('pending_survey');
   $table->text('admin_note')->nullable(); $table->timestamps();
  });
 }
 public function down(): void { Schema::dropIfExists('work_orders'); }
};
