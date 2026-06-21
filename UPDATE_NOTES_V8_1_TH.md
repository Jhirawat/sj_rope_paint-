# UPDATE NOTES V8.1

## แก้ไขหลัก
- แก้ Staff Login ตัวอย่าง `mark / 1234` ให้ใช้งานได้จริง
- เพิ่ม username + PIN ให้พนักงานตัวอย่างทุกคนใน Seeder
- Login รองรับ username แบบไม่สนตัวพิมพ์เล็ก/ใหญ่
- หลัง Login พนักงาน redirect เข้า `/staff` ถูกต้อง
- ปรับ Dashboard Admin ให้ข้อมูลธุรกิจกลับมาอยู่บนสุด
- แยกข้อมูลพนักงาน/KPI ให้อยู่ถัดลงมา ไม่ทับ Dashboard ธุรกิจ

## บัญชีทดสอบ Staff
- `mark / 1234`
- `dud / 1234`
- `wave / 1234`
- `kob / 1234`
- `chon / 1234`
- `phum / 1234`
- `id / 1234`
- `baitoey / 1234`
- `moei / 1234`
- `koy / 1234`

## หลังอัปเดต
ถ้าเคย migrate แล้วแต่ยัง login ไม่ได้ ให้รัน:

```bash
php artisan migrate:fresh --seed
php artisan optimize:clear
```
