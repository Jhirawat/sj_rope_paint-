<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
    use Notifiable;
    protected $fillable=['name','email','username','password','pin_hash','must_change_pin','pin_failed_attempts','pin_locked_until','role','is_active'];
    protected $hidden=['password','remember_token'];
    protected $casts=['password'=>'hashed','must_change_pin'=>'boolean','pin_locked_until'=>'datetime','is_active'=>'boolean'];
    public function isAdmin(): bool { return in_array($this->role,['staff','admin','super_admin'],true); }
}
