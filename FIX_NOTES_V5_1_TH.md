# FIX NOTES V5.1

แก้ไฟล์ `resources/views/layouts/app.blade.php` หลังพบ ParseError จาก Blade Layout

## แก้ไขหลัก
- ปิด `@php ... @endphp` ให้ถูกต้อง
- เปลี่ยน `socialIcon()` จากฟังก์ชัน global เป็น Closure `$socialIcon` เพื่อลดปัญหา redeclare/function parsing ใน Blade
- แก้ CSS Variables ให้เป็น `{{ $siteColor }}` และ `{{ $siteAccent }}` ถูกต้อง
- แยก `$heroImageUrl` ไว้ใน PHP ก่อนนำไปใช้ใน CSS เพื่อลดปัญหา quote ซ้อนใน `url(...)`
- จัดรูปแบบ Blade Layout ใหม่ให้อ่านง่ายขึ้น
- ตรวจ balance เบื้องต้นของ `@if/@endif`, `@foreach/@endforeach`, `@php/@endphp` ในไฟล์ views แล้ว

## หลังแตกไฟล์
ให้รัน:

```bash
composer dump-autoload
php artisan view:clear
php artisan optimize:clear
php artisan serve
```
