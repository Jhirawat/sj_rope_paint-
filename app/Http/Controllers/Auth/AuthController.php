<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm(){ return view('auth.login'); }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login'=>'required|string|max:255',
            'password'=>'required|string|max:255',
        ]);
        $login = trim($data['login']);
        $user = User::where('email',$login)->orWhereRaw('LOWER(username) = ?', [strtolower($login)])->first();
        if (!$user || !$user->is_active) {
            return back()->withErrors(['login'=>'ไม่พบบัญชี หรือบัญชีถูกระงับ'])->onlyInput('login');
        }
        if ($user->pin_locked_until && now()->lessThan($user->pin_locked_until)) {
            return back()->withErrors(['login'=>'บัญชีถูกล็อกชั่วคราวจากการใส่ PIN ผิดหลายครั้ง กรุณาติดต่อ Admin'])->onlyInput('login');
        }
        $okPassword = Hash::check($data['password'], $user->password ?? '');
        $okPin = $user->pin_hash && preg_match('/^\d{4,6}$/', $data['password']) && Hash::check($data['password'], $user->pin_hash);
        if ($okPassword || $okPin) {
            $user->forceFill(['pin_failed_attempts'=>0,'pin_locked_until'=>null])->save();
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended(($user->role ?? 'staff')==='staff' ? route('staff.dashboard') : route('admin.dashboard'));
        }
        if ($user->role === 'staff') {
            $attempts = ((int)$user->pin_failed_attempts) + 1;
            $user->forceFill([
                'pin_failed_attempts'=>$attempts,
                'pin_locked_until'=>$attempts >= 5 ? now()->addMinutes(15) : null,
            ])->save();
        }
        return back()->withErrors(['login'=>'Username/PIN หรือ Email/Password ไม่ถูกต้อง'])->onlyInput('login');
    }
    public function logout(Request $request){ Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect('/'); }
}
