<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', ['settings'=>SiteSetting::pluck('value','key')]);
    }

    public function update(Request $r)
    {
        $data = $r->except(['_token','_method','logo','favicon','logo_nav_th','logo_nav_en','logo_footer_th','logo_footer_en','logo_light','logo_dark','hero_image','og_image']);
        foreach(['contact_phones','social_links'] as $jsonKey){
            if(isset($data[$jsonKey]) && is_array($data[$jsonKey])){
                $rows=[];
                foreach($data[$jsonKey] as $row){
                    $row=array_filter((array)$row, fn($v)=>$v!==null && trim((string)$v)!=='');
                    if($row){ $rows[]=$row; }
                }
                $data[$jsonKey]=json_encode($rows, JSON_UNESCAPED_UNICODE);
            }
        }
        foreach([
            'logo'=>'logo_path',
            'logo_nav_th'=>'logo_nav_th_path',
            'logo_nav_en'=>'logo_nav_en_path',
            'logo_footer_th'=>'logo_footer_th_path',
            'logo_footer_en'=>'logo_footer_en_path',
            'logo_light'=>'logo_light_path',
            'logo_dark'=>'logo_dark_path',
            'hero_image'=>'hero_image_path',
            'og_image'=>'og_image_path',
            'favicon'=>'favicon_path',
        ] as $field=>$key){
            if($r->hasFile($field)){
                $data[$key]=$r->file($field)->store('site','public');
            }
        }
        foreach($data as $k=>$v){ SiteSetting::put($k, is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v); }
        return back()->with('success','บันทึกตั้งค่าเว็บไซต์แล้ว');
    }
}
