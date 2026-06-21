<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CompanyHoliday extends Model
{
    protected $fillable = ['holiday_date','title_th','title_en','type','is_active','note'];
    protected $casts = ['holiday_date'=>'date','is_active'=>'boolean'];
}
