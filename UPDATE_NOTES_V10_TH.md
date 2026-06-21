# SJ_ROPE_PAINTING_V10_PRODUCTION

อัปเดตหลักใน V10

- Rebuild หน้าแรกตาม Mockup ใหม่
- Navbar สีขาวตามแบบอ้างอิง
- ใช้ Navbar Logo PNG ใหม่ ไม่บีบ ไม่ใส่พื้นกรอบซ้ำ
- ใช้ Main Logo PNG ใน Hero Card
- ใช้ Footer Logo PNG ใน Footer
- ปรับ Theme เป็นน้ำเงิน / ทอง / ขาว ตาม Corporate Mockup
- ข้อมูลติดต่อให้เหลือรูปแบบเดียวกันทุกจุด:
  - 081-353-7779 คุณอิ้ด
  - 092-284-5996 คุณก้อย
- พื้นที่บริการ:
  - หัวหิน-ชะอำ (พื้นที่หลัก)
  - กรุงเทพมหานคร
  - ชลบุรี
- ตัดข้อความทีมงานมากกว่า 30 คนออก
- เปลี่ยน KPI เป็น:
  - ประสบการณ์มากกว่า 5 ปี
  - ทีมงานคุณภาพ
  - มาตรฐานความปลอดภัย
  - หัวหิน-ชะอำ พื้นที่หลัก
- คงระบบ Staff / Payroll / Work Order / Quotation จาก V9.2 ไว้
- เพิ่ม cache buster `?v=100` สำหรับไฟล์โลโก้

วิธีใช้งานหลังแตกไฟล์:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear
php artisan serve
```

GitHub force push:

```bash
git init
git add .
git commit -m "SJ Rope Painting V10 Production"
git branch -M main
git remote remove origin
git remote add origin https://github.com/Jhirawat/sj_rope_paint-.git
git push -u origin main --force
```
