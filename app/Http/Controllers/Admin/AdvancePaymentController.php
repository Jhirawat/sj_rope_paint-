<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{AdvancePayment,Employee,AdminNotification};
use App\Support\AuditTrail;
use Illuminate\Http\Request;

class AdvancePaymentController extends Controller
{
    public function index(Request $request)
    {
        $periodStart = now()->day <= 15 ? now()->copy()->startOfMonth() : now()->copy()->day(16);
        $periodEnd = now()->day <= 15 ? now()->copy()->day(15) : now()->copy()->endOfMonth();
        $advancesThisPeriod = AdvancePayment::whereBetween('request_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->whereIn('status',['approved','paid','pending'])->get()->groupBy('employee_id');
        return view('admin.advances.index', [
            'payments' => AdvancePayment::with('employee','approver')->latest('request_date')->paginate(40),
            'employees' => Employee::where('is_active', true)->orderBy('sort_order')->get(),
            'advancesThisPeriod' => $advancesThisPeriod,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id'=>'required|exists:employees,id',
            'amount'=>'nullable|numeric|min:100|max:100000',
            'preset_amount'=>'nullable|numeric|in:100,200,500,1000',
            'request_date'=>'required|date',
            'reason'=>'nullable|string|max:255',
            'note'=>'nullable|string|max:1000',
        ]);
        $amount = (float)($data['amount'] ?: $data['preset_amount'] ?: 0);
        if ($amount <= 0) return back()->withErrors('กรุณาระบุจำนวนเงินเบิก');
        $existing = AdvancePayment::where('employee_id',$data['employee_id'])
            ->whereBetween('request_date', $this->periodRange($data['request_date']))
            ->whereIn('status',['approved','paid','pending'])->sum('amount');
        $special = ($existing + $amount) > 1000 || $amount > 1000;
        $advance = AdvancePayment::create([
            'employee_id'=>$data['employee_id'], 'requested_by'=>auth()->id(), 'amount'=>$amount,
            'request_date'=>$data['request_date'], 'reason'=>$data['reason'] ?? null, 'note'=>$data['note'] ?? null,
            'status'=>'pending', 'is_special_request'=>$special,
            'special_request_note'=>$special ? 'ยอดเบิกเกิน 1,000 บาท/รอบ ต้องให้เจ้าของอนุมัติพิเศษ' : null,
        ]);
        $employee = Employee::find($data['employee_id']);
        AdminNotification::create([
            'type'=>$special?'advance_special_requested':'advance_requested',
            'title'=>$special?'มีคำขอเบิกเกิน 1,000 บาท':'มีรายการเบิกเงินใหม่',
            'message'=>($employee?->name ?: 'พนักงาน').' เบิก '.number_format($advance->amount).' บาท'.($special?' (รออนุมัติพิเศษ)':''),
            'url'=>route('admin.advances.index')
        ]);
        AuditTrail::log('create','advance_payments',$advance,[], $advance->toArray(), 'บันทึกคำขอเบิกเงิน');
        return back()->with('success',$special?'บันทึกคำขอเบิกเกิน 1,000 แล้ว รอเจ้าของอนุมัติพิเศษ':'บันทึกคำขอเบิกเงินแล้ว');
    }

    public function approve(AdvancePayment $advance)
    {
        $old=$advance->toArray();
        $advance->update(['status'=>'approved','approved_by'=>auth()->id(),'approved_at'=>now()]);
        AuditTrail::log('approve','advance_payments',$advance,$old,$advance->toArray(),'อนุมัติเงินเบิก');
        AdminNotification::create(['type'=>'advance_approved','title'=>'อนุมัติเงินเบิกแล้ว','message'=>($advance->employee?->name ?: 'พนักงาน').' ได้รับอนุมัติ '.number_format($advance->amount).' บาท','url'=>route('admin.advances.index')]);
        return back()->with('success','อนุมัติเงินเบิกแล้ว');
    }

    public function reject(Request $request, AdvancePayment $advance)
    {
        $old=$advance->toArray();
        $advance->update(['status'=>'rejected','approved_by'=>auth()->id(),'approved_at'=>now(),'note'=>trim(($advance->note ? $advance->note."\n" : '').($request->input('note','ไม่อนุมัติ')))]);
        AuditTrail::log('reject','advance_payments',$advance,$old,$advance->toArray(),'ไม่อนุมัติเงินเบิก');
        return back()->with('success','ไม่อนุมัติเงินเบิกแล้ว');
    }

    public function markPaid(AdvancePayment $advance)
    {
        if ($advance->status !== 'approved') return back()->withErrors('ต้องอนุมัติก่อนจึงจะบันทึกว่าจ่ายแล้วได้');
        $old=$advance->toArray();
        $advance->update(['status'=>'paid']);
        AuditTrail::log('paid','advance_payments',$advance,$old,$advance->toArray(),'บันทึกจ่ายเงินเบิก');
        return back()->with('success','บันทึกว่าจ่ายเงินเบิกแล้ว');
    }

    private function periodRange(string $date): array
    {
        $d = \Illuminate\Support\Carbon::parse($date);
        if ($d->day <= 15) return [$d->copy()->startOfMonth()->toDateString(), $d->copy()->day(15)->toDateString()];
        return [$d->copy()->day(16)->toDateString(), $d->copy()->endOfMonth()->toDateString()];
    }
}
