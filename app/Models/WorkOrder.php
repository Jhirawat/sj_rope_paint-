<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WorkOrder extends Model {
    protected $fillable=['work_order_no','quotation_id','customer_id','service_id','work_team_id','customer_name','phone','line_id','email','address','map_link','latitude','longitude','job_type','floors','details','income_amount','material_cost','labor_cost','other_cost','team_leader','team_members','scheduled_survey_at','start_at','finish_at','accepted_at','status','admin_note','customer_signature_path','foreman_signature_path','inspector_signature_path','last_checkin_at','last_checkin_latitude','last_checkin_longitude'];
    protected $casts=['scheduled_survey_at'=>'datetime','start_at'=>'date','finish_at'=>'date','accepted_at'=>'datetime','last_checkin_at'=>'datetime'];
    public function quotation(){ return $this->belongsTo(Quotation::class); }
    public function customer(){ return $this->belongsTo(Customer::class); }
    public function service(){ return $this->belongsTo(Service::class); }
    public function workTeam(){ return $this->belongsTo(WorkTeam::class); }
    public function profitAmount(): float { return (float)$this->income_amount - (float)$this->material_cost - (float)$this->labor_cost - (float)$this->other_cost; }
    public function images(){ return $this->hasMany(WorkOrderImage::class)->orderBy('sort_order'); }
    public function statusLogs(){ return $this->hasMany(WorkOrderStatusLog::class)->latest(); }
    public function checkins(){ return $this->hasMany(WorkOrderCheckin::class)->latest(); }
    public function mapUrl(): string { if($this->map_link) return $this->map_link; if($this->latitude && $this->longitude) return 'https://www.google.com/maps?q='.$this->latitude.','.$this->longitude; return '#'; }
    public function lineUrl(): ?string { if(!$this->line_id) return null; $line=ltrim(trim($this->line_id),'@'); return 'https://line.me/ti/p/~'.$line; }
    public function statusText(): string { return ['pending_survey'=>'รอสำรวจหน้างาน','surveyed'=>'สำรวจแล้ว','waiting_start'=>'รอเริ่มงาน','working'=>'กำลังดำเนินการ','inspection'=>'รอตรวจรับ','completed'=>'เสร็จสิ้น','cancelled'=>'ยกเลิก'][$this->status] ?? $this->status; }
}
