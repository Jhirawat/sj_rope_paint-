# SJ ทาสีโรยตัว Production Ready v2

เวอร์ชันนี้ปรับจากโมเดลเว็บสตรอว์เบอร์รี่เป็นระบบสำหรับธุรกิจรับทาสีอาคารสูง / โรยตัวซ่อมอาคาร โดยเน้นใช้งานจริง, UX/UI อ่านง่าย, ไม่บังคับลูกค้าสมัครสมาชิก และรองรับ 2 ภาษาไทย/อังกฤษ

## สิ่งที่เพิ่มใน v2
- ปรับหน้าหลักให้ภาษาไทย/อังกฤษครบขึ้น
- แก้ปุ่มให้ตัวหนังสืออยู่กลางปุ่ม ไม่ชิดขอบบน
- เพิ่มระบบใบงาน (Work Orders) ฝั่ง Admin
- สร้างใบงานจากใบเสนอราคาได้ทันที
- ใบงานเก็บที่อยู่ลูกค้า, เบอร์, LINE, Email, Google Maps Link, Latitude, Longitude
- เพิ่มฟอร์มขอใบเสนอราคาให้รับพิกัด/ลิงก์ Google Maps/ที่อยู่หน้างาน/งบประมาณโดยประมาณ
- เพิ่มปุ่ม “ใช้ตำแหน่งปัจจุบัน” ฝั่ง Guest
- เพิ่มเมนูใบงานใน Sidebar พร้อมไอคอน
- เพิ่มรายงานใบงานใน Dashboard
- เปลี่ยนระบบเรียงลำดับเป็นปุ่ม “เลื่อนขึ้น / เลื่อนลง” สำหรับบริการและผลงาน
- บริการรองรับรูปภาพ
- ผลงานรองรับอัลบั้มรูป
- ตั้งค่าเว็บรองรับ Logo, Favicon, เบอร์หลายเบอร์, Social และธีม Admin/User

## วิธีติดตั้ง
```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

## Database
ตั้งค่าใน `.env`
```env
DB_DATABASE=sj_rope_painting
DB_USERNAME=root
DB_PASSWORD=
```

## Login ตัวอย่าง
- Super Admin: admin@sjrope.test / password
- Staff: staff@sjrope.test / password

## Flow ใช้งานจริง
Guest ขอใบเสนอราคา → Admin ตรวจข้อมูล → นัดสำรวจ → สร้างใบงาน → อัปเดตสถานะ/รูปภาพ → ปิดงาน → นำไปทำผลงาน

---

# V4 Operation Management

V4 เพิ่มระบบสำหรับใช้งานกับทีมช่างและหน้างานจริง ได้แก่

- ลายเซ็นดิจิทัลในใบงาน
- พิมพ์ใบงาน A4 / บันทึก PDF จาก Browser
- QR Code เปิด Google Maps หน้างาน
- ปุ่มโทรหาลูกค้า / เปิด LINE / เปิดแผนที่
- GPS Check-in หน้างาน
- ประวัติการเปลี่ยนสถานะใบงาน
- Dashboard แจ้งเตือนงานใกล้นัดสำรวจ / ใกล้เริ่มงาน / งานเกินกำหนด

หลังอัปเดตไฟล์ V4 ให้รัน:

```bash
php artisan migrate
php artisan storage:link
php artisan optimize:clear
```

ไฟล์รายละเอียด:

- `UPDATE_NOTES_V4_TH.md`
- `docs/EDGE_CASES_V4.md`

---

## เพิ่มเติมใน V5

V5 เน้นเก็บ UX/UI ฝั่ง Guest/User และปรับ Website Settings ฝั่ง Admin ให้เหมาะกับเว็บทาสีโรยตัวมากขึ้น

### คำสั่งหลังอัปเดตจาก V4

```bash
composer install
php artisan optimize:clear
php artisan migrate
php artisan storage:link
php artisan serve
```

### จุดที่ต้องทดสอบ

1. เปลี่ยนภาษา TH/EN แล้วหน้า Footer, ปุ่ม และฟอร์มต้องแปลครบ
2. ฟอร์มขอใบเสนอราคา:
   - ประเภทอาคารเป็น Dropdown
   - จำนวนชั้นใช้ปุ่ม - / +
   - อัปโหลดรูปแล้ว Preview ได้
   - วาง Google Maps Link แล้ว Preview แผนที่ได้ หาก URL มีพิกัด
3. หน้า Contact ต้องแสดง Card ข้อมูลติดต่อ / แผนที่ / ส่งข้อความด่วน
4. หน้า Settings หลังบ้านต้องบันทึกโลโก้ Favicon Hero Banner OG Image และ Social ได้
5. หน้าใบเสนอราคา Admin ต้องเห็นรูปหน้างาน แผนที่ และข้อมูลที่อยู่ครบ

### หมายเหตุ Google Maps

การดึงข้อมูลจาก Google Maps ใน V5 เป็นแบบ Frontend parser สำหรับลิงก์ที่มีพิกัดอยู่ใน URL เช่น:

```text
https://www.google.com/maps?q=18.854859,98.561256
```

หากต้องการรองรับลิงก์สั้น `maps.app.goo.gl` และเติมจังหวัด/อำเภอ/ตำบลจากพิกัดแบบอัตโนมัติ ควรเพิ่ม Geocoding API ใน Production จริง


## V6 - ระบบพนักงาน / ค่าแรง / เงินเบิก
- เพิ่มพนักงานและประเภทพนักงาน
- เพิ่มค่าแรงแบบ Dropdown 360/400/420/500/550 และแก้ไขได้
- เพิ่มเข้างาน/ออกงานพร้อมรูปและ GPS
- 07:31 เป็นต้นไปถือว่าสาย
- หัวหน้าช่างสรุปจำนวนแรง 1 / 0.75 / 0.5 / 0 ตามสภาพหน้างาน
- เพิ่มเงินเบิกและกันเบิกเกิน 1,000 ต่อรอบ
- เพิ่มรอบจ่าย 1-15 และ 16-สิ้นเดือน
- เพิ่มปฏิทินวันหยุด/เงินเบิก/รอบจ่าย
