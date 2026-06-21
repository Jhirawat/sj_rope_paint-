<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Service,Project,Quotation,Customer,Article,Testimonial,WorkOrder,VisitorLog,AdminNotification,Employee,AttendanceRecord,AdvancePayment,PayrollPeriod,DailyWorkSummary};
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $monthlyQuotes=Quotation::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, count(*) as total')->groupBy('ym')->orderBy('ym')->take(12)->get();
        $topPages=VisitorLog::selectRaw('path, count(*) as total')->groupBy('path')->orderByDesc('total')->take(8)->get();
        $monthStart=today()->startOfMonth(); $monthEnd=today()->endOfMonth();
        $employees=Employee::where('is_active',1)->orderBy('sort_order')->get();
        $workUnits=DailyWorkSummary::selectRaw('employee_id, sum(work_unit) as total_units')->whereBetween('work_date',[$monthStart,$monthEnd])->groupBy('employee_id')->pluck('total_units','employee_id');
        $lateCounts=AttendanceRecord::selectRaw('employee_id, count(*) as total')->whereBetween('work_date',[$monthStart,$monthEnd])->where('is_late',1)->groupBy('employee_id')->pluck('total','employee_id');
        $absentCounts=DailyWorkSummary::selectRaw('employee_id, count(*) as total')->whereBetween('work_date',[$monthStart,$monthEnd])->where('day_status','absent')->groupBy('employee_id')->pluck('total','employee_id');
        $advanceTotals=AdvancePayment::selectRaw('employee_id, sum(amount) as total')->whereBetween('request_date',[$monthStart,$monthEnd])->whereIn('status',['pending','approved','paid'])->groupBy('employee_id')->pluck('total','employee_id');
        $kpiRows=$employees->map(fn($e)=>(object)['employee'=>$e,'work_units'=>(float)($workUnits[$e->id]??0),'late_count'=>(int)($lateCounts[$e->id]??0),'absent_count'=>(int)($absentCounts[$e->id]??0),'advance_total'=>(float)($advanceTotals[$e->id]??0)]);
        return view('admin.dashboard',[
            'serviceCount'=>Service::count(),'projectCount'=>Project::count(),'quotationCount'=>Quotation::count(),'customerCount'=>Customer::count(),
            'todayQuotes'=>Quotation::whereDate('created_at',today())->count(),
            'uncontactedQuotes'=>Quotation::where('status','new')->count(),
            'followUpQuotes'=>Quotation::whereIn('status',['contacted','survey_scheduled','quoted'])->count(),
            'workOrderCount'=>WorkOrder::count(),'openWorkOrders'=>WorkOrder::whereNotIn('status',['completed','cancelled'])->count(),
            'workingCount'=>WorkOrder::where('status','working')->count(),'completedWorkOrders'=>WorkOrder::where('status','completed')->count(),
            'activeProjectCount'=>Project::where('is_active',1)->count(),
            'visitorCount'=>VisitorLog::count(), 'todayVisitorCount'=>VisitorLog::whereDate('created_at',today())->count(), 'topPages'=>$topPages,
            'newQuotes'=>Quotation::where('status','new')->count(),'acceptedQuotes'=>Quotation::where('status','accepted')->count(),
            'latestQuotes'=>Quotation::with('service')->latest()->take(8)->get(),'latestWorkOrders'=>WorkOrder::with('service','workTeam')->latest()->take(6)->get(),
            'surveyTomorrow'=>WorkOrder::whereDate('scheduled_survey_at',today()->addDay())->whereNotIn('status',['completed','cancelled'])->get(),
            'startTomorrow'=>WorkOrder::whereDate('start_at',today()->addDay())->whereNotIn('status',['completed','cancelled'])->get(),
            'overdueWorkOrders'=>WorkOrder::whereDate('finish_at','<',today())->whereNotIn('status',['completed','cancelled'])->get(),
            'notifications'=>AdminNotification::latest()->take(10)->get(),
            'unreadNotifications'=>AdminNotification::whereNull('read_at')->count(),
            'pendingAdvances'=>AdvancePayment::with('employee')->where('status','pending')->latest('request_date')->take(8)->get(),
            'specialAdvances'=>AdvancePayment::with('employee')->where('is_special_request',1)->where('status','pending')->latest('request_date')->get(),
            'todayAttendance'=>AttendanceRecord::with('employee')->whereDate('work_date',today())->latest('check_in_at')->get(),
            'todayAttendanceCount'=>AttendanceRecord::whereDate('work_date',today())->count(),
            'todayLateCount'=>AttendanceRecord::whereDate('work_date',today())->where('is_late',true)->count(),
            'todayAbsentCount'=>DailyWorkSummary::whereDate('work_date',today())->where('day_status','absent')->count(),
            'todayWorkUnits'=>DailyWorkSummary::whereDate('work_date',today())->sum('work_unit'),
            'activeEmployeeCount'=>Employee::where('is_active',1)->count(),
            'currentPayrollPeriod'=>PayrollPeriod::where('start_date','<=',today())->where('end_date','>=',today())->first(),
            'topHardWorkers'=>$kpiRows->sortByDesc('work_units')->take(3),
            'topLate'=>$kpiRows->sortByDesc('late_count')->take(3),
            'topAbsent'=>$kpiRows->sortByDesc('absent_count')->take(3),
            'topAdvance'=>$kpiRows->sortByDesc('advance_total')->take(3),
            'hallOfFame'=>$kpiRows->filter(fn($r)=>$r->work_units>0 && $r->late_count===0 && $r->absent_count===0)->sortByDesc('work_units')->take(3),
            'articleCount'=>Article::count(),'reviewCount'=>Testimonial::count(),'monthlyQuotes'=>$monthlyQuotes
        ]);
    }
}
