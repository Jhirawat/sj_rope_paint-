## V5.2
- Fixed Contact social icon scope error.
- Changed language switcher to clean TH | EN format.
- Added Thai address cascader via kongvut/thai-province-data CDN with offline fallback.
- Hardened quotation form validation and duplicate image handling.

# CHANGELOG - Rai Khaisaeng Strawberry Prototype v29

## v29 - Production Stable Release
- ยกระดับจาก v28 เป็น Production Stable สำหรับใช้งานจริง/ส่งงาน
- เพิ่ม Activity Log ให้ครอบคลุมมากขึ้น:
  - Login / Logout / Register / Social Login
  - เพิ่ม/แก้ไข/ลบหมวดหมู่
  - ปรับสต๊อกเดี่ยวและปรับสต๊อกหลายรายการ
  - ตั้งค่าข้อมูลร้านค้า / โลโก้ / Hero Image
  - ตั้งค่าธีม / Preset Theme / Reset Theme
- คง Activity Log เดิมจาก v28: สินค้า, โปรโมชั่น, ออเดอร์, การชำระเงิน
- คง Backup / Export CSV: สินค้า, ออเดอร์, สมาชิก, คลังสินค้า
- คง Social Login Status พร้อม Callback URL สำหรับ Google / Facebook / LINE
- คง Security Headers + Force HTTPS + Login Rate Limit
- คง Seed Protection: Production ไม่รัน DemoProductSeeder ถ้า `ALLOW_DEMO_SEED=false`
- คง Edge Case สำคัญ: ยกเลิกออเดอร์แล้วคืน Stock แค่ครั้งเดียว
- คง Promotion Editor: แก้รูปข้างโปรโมชั่น, หัวข้อ, รายละเอียด, ปุ่ม, ลิงก์
- คง UI Fixes: ปุ่ม/Badge/รายละเอียดไม่ติดขอบบน และ Mobile Admin ดีขึ้น

## คำสั่งหลัง Deploy
```bash
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

> หมายเหตุ: ถ้า `storage:link` ขึ้นว่า link already exists ถือว่าปกติ


## v29.1 Quick Production Fix
- ปรับปุ่มเพิ่ม/ลดจำนวนในหน้ารายละเอียดสินค้าให้ใช้รูปแบบเดียวกับหน้าตะกร้า (+ / −) และกดง่ายขึ้น
- ปรับแผนที่ Footer ให้ปักตำแหน่งพิกัด 18.854859, 98.561256 และลิงก์ Google Maps ใหม่
- แก้เบอร์คุณตี๋เป็น 089-265-5685 และเพิ่ม migration อัปเดตค่าบน Production

## V4 - Operation Management

- เพิ่มลายเซ็นดิจิทัลในใบงาน: ลูกค้า / หัวหน้าช่าง / ผู้ตรวจรับ
- เพิ่มหน้าใบงานสำหรับพิมพ์ A4 / บันทึก PDF
- เพิ่ม QR Code เปิด Google Maps หน้างาน
- เพิ่มปุ่มโทรหาลูกค้า, เปิด LINE, เปิดแผนที่จากใบงาน
- เพิ่ม GPS Check-in หน้างาน
- เพิ่มประวัติการเปลี่ยนสถานะใบงาน
- เพิ่ม Dashboard แจ้งเตือนงานนัดสำรวจพรุ่งนี้ / เริ่มงานพรุ่งนี้ / งานเกินกำหนด
- เพิ่มตาราง admin_notifications สำหรับ Notification Center เบื้องต้น
- เพิ่มเอกสาร `UPDATE_NOTES_V4_TH.md` และ `docs/EDGE_CASES_V4.md`

## v5 - User/Admin UX Polish
- ปรับ Footer Social เป็นไอคอน
- เพิ่ม Language Switcher แบบธง TH/EN
- ปรับ Contact Page เป็น Card Layout
- ปรับ Quote Form: Dropdown ประเภทอาคาร, Stepper จำนวนชั้น, Preview รูป, Map Preview, Toast
- เพิ่มฟิลด์ที่อยู่หน้างาน จังหวัด/อำเภอ/ตำบล/รหัสไปรษณีย์
- เพิ่มปุ่มใช้ตำแหน่งปัจจุบันและดึงพิกัดจาก Google Maps Link
- ปรับหน้า Admin Settings เป็น Tab แบบ Production: Company, Branding, Homepage, Contact/Social, SEO, Theme, Quotation
- เพิ่ม Logo/Favicon/Hero/OG image settings
- เพิ่มหน้าใบเสนอราคา Admin ให้แสดงรูปแนบ แผนที่ และข้อมูลที่อยู่ครบขึ้น


## V6 - ระบบพนักงาน / ค่าแรง / เงินเบิก
- เพิ่มพนักงานและประเภทพนักงาน
- เพิ่มค่าแรงแบบ Dropdown 360/400/420/500/550 และแก้ไขได้
- เพิ่มเข้างาน/ออกงานพร้อมรูปและ GPS
- 07:31 เป็นต้นไปถือว่าสาย
- หัวหน้าช่างสรุปจำนวนแรง 1 / 0.75 / 0.5 / 0 ตามสภาพหน้างาน
- เพิ่มเงินเบิกและกันเบิกเกิน 1,000 ต่อรอบ
- เพิ่มรอบจ่าย 1-15 และ 16-สิ้นเดือน
- เพิ่มปฏิทินวันหยุด/เงินเบิก/รอบจ่าย

## V8
- รวม V7.1 fixes + Team/Owner KPI
- เพิ่ม Staff Username + PIN Login
- เพิ่ม Sidebar Notification สำหรับเงินเบิก
- เพิ่มระบบทีมช่างและรายงานเจ้าของ
- เพิ่ม Top ขยัน / Top สาย / Top ขาดงาน / Top เบิกเงิน
- เพิ่ม Audit Log และ Error Pages
