# UPDATE NOTES V5.2 - SJ ทาสีโรยตัว

## แก้/เพิ่มหลัก
- แก้ปุ่มภาษาเป็น `TH | EN` ไม่ใช้ธง/ข้อความซ้ำ
- แก้หน้า Contact: ไม่เรียกฟังก์ชัน socialIcon ที่อยู่นอก scope แล้ว
- เพิ่ม Thai Address Cascader แบบครบประเทศผ่านชุดข้อมูล kongvut/thai-province-data บน CDN พร้อม fallback สำหรับทดสอบ offline
- ฟอร์มขอใบเสนอราคาบังคับเลือก จังหวัด > อำเภอ > ตำบล > รหัสไปรษณีย์
- เพิ่ม edge case validation: เบอร์โทร, email, service, building type, floors, postcode, Google Maps link, รูปไม่เกิน 10 รูป และชนิดไฟล์รูป
- ป้องกันรูปซ้ำเบื้องต้นทั้งหน้าเว็บและฝั่ง controller

## คำสั่งหลังแตกไฟล์
```bash
composer dump-autoload
php artisan view:clear
php artisan optimize:clear
php artisan serve
```
