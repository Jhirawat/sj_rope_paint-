<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Employee,PayRate,User};
use App\Support\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        return view('admin.staff.index', [
            'employees' => Employee::with('payRate','user')->orderBy('sort_order')->orderBy('id')->paginate(30),
            'payRates' => PayRate::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
    public function create(){ return view('admin.staff.form', ['employee'=>new Employee(),'payRates'=>PayRate::where('is_active', true)->orderBy('sort_order')->get()]); }
    public function store(Request $request)
    {
        $data = $this->validateEmployee($request);
        $login = $this->validateLogin($request);
        $user = null;
        if (!empty($login['username'])) {
            $user = User::create(['name'=>$data['name'],'email'=>$login['email'] ?: $login['username'].'@sj.local','username'=>$login['username'],'password'=>Hash::make($login['password'] ?: 'password'),'pin_hash'=>Hash::make($login['pin'] ?: '1234'),'must_change_pin'=>false,'role'=>'staff','is_active'=>true]);
            $data['user_id']=$user->id;
        }
        $employee = Employee::create($data);
        AuditTrail::log('create','employees',$employee,[], $employee->toArray(),'เพิ่มพนักงาน');
        return redirect()->route('admin.staff.index')->with('success','เพิ่มพนักงานเรียบร้อยแล้ว');
    }
    public function edit(Employee $staff){ return view('admin.staff.form', ['employee'=>$staff->load('user'),'payRates'=>PayRate::where('is_active', true)->orderBy('sort_order')->get()]); }
    public function update(Request $request, Employee $staff)
    {
        $old=$staff->toArray();
        $data=$this->validateEmployee($request,$staff);
        $login=$this->validateLogin($request,$staff);
        $staff->update($data);
        if (!empty($login['username'])) {
            $user = $staff->user ?: new User(['role'=>'staff','is_active'=>true]);
            $user->name=$data['name'];
            $user->email=$login['email'] ?: $login['username'].'@sj.local';
            $user->username=$login['username'];
            $user->role='staff';
            $user->is_active=$request->boolean('is_active', true);
            if (!empty($login['password'])) $user->password=Hash::make($login['password']);
            if (!empty($login['pin'])) $user->pin_hash=Hash::make($login['pin']);
            $user->save();
            if (!$staff->user_id) $staff->update(['user_id'=>$user->id]);
        }
        AuditTrail::log('update','employees',$staff,$old,$staff->toArray(),'แก้ไขพนักงาน');
        return redirect()->route('admin.staff.index')->with('success','แก้ไขพนักงานเรียบร้อยแล้ว');
    }
    public function destroy(Employee $staff)
    {
        if ($staff->attendanceRecords()->exists() || $staff->dailyWorkSummaries()->exists() || $staff->advancePayments()->exists()) return back()->withErrors('ไม่สามารถลบพนักงานที่มีประวัติงาน/ค่าแรง/เงินเบิกได้ ให้ปิดใช้งานแทน');
        $old=$staff->toArray(); $staff->delete(); AuditTrail::log('delete','employees',null,$old,[],'ลบพนักงาน');
        return back()->with('success','ลบพนักงานแล้ว');
    }
    public function payRates(){ return view('admin.staff.pay-rates', ['payRates'=>PayRate::orderBy('sort_order')->orderBy('amount')->get()]); }
    public function storePayRate(Request $request)
    {
        $data=$request->validate(['name'=>'nullable|string|max:100','amount'=>'required|numeric|min:1|max:10000','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);
        $data['name']=$data['name'] ?: 'ค่าแรง '.number_format((float)$data['amount'],0).' บาท'; $data['is_active']=$request->boolean('is_active', true);
        $rate=PayRate::create($data); AuditTrail::log('create','pay_rates',$rate,[],$rate->toArray(),'เพิ่มค่าแรง');
        return back()->with('success','เพิ่มค่าแรงเรียบร้อยแล้ว');
    }
    public function updatePayRate(Request $request, PayRate $payRate)
    {
        $old=$payRate->toArray();
        $data=$request->validate(['name'=>'required|string|max:100','amount'=>'required|numeric|min:1|max:10000','sort_order'=>'nullable|integer|min:0','is_active'=>'nullable|boolean']);
        $data['is_active']=$request->boolean('is_active'); $payRate->update($data); AuditTrail::log('update','pay_rates',$payRate,$old,$payRate->toArray(),'แก้ไขค่าแรง');
        return back()->with('success','แก้ไขค่าแรงเรียบร้อยแล้ว');
    }
    public function destroyPayRate(PayRate $payRate)
    {
        if ($payRate->employees()->exists()) return back()->withErrors('ไม่สามารถลบค่าแรงที่ถูกใช้กับพนักงานได้');
        $payRate->delete(); return back()->with('success','ลบค่าแรงแล้ว');
    }
    private function validateEmployee(Request $request, ?Employee $employee=null): array
    {
        $data=$request->validate(['name'=>'required|string|max:255','nickname'=>'nullable|string|max:100','employee_type'=>'required|in:head_technician,daily_technician,helper,accounting_admin,owner','position_note'=>'nullable|string|max:255','phone'=>'nullable|string|max:30','profile_photo'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120','default_pay_rate_id'=>'nullable|exists:pay_rates,id','daily_wage'=>'required|numeric|min:1|max:10000','is_active'=>'nullable|boolean','employment_status'=>'nullable|in:active,suspended,resigned','sort_order'=>'nullable|integer|min:0']);
        $data['is_active']=$request->boolean('is_active', true); $data['employment_status']=$data['employment_status'] ?? 'active';
        if ($request->hasFile('profile_photo')) $data['profile_photo']=$request->file('profile_photo')->store('employees','public');
        return $data;
    }
    private function validateLogin(Request $request, ?Employee $employee=null): array
    {
        $userId=$employee?->user_id;
        return $request->validate([
            'username'=>'nullable|string|max:50|alpha_dash|unique:users,username,'.($userId ?: 'NULL').',id',
            'login_email'=>'nullable|email|max:255|unique:users,email,'.($userId ?: 'NULL').',id',
            'password'=>'nullable|string|min:6|max:255',
            'pin'=>'nullable|digits_between:4,6',
        ]) + ['email'=>$request->input('login_email')];
    }
}
