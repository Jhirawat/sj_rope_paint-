<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','username')) $table->string('username')->nullable()->unique()->after('email');
            if (!Schema::hasColumn('users','pin_hash')) $table->string('pin_hash')->nullable()->after('password');
            if (!Schema::hasColumn('users','must_change_pin')) $table->boolean('must_change_pin')->default(false)->after('pin_hash');
            if (!Schema::hasColumn('users','pin_failed_attempts')) $table->unsignedTinyInteger('pin_failed_attempts')->default(0)->after('must_change_pin');
            if (!Schema::hasColumn('users','pin_locked_until')) $table->timestamp('pin_locked_until')->nullable()->after('pin_failed_attempts');
        });
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees','profile_photo')) $table->string('profile_photo')->nullable()->after('phone');
            if (!Schema::hasColumn('employees','employment_status')) $table->enum('employment_status',['active','suspended','resigned'])->default('active')->after('is_active');
            if (!Schema::hasColumn('employees','staff_score')) $table->integer('staff_score')->default(100)->after('sort_order');
        });
        Schema::table('advance_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('advance_payments','is_special_request')) $table->boolean('is_special_request')->default(false)->after('amount');
            if (!Schema::hasColumn('advance_payments','special_request_note')) $table->string('special_request_note')->nullable()->after('is_special_request');
        });
        Schema::create('work_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('foreman_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('color')->default('#071b35');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('employee_work_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('role_in_team')->default('member');
            $table->timestamps();
            $table->unique(['work_team_id','employee_id']);
        });
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders','work_team_id')) $table->foreignId('work_team_id')->nullable()->after('service_id')->constrained('work_teams')->nullOnDelete();
            if (!Schema::hasColumn('work_orders','income_amount')) $table->decimal('income_amount',12,2)->default(0)->after('details');
            if (!Schema::hasColumn('work_orders','material_cost')) $table->decimal('material_cost',12,2)->default(0)->after('income_amount');
            if (!Schema::hasColumn('work_orders','labor_cost')) $table->decimal('labor_cost',12,2)->default(0)->after('material_cost');
            if (!Schema::hasColumn('work_orders','other_cost')) $table->decimal('other_cost',12,2)->default(0)->after('labor_cost');
        });
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('module')->nullable();
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        // Fill easy username/PIN defaults for demo staff.
        $map = ['ช่างดุด'=>'dud','ช่างมาร์ค'=>'mark','ช่างเวฟ'=>'wave','ช่างกบ'=>'kob','ช่างชล'=>'chon','ช่างภูมิ'=>'phum','ช่างอิ้ด'=>'id','ใบเตย'=>'baitoey','เหมย'=>'moei','ก้อย'=>'koy'];
        foreach ($map as $name=>$username) {
            $employee = DB::table('employees')->where('name',$name)->first();
            if ($employee && $employee->user_id) {
                DB::table('users')->where('id',$employee->user_id)->update(['username'=>$username,'pin_hash'=>Hash::make('1234'),'must_change_pin'=>false]);
            }
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        if (Schema::hasColumn('work_orders','work_team_id')) {
            Schema::table('work_orders', function(Blueprint $table){
                $table->dropConstrainedForeignId('work_team_id');
                $table->dropColumn(['income_amount','material_cost','labor_cost','other_cost']);
            });
        }
        Schema::dropIfExists('employee_work_team');
        Schema::dropIfExists('work_teams');
    }
};
