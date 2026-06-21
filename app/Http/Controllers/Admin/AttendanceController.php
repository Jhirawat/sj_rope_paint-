<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{AttendanceRecord,DailyWorkSummary,Employee,WorkOrder,StaffWarning};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', today()->toDateString());
        return view('admin.attendance.index', [
            'date' => $date,
            'employees' => Employee::where('is_active', true)->orderBy('sort_order')->get(),
            'records' => AttendanceRecord::with('employee','workOrder')->whereDate('work_date', $date)->get()->keyBy('employee_id'),
            'summaries' => DailyWorkSummary::with('employee','workOrder')->whereDate('work_date', $date)->get()->keyBy('employee_id'),
            'workOrders' => WorkOrder::whereNotIn('status',['completed','cancelled'])->orderByDesc('id')->get(),
        ]);
    }

    public function checkIn(Request $request)
    {
        $data = $request->validate([
            'employee_id'=>'required|exists:employees,id',
            'work_order_id'=>'nullable|exists:work_orders,id',
            'work_date'=>'required|date',
            'check_in_photo'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'latitude'=>'nullable|numeric',
            'longitude'=>'nullable|numeric',
            'morning_location_type'=>'required|in:company,site',
            'note'=>'nullable|string|max:1000',
        ]);
        $date = Carbon::parse($data['work_date'])->toDateString();
        if (AttendanceRecord::where('employee_id',$data['employee_id'])->whereDate('work_date',$date)->exists()) {
            return back()->withErrors('พนักงานคนนี้เข้างานวันนี้แล้ว');
        }
        $now = now();
        $lateBase = Carbon::parse($date.' 07:30:00');
        $isLate = $now->gt($lateBase);
        $lateMinutes = $isLate ? $lateBase->diffInMinutes($now) : 0;
        $path = $request->file('check_in_photo')?->store('attendance/checkin','public');
        AttendanceRecord::create([
            'employee_id'=>$data['employee_id'], 'work_order_id'=>$data['work_order_id'] ?? null, 'work_date'=>$date,
            'check_in_at'=>$now, 'check_in_photo'=>$path, 'check_in_latitude'=>$data['latitude'] ?? null, 'check_in_longitude'=>$data['longitude'] ?? null,
            'morning_location_type'=>$data['morning_location_type'], 'is_late'=>$isLate, 'late_minutes'=>$lateMinutes, 'status'=>'checked_in', 'note'=>$data['note'] ?? null,
        ]);
        $this->createLateWarningIfNeeded($data['employee_id'], $date);
        return back()->with('success', $isLate ? 'บันทึกเข้างานแล้ว: สาย '.$lateMinutes.' นาที' : 'บันทึกเข้างานเรียบร้อยแล้ว');
    }

    public function checkOut(Request $request, AttendanceRecord $attendance)
    {
        if ($attendance->check_out_at) {
            return back()->withErrors('รายการนี้ออกงานแล้ว');
        }
        $data = $request->validate([
            'check_out_photo'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'latitude'=>'nullable|numeric',
            'longitude'=>'nullable|numeric',
            'note'=>'nullable|string|max:1000',
        ]);
        $path = $request->file('check_out_photo')?->store('attendance/checkout','public');
        $attendance->update([
            'check_out_at'=>now(), 'check_out_photo'=>$path,
            'check_out_latitude'=>$data['latitude'] ?? null, 'check_out_longitude'=>$data['longitude'] ?? null,
            'status'=>'checked_out', 'note'=>trim(($attendance->note ? $attendance->note."\n" : '').($data['note'] ?? '')),
        ]);
        return back()->with('success','บันทึกออกงานเรียบร้อยแล้ว');
    }

    public function summarize(Request $request)
    {
        $data = $request->validate([
            'employee_id'=>'required|exists:employees,id',
            'work_order_id'=>'nullable|exists:work_orders,id',
            'work_date'=>'required|date',
            'work_unit'=>'required|numeric|in:0,0.5,0.75,1',
            'day_status'=>'required|in:full_day,half_day,three_quarter,absent,leave,late,weather_stop,site_issue',
            'reason'=>'nullable|string|max:255',
            'note'=>'nullable|string|max:1000',
            'weather_stop_photo'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $path = $request->file('weather_stop_photo')?->store('attendance/weather','public');
        DailyWorkSummary::updateOrCreate(
            ['employee_id'=>$data['employee_id'],'work_date'=>Carbon::parse($data['work_date'])->toDateString()],
            ['work_order_id'=>$data['work_order_id'] ?? null,'work_unit'=>$data['work_unit'],'day_status'=>$data['day_status'],'reason'=>$data['reason'] ?? null,'note'=>$data['note'] ?? null,'weather_stop_photo'=>$path,'approved_by'=>auth()->id(),'approved_at'=>now()]
        );
        return back()->with('success','สรุปจำนวนแรงประจำวันเรียบร้อยแล้ว');
    }

    private function createLateWarningIfNeeded(int $employeeId, string $date): void
    {
        $start = Carbon::parse($date)->startOfMonth();
        $end = Carbon::parse($date)->endOfMonth();
        $lateCount = AttendanceRecord::where('employee_id',$employeeId)->whereBetween('work_date',[$start,$end])->where('is_late',true)->count();
        if ($lateCount > 0 && $lateCount % 3 === 0) {
            StaffWarning::firstOrCreate([
                'employee_id'=>$employeeId,
                'warning_date'=>$date,
                'warning_type'=>'late_3_times',
            ],[
                'title'=>'สายครบ 3 ครั้ง',
                'description'=>'ระบบบันทึกใบเตือนอัตโนมัติ และหักค่าแรง 1 แรงตามกติกาบริษัท',
                'deduction_units'=>1,
                'created_by'=>auth()->id(),
            ]);
        }
    }
}
