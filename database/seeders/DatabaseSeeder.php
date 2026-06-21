<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,Service,Project,Quotation,Customer,Testimonial,Article,SiteSetting,WorkOrder,PayRate,Employee,PayrollPeriod,PayrollItem,CompanyHoliday,AttendanceRecord,DailyWorkSummary,AdvancePayment,AdminNotification,WorkTeam,AuditLog};
class DatabaseSeeder extends Seeder {
 public function run(): void {
  User::updateOrCreate(['email'=>'admin@sjrope.test'], ['name'=>'SJ Super Admin','password'=>Hash::make('password'),'role'=>'super_admin','is_active'=>true]);
  User::updateOrCreate(['email'=>'staff@sjrope.test'], ['name'=>'SJ Staff','username'=>'staff','password'=>Hash::make('password'),'pin_hash'=>Hash::make('1234'),'must_change_pin'=>false,'role'=>'staff','is_active'=>true]);
  $services=[
   ['ทาสีอาคารสูง','High-rise Building Painting','high-rise-painting','งานทาสีภายนอกอาคารสูง คอนโด โรงแรม และสำนักงาน','Exterior painting for high-rise buildings, condos, hotels and offices','fas fa-paint-roller'],
   ['โรยตัวซ่อมรอยร้าว','Rope Access Crack Repair','rope-access-crack-repair','ซ่อมรอยแตกร้าว รอยรั่วซึม และผนังภายนอกอาคาร','Crack, leakage and external wall repair by rope access','fas fa-tools'],
   ['กันซึมดาดฟ้าและผนัง','Waterproofing','waterproofing','งานกันซึมดาดฟ้า รอยต่ออาคาร และผนังภายนอก','Roof deck, joint and exterior wall waterproofing','fas fa-shield-alt'],
   ['ล้างกระจกอาคารสูง','High-rise Window Cleaning','high-rise-window-cleaning','ล้างกระจกภายนอกอาคารสูงด้วยอุปกรณ์มาตรฐาน','Professional high-rise window cleaning','fas fa-spray-can'],
   ['ซ่อมบำรุงภายนอกอาคาร','Facade Maintenance','facade-maintenance','ตรวจเช็กและซ่อมบำรุงเปลือกอาคารแบบครบวงจร','Complete facade inspection and maintenance','fas fa-hard-hat'],
  ];
  foreach($services as $i=>$s){ Service::updateOrCreate(['slug'=>$s[2]], ['title_th'=>$s[0],'title_en'=>$s[1],'excerpt_th'=>$s[3],'excerpt_en'=>$s[4],'content_th'=>$s[3].' พร้อมทีมงานผ่านประสบการณ์จริง เน้นความปลอดภัยและความเรียบร้อยของหน้างาน','content_en'=>$s[4].' with experienced technicians, safety-first process and clean handover.','icon'=>$s[5],'sort_order'=>$i+1,'is_active'=>true]); }
  $painting=Service::where('slug','high-rise-painting')->first(); $repair=Service::where('slug','rope-access-crack-repair')->first();
  Project::updateOrCreate(['slug'=>'condo-exterior-painting-bangkok'], ['service_id'=>$painting?->id,'title_th'=>'ทาสีภายนอกคอนโด 18 ชั้น','title_en'=>'18-storey Condo Exterior Painting','location_th'=>'กรุงเทพมหานคร','location_en'=>'Bangkok','description_th'=>'งานทาสีภายนอกอาคารสูงด้วยระบบโรยตัว พร้อมตรวจเช็กพื้นผิวและเก็บงานละเอียด','description_en'=>'Exterior repainting by rope access with surface inspection and detailed finishing.','status'=>'completed','budget'=>320000,'sort_order'=>1,'is_featured'=>true,'is_active'=>true]);
  Project::updateOrCreate(['slug'=>'hotel-crack-repair-huahin'], ['service_id'=>$repair?->id,'title_th'=>'ซ่อมรอยร้าวผนังโรงแรม','title_en'=>'Hotel Wall Crack Repair','location_th'=>'หัวหิน','location_en'=>'Hua Hin','description_th'=>'ซ่อมรอยแตกร้าวและอุดรอยรั่วซึมผนังภายนอก ลดปัญหาน้ำซึมช่วงฝนตก','description_en'=>'Exterior crack and leak repair for hotel facade.','status'=>'completed','budget'=>95000,'sort_order'=>2,'is_featured'=>true,'is_active'=>true]);
  $c=Customer::create(['name'=>'คุณสมชาย','phone'=>'0812345678','line_id'=>'sj-demo','email'=>'demo@example.com','address'=>'กรุงเทพมหานคร']);
  Quotation::create(['quotation_no'=>'SJQ-'.date('Ymd').'-0001','customer_id'=>$c->id,'service_id'=>$painting?->id,'name'=>$c->name,'phone'=>$c->phone,'line_id'=>$c->line_id,'email'=>$c->email,'building_type'=>'คอนโด','floors'=>18,'location'=>'กรุงเทพมหานคร','address'=>'คอนโดตัวอย่าง เขตบางนา กรุงเทพมหานคร','map_link'=>'https://maps.google.com/?q=13.7563,100.5018','latitude'=>13.7563,'longitude'=>100.5018,'budget_range'=>'100,000 - 300,000 บาท','details'=>'ต้องการประเมินราคาทาสีภายนอกอาคาร','status'=>'new']);
  WorkOrder::updateOrCreate(['work_order_no'=>'SJW-'.date('Ymd').'-0001'], ['customer_id'=>$c->id,'service_id'=>$painting?->id,'customer_name'=>$c->name,'phone'=>$c->phone,'line_id'=>$c->line_id,'email'=>$c->email,'address'=>'คอนโดตัวอย่าง เขตบางนา กรุงเทพมหานคร','map_link'=>'https://maps.google.com/?q=13.7563,100.5018','latitude'=>13.7563,'longitude'=>100.5018,'job_type'=>'คอนโด','floors'=>18,'details'=>'ใบงานตัวอย่างสำหรับนัดสำรวจหน้างาน','team_leader'=>'หัวหน้าทีม SJ','status'=>'pending_survey']);
  Testimonial::create(['customer_name'=>'นิติบุคคลคอนโด A','company'=>'Condo A','message_th'=>'ทีมงานตรงเวลา ทำงานปลอดภัย และเก็บงานเรียบร้อยมาก','message_en'=>'On time, safe and very clean handover.','rating'=>5,'is_active'=>true]);
  Article::create(['title_th'=>'เลือกผู้รับเหมาทาสีอาคารสูงอย่างไรให้ปลอดภัย','title_en'=>'How to Choose a Safe High-rise Painting Contractor','slug'=>'choose-safe-high-rise-painting-contractor','excerpt_th'=>'เช็กลิสต์ก่อนจ้างงานโรยตัวและทาสีอาคารสูง','excerpt_en'=>'Checklist before hiring a rope access painting contractor.','content_th'=>'ควรตรวจสอบประสบการณ์ อุปกรณ์นิรภัย ขั้นตอนประเมินหน้างาน และความชัดเจนของใบเสนอราคา','content_en'=>'Check experience, PPE, site survey process and clear quotation details.','status'=>'published','published_at'=>now()]);
  $settings=[
   'company_name'=>'บริษัท เอสเจ ทาสีโรยตัว จำกัด',
   'company_name_en'=>'SJ Rope Access Painting Co., Ltd.',
   'phone'=>'081-353-7779',
   'line_id'=>'@sjrope',
   'facebook'=>'SJ Rope Painting',
   'email'=>'contact@sjrope.test',
   'admin_theme_color'=>'#082B5B','admin_secondary_color'=>'#1E6BFF','admin_accent_color'=>'#F4B400','site_theme_color'=>'#082B5B','site_secondary_color'=>'#1E6BFF','site_accent_color'=>'#F4B400','admin_appearance'=>'light',
   'contact_phones'=>json_encode([
     ['label'=>'ฝ่ายขาย','label_en'=>'Sales Department','number'=>'081-353-7779','person'=>'คุณอิ้ด','person_en'=>'Mr. Id'],
     ['label'=>'ประสานงาน','label_en'=>'Coordinator','number'=>'092-284-5996','person'=>'คุณก้อย','person_en'=>'Ms. Koy']
   ], JSON_UNESCAPED_UNICODE),
   'social_links'=>json_encode([
     ['name'=>'Facebook','url'=>'https://facebook.com/sjrope'],
     ['name'=>'LINE','url'=>'@sjrope'],
     ['name'=>'Google Maps','url'=>'https://maps.app.goo.gl/9ymreGzdu7VUAVN99']
   ], JSON_UNESCAPED_UNICODE),
   'google_maps_link'=>'https://maps.app.goo.gl/9ymreGzdu7VUAVN99',
   'address_th'=>'1309/15 (ทรายใต้) ถ.ชลประทาน ต.ชะอำ อ.ชะอำ จ.เพชรบุรี 76120',
   'address_en'=>'1309/15 (Sai Tai), Chonprathan Road, Cha-am Subdistrict, Cha-am District, Phetchaburi 76120',
   'service_area_th'=>'หัวหิน-ชะอำ (พื้นที่หลัก)\nกรุงเทพมหานคร\nชลบุรี',
   'service_area_en'=>'Hua Hin–Cha-am (Primary Area)\nBangkok\nChonburi',
   'logo_path'=>'images/sj-v9/sj-logo-main.png',
   'logo_nav_th_path'=>'images/sj-v9/sj-navbar-logo.png','logo_nav_en_path'=>'images/sj-v9/sj-navbar-logo.png',
   'favicon_path'=>'images/sj-v9/sj-favicon.png','footer_logo_path'=>'images/sj-v9/sj-footer-logo.png','og_image_path'=>'images/sj-v9/sj-branding-board.png',
   'quotation_company_info'=>'บริษัท เอสเจ ทาสีโรยตัว จำกัด\n081-353-7779 คุณอิ้ด | 092-284-5996 คุณก้อย\n1309/15 (ทรายใต้) ถ.ชลประทาน ต.ชะอำ อ.ชะอำ จ.เพชรบุรี 76120',
   'quotation_terms'=>'ใบเสนอราคามีอายุ 15 วัน\nราคาจริงขึ้นอยู่กับการสำรวจหน้างาน\nทีมงานจะติดต่อกลับเพื่อยืนยันรายละเอียด',
   'hero_title_th'=>'งานทาสีโรยตัว มาตรฐานสูง ปลอดภัย ใส่ใจคุณภาพ','hero_title_en'=>'Rope Access Painting with Safety and Quality Standards',
   'hero_subtitle_th'=>'ทีมงานคุณภาพ ผ่านประสบการณ์หน้างานจริง พร้อมสำรวจหน้างานและประเมินราคาอย่างชัดเจน',
   'hero_subtitle_en'=>'Quality team with real on-site experience. Clear site survey, quotation and professional handover.',
   'meta_description_th'=>'บริษัท เอสเจ ทาสีโรยตัว จำกัด รับงานทาสีอาคารสูง โรยตัวซ่อมอาคาร กันซึม ล้างกระจก พื้นที่หลักหัวหิน-ชะอำ กรุงเทพฯ และชลบุรี',
   'meta_description_en'=>'SJ Rope Access Painting Co., Ltd. provides high-rise painting, rope access repair, waterproofing and window cleaning in Hua Hin, Cha-am, Bangkok and Chonburi.'
  ];
  foreach($settings as $k=>$v){ SiteSetting::put($k,$v); }

  foreach([360,400,420,500,550] as $i=>$amount){
   PayRate::updateOrCreate(['amount'=>$amount], ['name'=>'ค่าแรง '.number_format($amount).' บาท','is_active'=>true,'sort_order'=>$i+1]);
  }
  $rate500=PayRate::where('amount',500)->first(); $rate550=PayRate::where('amount',550)->first(); $rate400=PayRate::where('amount',400)->first(); $rate360=PayRate::where('amount',360)->first();
  $employees=[
   ['ช่างดุด','ดุด','head_technician','หัวหน้าช่าง',$rate550?->id,550],
   ['ช่างมาร์ค','มาร์ค','daily_technician','ช่างรายวัน',$rate500?->id,500],
   ['ช่างเวฟ','เวฟ','daily_technician','ช่างรายวัน',$rate500?->id,500],
   ['ช่างกบ','กบ','daily_technician','ช่างรายวัน',$rate500?->id,500],
   ['ช่างชล','ชล','daily_technician','ช่างรายวัน',$rate500?->id,500],
   ['ช่างภูมิ','ภูมิ','head_technician','หัวหน้าช่าง / ผู้สร้างระบบ',$rate550?->id,550],
   ['ช่างอิ้ด','อิ้ด','owner','หัวหน้าช่าง / เจ้าของกิจการ',$rate550?->id,550],
   ['ใบเตย','ใบเตย','helper','ผู้ช่วย / กรรมกร',$rate360?->id,360],
   ['เหมย','เหมย','helper','ผู้ช่วย / กรรมกร',$rate360?->id,360],
   ['ก้อย','ก้อย','accounting_admin','บัญชี / เลขา / ผู้จัดการ',$rate400?->id,400],
  ];
  $staffUsernames = ['ดุด'=>'dud','มาร์ค'=>'mark','เวฟ'=>'wave','กบ'=>'kob','ชล'=>'chon','ภูมิ'=>'phum','อิ้ด'=>'id','ใบเตย'=>'baitoey','เหมย'=>'moei','ก้อย'=>'koy'];
  foreach($employees as $i=>$e){
   $username = $staffUsernames[$e[1]] ?? ('staff'.($i+1));
   $user = User::updateOrCreate(
    ['email'=>'staff'.($i+1).'@sjrope.test'],
    ['name'=>$e[0],'username'=>$username,'password'=>Hash::make('password'),'pin_hash'=>Hash::make('1234'),'must_change_pin'=>false,'pin_failed_attempts'=>0,'pin_locked_until'=>null,'role'=>'staff','is_active'=>true]
   );
   Employee::updateOrCreate(['name'=>$e[0]], ['user_id'=>$user->id,'nickname'=>$e[1],'employee_type'=>$e[2],'position_note'=>$e[3],'default_pay_rate_id'=>$e[4],'daily_wage'=>$e[5],'is_active'=>true,'sort_order'=>$i+1]);
  }
  $now=now();
  $pMay = PayrollPeriod::updateOrCreate(['start_date'=>'2026-05-16','end_date'=>'2026-05-31'], ['name'=>'รอบจ่าย 16-31/05/2569','status'=>'confirmed']);
  $pJun = PayrollPeriod::updateOrCreate(['start_date'=>'2026-06-01','end_date'=>'2026-06-15'], ['name'=>'รอบจ่าย 1-15/06/2569','status'=>'draft']);
  PayrollPeriod::firstOrCreate(['start_date'=>$now->copy()->startOfMonth()->toDateString(),'end_date'=>$now->copy()->day(15)->toDateString()], ['name'=>'รอบจ่าย 1-15 '.$now->format('m/Y')]);
  PayrollPeriod::firstOrCreate(['start_date'=>$now->copy()->day(16)->toDateString(),'end_date'=>$now->copy()->endOfMonth()->toDateString()], ['name'=>'รอบจ่าย 16-'.$now->copy()->endOfMonth()->day.' '.$now->format('m/Y')]);
  CompanyHoliday::firstOrCreate(['holiday_date'=>'2026-06-03','title_th'=>'วันหยุดนักขัตฤกษ์ตัวอย่าง'], ['title_en'=>'Public holiday example','type'=>'public_holiday','is_active'=>true]);
  CompanyHoliday::firstOrCreate(['holiday_date'=>'2026-06-15','title_th'=>'ปิดรอบจ่าย 1-15'], ['title_en'=>'Payroll closing','type'=>'payroll','is_active'=>true]);
  $sampleUnitsMay=['ช่างดุด'=>15,'ช่างมาร์ค'=>14,'ช่างเวฟ'=>14,'ช่างกบ'=>13,'ช่างชล'=>14,'ช่างภูมิ'=>15,'ช่างอิ้ด'=>15,'ใบเตย'=>12,'เหมย'=>12,'ก้อย'=>15];
  $sampleUnitsJun=['ช่างดุด'=>13,'ช่างมาร์ค'=>12,'ช่างเวฟ'=>13,'ช่างกบ'=>11,'ช่างชล'=>12,'ช่างภูมิ'=>13,'ช่างอิ้ด'=>13,'ใบเตย'=>10,'เหมย'=>10,'ก้อย'=>13];
  foreach(Employee::all() as $emp){
   foreach([['period'=>$pMay,'start'=>'2026-05-16','units'=>$sampleUnitsMay[$emp->name] ?? 10],['period'=>$pJun,'start'=>'2026-06-01','units'=>$sampleUnitsJun[$emp->name] ?? 10]] as $pack){
    $workUnits=$pack['units']; $daily=(float)$emp->daily_wage; $advance= in_array($emp->nickname,['มาร์ค','เวฟ','ใบเตย']) ? 500 : (in_array($emp->nickname,['กบ','ชล']) ? 200 : 0);
    PayrollItem::updateOrCreate(['payroll_period_id'=>$pack['period']->id,'employee_id'=>$emp->id], ['daily_wage'=>$daily,'work_units'=>$workUnits,'gross_amount'=>$daily*$workUnits,'bonus_amount'=>0,'deduction_amount'=>0,'advance_amount'=>$advance,'net_amount'=>($daily*$workUnits)-$advance,'late_count'=>($emp->nickname==='กบ'?2:($emp->nickname==='มาร์ค'?1:0))]);
   }
  }
  $demoEmp=Employee::where('nickname','มาร์ค')->first();
  if($demoEmp){
   AttendanceRecord::updateOrCreate(['employee_id'=>$demoEmp->id,'work_date'=>'2026-06-18'], ['check_in_at'=>'2026-06-18 07:36:00','is_late'=>true,'late_minutes'=>6,'status'=>'checked_in','note'=>'ตัวอย่างเข้างานสาย']);
   AdvancePayment::updateOrCreate(['employee_id'=>$demoEmp->id,'request_date'=>'2026-06-18','amount'=>500], ['requested_by'=>User::where('email','staff2@sjrope.test')->value('id'),'status'=>'pending','reason'=>'ค่าอาหาร/ค่าเดินทาง']);
   AdminNotification::firstOrCreate(['type'=>'advance_requested','title'=>'มีคำขอเบิกเงินใหม่','message'=>'ช่างมาร์ค ขอเบิก 500 บาท'], ['url'=>'/admin/advances']);
  }

  // V8 ทีมช่าง + ตัวอย่าง KPI + PIN login
  $teamDud = WorkTeam::updateOrCreate(['name'=>'ทีมช่างดุด'], ['foreman_employee_id'=>Employee::where('nickname','ดุด')->value('id'),'color'=>'#0d6efd','is_active'=>true,'sort_order'=>1]);
  $teamPhum = WorkTeam::updateOrCreate(['name'=>'ทีมช่างภูมิ'], ['foreman_employee_id'=>Employee::where('nickname','ภูมิ')->value('id'),'color'=>'#198754','is_active'=>true,'sort_order'=>2]);
  $teamId = WorkTeam::updateOrCreate(['name'=>'ทีมช่างอิ้ด'], ['foreman_employee_id'=>Employee::where('nickname','อิ้ด')->value('id'),'color'=>'#fd7e14','is_active'=>true,'sort_order'=>3]);
  $teamDud->members()->sync(Employee::whereIn('nickname',['ดุด','มาร์ค','เวฟ'])->pluck('id')->all());
  $teamPhum->members()->sync(Employee::whereIn('nickname',['ภูมิ','กบ','ชล'])->pluck('id')->all());
  $teamId->members()->sync(Employee::whereIn('nickname',['อิ้ด','ใบเตย','เหมย','ก้อย'])->pluck('id')->all());
  WorkOrder::where('work_order_no','SJW-'.date('Ymd').'-0001')->update(['work_team_id'=>$teamDud->id,'income_amount'=>45000,'labor_cost'=>12000,'material_cost'=>8500,'other_cost'=>1500]);
  $sampleLate = ['มาร์ค'=>4,'กบ'=>3,'เวฟ'=>2,'ชล'=>1];
  $sampleAbsent = ['เวฟ'=>3,'มาร์ค'=>2,'กบ'=>1];
  foreach($sampleLate as $nick=>$count){
      $emp=Employee::where('nickname',$nick)->first();
      if($emp){ for($i=1;$i<=$count;$i++){ $date='2026-06-'.str_pad((string)(2+$i),2,'0',STR_PAD_LEFT); AttendanceRecord::updateOrCreate(['employee_id'=>$emp->id,'work_date'=>$date], ['check_in_at'=>$date.' 07:3'.min($i,9).':00','is_late'=>true,'late_minutes'=>3+$i,'status'=>'checked_in','note'=>'ตัวอย่าง Top สาย V8']); }}
  }
  foreach($sampleAbsent as $nick=>$count){
      $emp=Employee::where('nickname',$nick)->first();
      if($emp){ for($i=1;$i<=$count;$i++){ $date='2026-06-'.str_pad((string)(7+$i),2,'0',STR_PAD_LEFT); DailyWorkSummary::updateOrCreate(['employee_id'=>$emp->id,'work_date'=>$date], ['work_unit'=>0,'day_status'=>'absent','reason'=>'ขาดงานตัวอย่าง','approved_at'=>now()]); }}
  }
  foreach(Employee::all() as $emp){
      foreach(range(1,15) as $day){
          $date='2026-06-'.str_pad((string)$day,2,'0',STR_PAD_LEFT);
          if(!DailyWorkSummary::where('employee_id',$emp->id)->where('work_date',$date)->exists()){
              DailyWorkSummary::create(['employee_id'=>$emp->id,'work_date'=>$date,'work_unit'=>in_array($emp->nickname,['ใบเตย','เหมย']) && $day%5==0 ? 0.5 : 1,'day_status'=>'full_day','approved_at'=>now()]);
          }
      }
  }
  $mark=Employee::where('nickname','มาร์ค')->first(); if($mark){ AdvancePayment::updateOrCreate(['employee_id'=>$mark->id,'request_date'=>'2026-06-10','amount'=>1500], ['status'=>'pending','reason'=>'เบิกเกินตัวอย่าง','is_special_request'=>true,'special_request_note'=>'เกิน 1,000 บาท ต้องให้เจ้าของอนุมัติพิเศษ']); AdminNotification::firstOrCreate(['type'=>'advance_special_requested','title'=>'มีคำขอเบิกเกิน 1,000 บาท','message'=>'ช่างมาร์ค ขอเบิก 1,500 บาท'], ['url'=>'/admin/advances']); }
  AuditLog::firstOrCreate(['action'=>'seed','module'=>'v8'], ['note'=>'สร้างข้อมูลตัวอย่าง V8: ทีมช่าง, Top สาย, Top ขาดงาน, เงินเบิกพิเศษ']);
 }
}
