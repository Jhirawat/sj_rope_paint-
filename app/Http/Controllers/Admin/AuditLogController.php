<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
class AuditLogController extends Controller
{
    public function index(){ return view('admin.audit-logs.index',['logs'=>AuditLog::with('user')->latest()->paginate(80)]); }
}
