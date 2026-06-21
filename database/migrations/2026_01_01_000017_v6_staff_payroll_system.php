<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pay_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount',10,2);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->enum('employee_type',['head_technician','daily_technician','helper','accounting_admin','owner'])->default('daily_technician');
            $table->string('position_note')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('default_pay_rate_id')->nullable()->constrained('pay_rates')->nullOnDelete();
            $table->decimal('daily_wage',10,2)->default(360);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->date('work_date');
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->string('check_in_photo')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->decimal('check_in_latitude',10,7)->nullable();
            $table->decimal('check_in_longitude',10,7)->nullable();
            $table->decimal('check_out_latitude',10,7)->nullable();
            $table->decimal('check_out_longitude',10,7)->nullable();
            $table->enum('morning_location_type',['company','site'])->default('company');
            $table->unsignedInteger('late_minutes')->default(0);
            $table->boolean('is_late')->default(false);
            $table->enum('status',['checked_in','checked_out','manual_adjusted'])->default('checked_in');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['employee_id','work_date']);
        });
        Schema::create('daily_work_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->date('work_date');
            $table->decimal('work_unit',4,2)->default(1.00);
            $table->enum('day_status',['full_day','half_day','three_quarter','absent','leave','late','weather_stop','site_issue'])->default('full_day');
            $table->string('reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->string('weather_stop_photo')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['employee_id','work_date']);
        });
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('amount',10,2);
            $table->date('request_date');
            $table->enum('status',['pending','approved','rejected','paid'])->default('pending');
            $table->string('reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status',['draft','confirmed','paid'])->default('draft');
            $table->timestamp('paid_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->decimal('daily_wage',10,2);
            $table->decimal('work_units',6,2)->default(0);
            $table->decimal('gross_amount',10,2)->default(0);
            $table->decimal('bonus_amount',10,2)->default(0);
            $table->decimal('deduction_amount',10,2)->default(0);
            $table->decimal('advance_amount',10,2)->default(0);
            $table->decimal('net_amount',10,2)->default(0);
            $table->unsignedInteger('late_count')->default(0);
            $table->unsignedInteger('warning_count')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['payroll_period_id','employee_id']);
        });
        Schema::create('company_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date');
            $table->string('title_th');
            $table->string('title_en')->nullable();
            $table->enum('type',['public_holiday','company_holiday','payroll','work_event'])->default('public_holiday');
            $table->boolean('is_active')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();
        });
        Schema::create('staff_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('warning_date');
            $table->string('warning_type')->default('late_3_times');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('deduction_units',4,2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_warnings');
        Schema::dropIfExists('company_holidays');
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_periods');
        Schema::dropIfExists('advance_payments');
        Schema::dropIfExists('daily_work_summaries');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('pay_rates');
    }
};
