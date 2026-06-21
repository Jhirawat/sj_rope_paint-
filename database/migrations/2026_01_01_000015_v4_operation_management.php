<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('work_orders', function(Blueprint $table){
   $table->timestamp('accepted_at')->nullable()->after('finish_at');
   $table->string('customer_signature_path')->nullable()->after('accepted_at');
   $table->string('foreman_signature_path')->nullable()->after('customer_signature_path');
   $table->string('inspector_signature_path')->nullable()->after('foreman_signature_path');
   $table->timestamp('last_checkin_at')->nullable()->after('inspector_signature_path');
   $table->decimal('last_checkin_latitude',10,7)->nullable()->after('last_checkin_at');
   $table->decimal('last_checkin_longitude',10,7)->nullable()->after('last_checkin_latitude');
  });
  Schema::create('work_order_status_logs', function(Blueprint $table){
   $table->id(); $table->foreignId('work_order_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
   $table->string('from_status')->nullable(); $table->string('to_status'); $table->text('note')->nullable(); $table->timestamps();
  });
  Schema::create('work_order_checkins', function(Blueprint $table){
   $table->id(); $table->foreignId('work_order_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
   $table->decimal('latitude',10,7)->nullable(); $table->decimal('longitude',10,7)->nullable(); $table->text('note')->nullable(); $table->timestamps();
  });
  Schema::create('admin_notifications', function(Blueprint $table){
   $table->id(); $table->string('type')->index(); $table->string('title'); $table->text('message')->nullable(); $table->string('url')->nullable(); $table->timestamp('read_at')->nullable(); $table->timestamps();
  });
 }
 public function down(): void {
  Schema::dropIfExists('admin_notifications'); Schema::dropIfExists('work_order_checkins'); Schema::dropIfExists('work_order_status_logs');
  Schema::table('work_orders', function(Blueprint $table){ $table->dropColumn(['accepted_at','customer_signature_path','foreman_signature_path','inspector_signature_path','last_checkin_at','last_checkin_latitude','last_checkin_longitude']); });
 }
};
