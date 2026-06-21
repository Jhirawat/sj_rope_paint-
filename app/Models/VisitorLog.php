<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = ['path','locale','device_type','ip_hash','user_agent'];
}
