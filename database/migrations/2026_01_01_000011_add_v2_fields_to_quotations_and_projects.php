<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('quotations', function(Blueprint $table){
   $table->string('address')->nullable()->after('location');
   $table->string('map_link')->nullable()->after('address');
   $table->decimal('latitude',10,7)->nullable()->after('map_link');
   $table->decimal('longitude',10,7)->nullable()->after('latitude');
   $table->string('budget_range')->nullable()->after('floors');
  });
  Schema::table('projects', function(Blueprint $table){ $table->integer('sort_order')->default(0)->after('budget'); });
 }
 public function down(): void {
  Schema::table('quotations', function(Blueprint $table){ $table->dropColumn(['address','map_link','latitude','longitude','budget_range']); });
  Schema::table('projects', function(Blueprint $table){ $table->dropColumn('sort_order'); });
 }
};
