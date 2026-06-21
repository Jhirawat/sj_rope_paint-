<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index', [
            'notifications'=>AdminNotification::latest()->paginate(50),
            'unreadCount'=>AdminNotification::whereNull('read_at')->count(),
            'advanceCount'=>AdminNotification::whereNull('read_at')->where('type','like','advance%')->count(),
        ]);
    }
    public function markRead(AdminNotification $notification)
    {
        $notification->update(['read_at'=>now()]);
        return redirect($notification->url ?: route('admin.notifications.index'));
    }
    public function markAllRead()
    {
        AdminNotification::whereNull('read_at')->update(['read_at'=>now()]);
        return back()->with('success','อ่านแจ้งเตือนทั้งหมดแล้ว');
    }
}
