<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('quotations', function(Blueprint $table){
   if(!Schema::hasColumn('quotations','province')) $table->string('province')->nullable()->after('location');
   if(!Schema::hasColumn('quotations','district')) $table->string('district')->nullable()->after('province');
   if(!Schema::hasColumn('quotations','subdistrict')) $table->string('subdistrict')->nullable()->after('district');
   if(!Schema::hasColumn('quotations','postcode')) $table->string('postcode',20)->nullable()->after('subdistrict');
   if(!Schema::hasColumn('quotations','details_short')) $table->string('details_short')->nullable()->after('details');
  });
 }
 public function down(): void {
  Schema::table('quotations', function(Blueprint $table){
   foreach(['province','district','subdistrict','postcode','details_short'] as $col){ if(Schema::hasColumn('quotations',$col)) $table->dropColumn($col); }
  });
 }
};
