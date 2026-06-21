<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{AdvancePayment,DailyWorkSummary,Employee,PayrollItem,PayrollPeriod,StaffWarning};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $periods = PayrollPeriod::where('start_date','like',$month.'%')->orderBy('start_date')->get();
        if ($periods->isEmpty()) {
            $this->ensurePeriods($month);
            $periods = PayrollPeriod::where('start_date','like',$month.'%')->orderBy('start_date')->get();
        }
        return view('admin.payroll.index', compact('periods','month'));
    }

    public function show(PayrollPeriod $period)
    {
        $this->calculate($period);
        return view('admin.payroll.show', ['period'=>$period->load('items.employee')]);
    }

    public function confirm(PayrollPeriod $period)
    {
        $this->calculate($period);
        $period->update(['status'=>'confirmed']);
        return back()->with('success','ยืนยันรอบจ่ายแล้ว');
    }

    public function markPaid(PayrollPeriod $period)
    {
        $period->update(['status'=>'paid','paid_at'=>now()]);
        return back()->with('success','บันทึกจ่ายค่าแรงแล้ว');
    }

    public function exportCsv(PayrollPeriod $period)
    {
        $this->calculate($period);
        $filename = 'payroll_'.$period->start_date->format('Ymd').'_to_'.$period->end_date->format('Ymd').'.csv';
        return response()->streamDownload(function() use ($period) {
            $out = fopen('php://output','w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['พนักงาน','ค่าแรง/วัน','จำนวนแรง','ค่าแรงรวม','โบนัส','หัก','เงินเบิก','ยอดสุทธิ','สาย','ใบเตือน']);
            foreach($period->items()->with('employee')->get() as $item){
                fputcsv($out, [$item->employee?->name,$item->daily_wage,$item->work_units,$item->gross_amount,$item->bonus_amount,$item->deduction_amount,$item->advance_amount,$item->net_amount,$item->late_count,$item->warning_count]);
            }
            fclose($out);
        }, $filename);
    }

    private function ensurePeriods(string $month): void
    {
        $first = Carbon::createFromFormat('Y-m-d', $month.'-01');
        PayrollPeriod::firstOrCreate(['start_date'=>$first->copy()->startOfMonth()->toDateString(),'end_date'=>$first->copy()->day(15)->toDateString()], ['name'=>'รอบจ่าย 1-15 '.$first->format('m/Y')]);
        PayrollPeriod::firstOrCreate(['start_date'=>$first->copy()->day(16)->toDateString(),'end_date'=>$first->copy()->endOfMonth()->toDateString()], ['name'=>'รอบจ่าย 16-'.$first->copy()->endOfMonth()->day.' '.$first->format('m/Y')]);
    }

    private function calculate(PayrollPeriod $period): void
    {
        foreach (Employee::where('is_active', true)->get() as $employee) {
            $workUnits = (float) DailyWorkSummary::where('employee_id',$employee->id)->whereBetween('work_date',[$period->start_date,$period->end_date])->sum('work_unit');
            $lateCount = (int) \App\Models\AttendanceRecord::where('employee_id',$employee->id)->whereBetween('work_date',[$period->start_date,$period->end_date])->where('is_late',true)->count();
            $warnings = StaffWarning::where('employee_id',$employee->id)->whereBetween('warning_date',[$period->start_date,$period->end_date])->get();
            $autoLateDeductionUnits = floor($lateCount / 3);
            $deductionUnits = (float) $warnings->sum('deduction_units') + $autoLateDeductionUnits;
            $daily = (float) $employee->daily_wage;
            $gross = $workUnits * $daily;
            $deduction = $deductionUnits * $daily;
            $advance = (float) AdvancePayment::where('employee_id',$employee->id)->whereBetween('request_date',[$period->start_date,$period->end_date])->whereIn('status',['approved','paid'])->sum('amount');
            PayrollItem::updateOrCreate(
                ['payroll_period_id'=>$period->id,'employee_id'=>$employee->id],
                ['daily_wage'=>$daily,'work_units'=>$workUnits,'gross_amount'=>$gross,'bonus_amount'=>0,'deduction_amount'=>$deduction,'advance_amount'=>$advance,'net_amount'=>$gross - $deduction - $advance,'late_count'=>$lateCount,'warning_count'=>$warnings->count() + (int)$autoLateDeductionUnits]
            );
        }
    }
}
