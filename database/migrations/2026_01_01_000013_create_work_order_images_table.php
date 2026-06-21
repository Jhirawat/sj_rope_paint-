<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('work_order_images', function(Blueprint $table){ $table->id(); $table->foreignId('work_order_id')->constrained()->cascadeOnDelete(); $table->string('image_path'); $table->enum('type',['before','during','after','other'])->default('other'); $table->string('caption')->nullable(); $table->integer('sort_order')->default(0); $table->timestamps(); }); }
 public function down(): void { Schema::dropIfExists('work_order_images'); }
};
