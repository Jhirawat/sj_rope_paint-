<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{CompanyHoliday,AdvancePayment,PayrollPeriod,WorkOrder};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StaffCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();
        return view('admin.calendar.index', [
            'month'=>$month,
            'days'=>collect(range(1,$end->day))->map(fn($d)=>$start->copy()->day($d)),
            'holidays'=>CompanyHoliday::whereBetween('holiday_date',[$start,$end])->get()->groupBy(fn($h)=>$h->holiday_date->toDateString()),
            'advances'=>AdvancePayment::with('employee')->whereBetween('request_date',[$start,$end])->get()->groupBy(fn($a)=>$a->request_date->toDateString()),
            'periods'=>PayrollPeriod::whereBetween('start_date',[$start,$end])->orWhereBetween('end_date',[$start,$end])->get(),
            'workOrders'=>WorkOrder::whereBetween('start_at',[$start,$end])->orWhereBetween('scheduled_survey_at',[$start,$end])->get(),
        ]);
    }

    public function storeHoliday(Request $request)
    {
        $data = $request->validate([
            'holiday_date'=>'required|date','title_th'=>'required|string|max:255','title_en'=>'nullable|string|max:255','type'=>'required|in:public_holiday,company_holiday,payroll,work_event','note'=>'nullable|string|max:1000'
        ]);
        $data['is_active'] = true;
        CompanyHoliday::create($data);
        return back()->with('success','เพิ่มวันหยุด/กิจกรรมในปฏิทินแล้ว');
    }
}
