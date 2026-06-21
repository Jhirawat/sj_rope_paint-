<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Service,Project,Quotation,Customer,Testimonial,Article,SiteSetting,WorkOrder,WorkOrderStatusLog,WorkOrderCheckin,AdminNotification};
use Illuminate\Http\Response;

class BackupController extends Controller
{
    public function index(){ return view('admin.backup.index'); }
    public function exportJson(): Response
    {
        $payload = ['exported_at'=>now()->toDateTimeString(),'app'=>'SJ Rope Painting V4','services'=>Service::with('images')->get(),'projects'=>Project::with('images')->get(),'quotations'=>Quotation::with(['customer','service','images'])->get(),'customers'=>Customer::all(),'work_orders'=>WorkOrder::with(['images','statusLogs','checkins'])->get(),'testimonials'=>Testimonial::all(),'articles'=>Article::all(),'settings'=>SiteSetting::all(),'work_order_status_logs'=>WorkOrderStatusLog::all(),'work_order_checkins'=>WorkOrderCheckin::all(),'admin_notifications'=>AdminNotification::all()];
        return response(json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT), 200, ['Content-Type'=>'application/json; charset=UTF-8','Content-Disposition'=>'attachment; filename="sj-rope-v4-backup-'.now()->format('Ymd-His').'.json"']);
    }
    public function exportQuotationsCsv(): Response { return $this->csv('quotations-v4', Quotation::latest()->get(['quotation_no','name','phone','line_id','location','status','created_at'])->toArray()); }
    public function exportCustomersCsv(): Response { return $this->csv('customers-v4', Customer::latest()->get(['name','phone','line_id','email','address','created_at'])->toArray()); }
    public function exportWorkOrdersCsv(): Response { return $this->csv('work-orders-v4', WorkOrder::latest()->get(['work_order_no','customer_name','phone','address','status','start_at','finish_at'])->toArray()); }
    private function csv(string $name, array $rows): Response
    {
        $out=fopen('php://temp','r+'); if($rows){ fputcsv($out,array_keys($rows[0])); foreach($rows as $row){ fputcsv($out,$row); } }
        rewind($out); $csv=stream_get_contents($out); fclose($out);
        return response($csv,200,['Content-Type'=>'text/csv; charset=UTF-8','Content-Disposition'=>'attachment; filename="'.$name.'-'.now()->format('Ymd-His').'.csv"']);
    }
}
