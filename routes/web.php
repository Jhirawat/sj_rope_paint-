<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\{DashboardController,ServiceController,ProjectController,QuotationController,ArticleController,TestimonialController,SettingController,UserController,BackupController,WorkOrderController,StaffController,AttendanceController,AdvancePaymentController,PayrollController,StaffCalendarController,NotificationController,TeamController,OwnerReportController,AuditLogController};
use App\Http\Controllers\Staff\StaffPortalController;

Route::get('/language/{locale}',[ShopController::class,'language'])->name('language.switch');
Route::get('/',[ShopController::class,'home'])->name('home');
Route::get('/services',[ShopController::class,'services'])->name('services');
Route::get('/services/{service:slug}',[ShopController::class,'service'])->name('services.show');
Route::get('/projects',[ShopController::class,'projects'])->name('projects');
Route::get('/projects/{project:slug}',[ShopController::class,'project'])->name('projects.show');
Route::get('/articles',[ShopController::class,'articles'])->name('articles');
Route::get('/articles/{article:slug}',[ShopController::class,'article'])->name('articles.show');
Route::get('/quote',[ShopController::class,'quoteForm'])->name('quote.form');
Route::get('/quote/track',[ShopController::class,'trackForm'])->name('quote.track.form');
Route::post('/quote/track',[ShopController::class,'track'])->name('quote.track');
Route::post('/quote',[ShopController::class,'quoteStore'])->name('quote.store');
Route::get('/contact',[ShopController::class,'contact'])->name('contact');

Route::get('/login',[AuthController::class,'loginForm'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.post');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');


Route::middleware(['auth','role:staff,admin,super_admin'])->prefix('staff')->name('staff.')->group(function(){
 Route::get('/',[StaffPortalController::class,'dashboard'])->name('dashboard');
 Route::get('dashboard',[StaffPortalController::class,'dashboard'])->name('dashboard.alt');
 Route::post('check-in',[StaffPortalController::class,'checkIn'])->name('check-in');
 Route::post('check-out',[StaffPortalController::class,'checkOut'])->name('check-out');
 Route::get('advances',[StaffPortalController::class,'advances'])->name('advances');
 Route::post('advances',[StaffPortalController::class,'requestAdvance'])->name('advances.store');
 Route::get('payroll',[StaffPortalController::class,'payroll'])->name('payroll');
 Route::get('work-orders',[StaffPortalController::class,'workOrders'])->name('work-orders');
});

Route::middleware(['auth','role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function(){
 Route::get('/',[DashboardController::class,'index'])->name('dashboard');
 Route::get('notifications',[NotificationController::class,'index'])->name('notifications.index');
 Route::post('notifications/read-all',[NotificationController::class,'markAllRead'])->name('notifications.read-all');
 Route::post('notifications/{notification}/read',[NotificationController::class,'markRead'])->name('notifications.read');
 Route::resource('teams',TeamController::class)->except(['create','show','edit']);
 Route::get('owner-reports',[OwnerReportController::class,'index'])->name('owner-reports.index');
 Route::get('audit-logs',[AuditLogController::class,'index'])->name('audit-logs.index');
 Route::resource('services',ServiceController::class)->except(['show']);
 Route::post('services/{service}/move-up',[ServiceController::class,'moveUp'])->name('services.move-up');
 Route::post('services/{service}/move-down',[ServiceController::class,'moveDown'])->name('services.move-down');
 Route::resource('projects',ProjectController::class)->except(['show']);
 Route::post('projects/{project}/move-up',[ProjectController::class,'moveUp'])->name('projects.move-up');
 Route::post('projects/{project}/move-down',[ProjectController::class,'moveDown'])->name('projects.move-down');
 Route::get('quotations',[QuotationController::class,'index'])->name('quotations.index');
 Route::get('quotations/{quotation}',[QuotationController::class,'show'])->name('quotations.show');
 Route::patch('quotations/{quotation}',[QuotationController::class,'update'])->name('quotations.update');
 Route::post('quotations/{quotation}/create-work-order',[WorkOrderController::class,'storeFromQuotation'])->name('quotations.create-work-order');
 Route::get('work-orders/{workOrder}/print',[WorkOrderController::class,'print'])->name('work-orders.print');
 Route::post('work-orders/{workOrder}/checkin',[WorkOrderController::class,'checkin'])->name('work-orders.checkin');
 Route::post('work-orders/{workOrder}/sign',[WorkOrderController::class,'sign'])->name('work-orders.sign');
 Route::resource('work-orders',WorkOrderController::class)->except(['create','store']);
 Route::post('work-orders/{workOrder}/create-project',[ProjectController::class,'createFromWorkOrder'])->name('work-orders.create-project');
 Route::resource('articles',ArticleController::class)->except(['show']);
 Route::resource('testimonials',TestimonialController::class)->except(['show']);

 Route::resource('staff',StaffController::class)->parameters(['staff'=>'staff']);
 Route::get('pay-rates',[StaffController::class,'payRates'])->name('staff.pay-rates');
 Route::post('pay-rates',[StaffController::class,'storePayRate'])->name('pay-rates.store');
 Route::patch('pay-rates/{payRate}',[StaffController::class,'updatePayRate'])->name('pay-rates.update');
 Route::delete('pay-rates/{payRate}',[StaffController::class,'destroyPayRate'])->name('pay-rates.destroy');
 Route::get('attendance',[AttendanceController::class,'index'])->name('attendance.index');
 Route::post('attendance/check-in',[AttendanceController::class,'checkIn'])->name('attendance.check-in');
 Route::post('attendance/{attendance}/check-out',[AttendanceController::class,'checkOut'])->name('attendance.check-out');
 Route::post('attendance/summary',[AttendanceController::class,'summarize'])->name('attendance.summarize');
 Route::get('advances',[AdvancePaymentController::class,'index'])->name('advances.index');
 Route::post('advances',[AdvancePaymentController::class,'store'])->name('advances.store');
 Route::post('advances/{advance}/approve',[AdvancePaymentController::class,'approve'])->name('advances.approve');
 Route::post('advances/{advance}/reject',[AdvancePaymentController::class,'reject'])->name('advances.reject');
 Route::post('advances/{advance}/paid',[AdvancePaymentController::class,'markPaid'])->name('advances.paid');
 Route::get('payroll',[PayrollController::class,'index'])->name('payroll.index');
 Route::get('payroll/{period}',[PayrollController::class,'show'])->name('payroll.show');
 Route::post('payroll/{period}/confirm',[PayrollController::class,'confirm'])->name('payroll.confirm');
 Route::post('payroll/{period}/paid',[PayrollController::class,'markPaid'])->name('payroll.paid');
 Route::get('payroll/{period}/export',[PayrollController::class,'exportCsv'])->name('payroll.export');
 Route::get('staff-calendar',[StaffCalendarController::class,'index'])->name('calendar.index');
 Route::post('staff-calendar/holidays',[StaffCalendarController::class,'storeHoliday'])->name('calendar.holidays.store');
 Route::get('settings',[SettingController::class,'edit'])->name('settings.edit');
 Route::patch('settings',[SettingController::class,'update'])->name('settings.update');
 Route::resource('users',UserController::class)->except(['show']);
 Route::get('backup',[BackupController::class,'index'])->name('backup.index');
 Route::get('backup/export-json',[BackupController::class,'exportJson'])->name('backup.export-json');
 Route::get('backup/export-quotations-csv',[BackupController::class,'exportQuotationsCsv'])->name('backup.export-quotations-csv');
 Route::get('backup/export-customers-csv',[BackupController::class,'exportCustomersCsv'])->name('backup.export-customers-csv');
 Route::get('backup/export-work-orders-csv',[BackupController::class,'exportWorkOrdersCsv'])->name('backup.export-work-orders-csv');
});
