<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\{Employee,AttendanceRecord,AdvancePayment,PayrollPeriod,PayrollItem,WorkOrder,AdminNotification,DailyWorkSummary};
use Illuminate\Http\Request;

class StaffPortalController extends Controller
{
    private function employee(): ?Employee
    {
        return Employee::where('user_id', auth()->id())->first();
    }
    public function dashboard()
    {
        $employee = $this->employee(); abort_unless($employee, 404, 'ไม่พบข้อมูลพนักงาน');
        $today = today(); $period = $this->currentPeriod();
        $attendance = AttendanceRecord::where('employee_id',$employee->id)->whereDate('work_date',$today)->first();
        $advanceTotal = AdvancePayment::where('employee_id',$employee->id)->whereBetween('request_date',[$period['start'],$period['end']])->whereIn('status',['pending','approved','paid'])->sum('amount');
        $workDays = $employee->dailyWorkSummaries()->whereBetween('work_date',[$period['start'],$period['end']])->sum('work_unit');
        $gross = $workDays * (float)$employee->daily_wage;
        $latestAdvances = $employee->advancePayments()->latest('request_date')->take(5)->get();
        $myTeamIds = $employee->teams()->pluck('work_teams.id');
        $myWorkOrders = WorkOrder::whereIn('work_team_id',$myTeamIds)->orWhere('team_leader','like','%'.$employee->nickname.'%')->orWhere('team_members','like','%'.$employee->nickname.'%')->latest()->take(5)->get();
        $lateCount = AttendanceRecord::where('employee_id',$employee->id)->whereBetween('work_date',[$period['start'],$period['end']])->where('is_late',1)->count();
        $absentCount = DailyWorkSummary::where('employee_id',$employee->id)->whereBetween('work_date',[$period['start'],$period['end']])->where('day_status','absent')->count();
        return view('staff.dashboard', compact('employee','attendance','period','advanceTotal','workDays','gross','latestAdvances','myWorkOrders','lateCount','absentCount'));
    }
    public function checkIn(Request $request)
    {
        $employee = $this->employee(); abort_unless($employee, 404);
        $data = $request->validate(['photo'=>'required|image|mimes:jpg,jpeg,png,webp|max:5120','latitude'=>'nullable|numeric','longitude'=>'nullable|numeric','note'=>'nullable|string|max:500']);
        $today = today()->toDateString();
        if (AttendanceRecord::where('employee_id',$employee->id)->where('work_date',$today)->whereNull('check_out_at')->exists()) return back()->withErrors('วันนี้เข้างานแล้ว หากต้องการแก้ไขให้แจ้งหัวหน้าช่างหรือ Admin');
        $path = $request->file('photo')?->store('staff/checkins','public');
        $checkInAt = now(); $lateLimit = now()->setTime(7,30,0); $isLate = $checkInAt->greaterThan($lateLimit); $lateMinutes = $isLate ? $lateLimit->diffInMinutes($checkInAt) : 0;
        AttendanceRecord::updateOrCreate(['employee_id'=>$employee->id,'work_date'=>$today], ['check_in_at'=>$checkInAt,'check_in_photo'=>$path,'check_in_latitude'=>$data['latitude'] ?? null,'check_in_longitude'=>$data['longitude'] ?? null,'is_late'=>$isLate,'late_minutes'=>$lateMinutes,'status'=>'checked_in','note'=>$data['note'] ?? null]);
        if ($isLate) AdminNotification::create(['type'=>'staff_late','title'=>'พนักงานเข้างานสาย','message'=>$employee->name.' เข้างานสาย '.$lateMinutes.' นาที','url'=>route('admin.attendance.index',['date'=>$today])]);
        return back()->with('success',$isLate ? 'บันทึกเข้างานแล้ว: วันนี้เข้าสาย '.$lateMinutes.' นาที' : 'บันทึกเข้างานเรียบร้อย');
    }
    public function checkOut(Request $request)
    {
        $employee = $this->employee(); abort_unless($employee,404);
        $data = $request->validate(['photo'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120','latitude'=>'nullable|numeric','longitude'=>'nullable|numeric','note'=>'nullable|string|max:500']);
        $attendance = AttendanceRecord::where('employee_id',$employee->id)->where('work_date',today()->toDateString())->first();
        if (!$attendance || !$attendance->check_in_at) return back()->withErrors('ยังไม่ได้เข้างานวันนี้');
        if ($attendance->check_out_at) return back()->withErrors('วันนี้ออกงานแล้ว');
        $path = $request->file('photo')?->store('staff/checkouts','public');
        $attendance->update(['check_out_at'=>now(),'check_out_photo'=>$path,'check_out_latitude'=>$data['latitude'] ?? null,'check_out_longitude'=>$data['longitude'] ?? null,'status'=>'checked_out','note'=>trim(($attendance->note ? $attendance->note."\n" : '').($data['note'] ?? ''))]);
        return back()->with('success','บันทึกออกงานเรียบร้อย');
    }
    public function advances()
    {
        $employee=$this->employee(); abort_unless($employee,404); $period=$this->currentPeriod();
        $used=AdvancePayment::where('employee_id',$employee->id)->whereBetween('request_date',[$period['start'],$period['end']])->whereIn('status',['pending','approved','paid'])->sum('amount');
        $items=$employee->advancePayments()->latest('request_date')->paginate(20);
        return view('staff.advances', compact('employee','period','used','items'));
    }
    public function requestAdvance(Request $request)
    {
        $employee=$this->employee(); abort_unless($employee,404);
        $data=$request->validate(['preset_amount'=>'nullable|numeric|in:100,200,500,1000','amount'=>'nullable|numeric|min:100|max:100000','reason'=>'nullable|string|max:255']);
        $amount = (float)($data['amount'] ?: $data['preset_amount'] ?: 0);
        if ($amount <= 0) return back()->withErrors('กรุณาระบุจำนวนเงินเบิก');
        $period=$this->currentPeriod();
        $used=AdvancePayment::where('employee_id',$employee->id)->whereBetween('request_date',[$period['start'],$period['end']])->whereIn('status',['pending','approved','paid'])->sum('amount');
        $special = ($used + $amount) > 1000 || $amount > 1000;
        $adv=AdvancePayment::create(['employee_id'=>$employee->id,'requested_by'=>auth()->id(),'amount'=>$amount,'request_date'=>today(),'reason'=>$data['reason'] ?? 'พนักงานขอเบิกผ่านมือถือ','status'=>'pending','is_special_request'=>$special,'special_request_note'=>$special?'เกินวงเงิน 1,000 บาท/รอบ ต้องให้เจ้าของอนุมัติพิเศษ':null]);
        AdminNotification::create(['type'=>$special?'advance_special_requested':'advance_requested','title'=>$special?'มีคำขอเบิกเกิน 1,000 บาท':'มีคำขอเบิกเงินใหม่','message'=>$employee->name.' ขอเบิก '.number_format($adv->amount).' บาท'.($special?' (พิเศษ)':''),'url'=>route('admin.advances.index')]);
        return back()->with('success',$special?'ส่งคำขอเบิกพิเศษแล้ว รอเจ้าของอนุมัติ':'ส่งคำขอเบิกเงินแล้ว รอ Admin/เจ้าของอนุมัติ');
    }
    public function payroll()
    {
        $employee=$this->employee(); abort_unless($employee,404);
        $periods=PayrollPeriod::latest('start_date')->take(6)->get();
        $items=PayrollItem::where('employee_id',$employee->id)->with('period')->latest()->get();
        return view('staff.payroll', compact('employee','periods','items'));
    }
    public function workOrders()
    {
        $employee=$this->employee(); abort_unless($employee,404);
        $teamIds=$employee->teams()->pluck('work_teams.id');
        $orders=WorkOrder::with('service','workTeam')->whereIn('work_team_id',$teamIds)->orWhere('team_leader','like','%'.$employee->nickname.'%')->orWhere('team_members','like','%'.$employee->nickname.'%')->latest()->paginate(15);
        return view('staff.work-orders', compact('employee','orders'));
    }
    private function currentPeriod(): array
    {
        $d=today();
        if ($d->day <= 15) return ['name'=>'รอบ 1-15/'.$d->format('m/Y'),'start'=>$d->copy()->startOfMonth()->toDateString(),'end'=>$d->copy()->day(15)->toDateString()];
        return ['name'=>'รอบ 16-'.$d->copy()->endOfMonth()->day.'/'.$d->format('m/Y'),'start'=>$d->copy()->day(16)->toDateString(),'end'=>$d->copy()->endOfMonth()->toDateString()];
    }
}
