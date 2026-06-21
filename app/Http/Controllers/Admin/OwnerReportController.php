<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Employee,DailyWorkSummary,AttendanceRecord,AdvancePayment,WorkOrder};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class OwnerReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $start = Carbon::parse($month.'-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $employees = Employee::where('is_active',1)->orderBy('sort_order')->get();
        $workUnits = DailyWorkSummary::selectRaw('employee_id, sum(work_unit) as total_units')->whereBetween('work_date',[$start,$end])->groupBy('employee_id')->pluck('total_units','employee_id');
        $lateCounts = AttendanceRecord::selectRaw('employee_id, count(*) as total')->whereBetween('work_date',[$start,$end])->where('is_late',1)->groupBy('employee_id')->pluck('total','employee_id');
        $absentCounts = DailyWorkSummary::selectRaw('employee_id, count(*) as total')->whereBetween('work_date',[$start,$end])->where('day_status','absent')->groupBy('employee_id')->pluck('total','employee_id');
        $advanceTotals = AdvancePayment::selectRaw('employee_id, sum(amount) as total')->whereBetween('request_date',[$start,$end])->whereIn('status',['pending','approved','paid'])->groupBy('employee_id')->pluck('total','employee_id');
        $rows = $employees->map(function($e) use($workUnits,$lateCounts,$absentCounts,$advanceTotals){
            return (object)['employee'=>$e,'work_units'=>(float)($workUnits[$e->id]??0),'late_count'=>(int)($lateCounts[$e->id]??0),'absent_count'=>(int)($absentCounts[$e->id]??0),'advance_total'=>(float)($advanceTotals[$e->id]??0),'score'=>100-((int)($lateCounts[$e->id]??0)*2)-((int)($absentCounts[$e->id]??0)*10)];
        });
        return view('admin.owner-reports.index', [
            'month'=>$month,'start'=>$start,'end'=>$end,'rows'=>$rows,
            'topHardWorkers'=>$rows->sortByDesc('work_units')->take(5),
            'topLate'=>$rows->sortByDesc('late_count')->take(5),
            'topAbsent'=>$rows->sortByDesc('absent_count')->take(5),
            'topAdvance'=>$rows->sortByDesc('advance_total')->take(5),
            'hallOfFame'=>$rows->filter(fn($r)=>$r->late_count==0 && $r->absent_count==0 && $r->work_units>0)->sortByDesc('work_units')->take(5),
            'workOrders'=>WorkOrder::latest()->take(10)->get(),
        ]);
    }
}
