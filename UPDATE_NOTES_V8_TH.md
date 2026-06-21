# UPDATE NOTES V8 — SJ ทาสีโรยตัว

V8 รวมส่วนแก้ไขจาก V7.1 และฟีเจอร์บริหารทีมช่างเพื่อทดสอบระบบทีเดียว

## ส่วนที่แก้จาก V7.1
- แก้ระบบ Theme Admin Light/Dark ให้ Layout อ่านค่า `admin_appearance` จริง
- เพิ่ม Error Pages 403 / 404 / 419 / 500 แบบไม่ให้เห็น Laravel Error ดิบ
- เพิ่ม Sidebar Notification Center พร้อม Badge จำนวนแจ้งเตือน
- เพิ่มรูปเข้างานใน Dashboard และหน้าเข้างาน/สรุปแรง
- แก้ Staff Payroll relation จาก `payrollPeriod` เป็น `period`
- ปรับ Staff UI ให้ใช้มือถือเป็นหลัก

## ฟีเจอร์ V8
- ระบบทีมช่าง (`admin/teams`)
- ผูกทีมช่างกับใบงาน (`work_team_id`)
- ระบบ Login พนักงานด้วย Username + PIN 4-6 หลัก
- Admin สร้าง/แก้ Username และ PIN ให้พนักงานได้
- เงินเบิกเกิน 1,000 บาท/รอบ เป็น “คำขออนุมัติพิเศษ”
- แจ้งเตือนเงินเบิก/เบิกพิเศษที่ Sidebar
- Dashboard เจ้าของ: Top ขยัน / Top สาย / Top ขาดงาน / Top เบิกเงิน / พนักงานดีเด่น
- รายงานเจ้าของ (`admin/owner-reports`)
- Audit Log (`admin/audit-logs`)
- สรุปต้นทุนและกำไรต่อใบงานเบื้องต้น
- Seed ตัวอย่างทีมช่างและ KPI เดือน 06/2569

## บัญชีทดสอบ
- Admin: `admin@sjrope.test` / `password`
- Staff ตัวอย่าง: `mark` / `1234`
- Staff ตัวอย่างอื่น: `dud`, `wave`, `kob`, `chon`, `phum`, `id`, `baitoey`, `moei`, `koy` / PIN `1234`

## หมายเหตุ
ไฟล์นี้ไม่รวม `vendor` ให้รัน `composer install` ก่อนใช้งาน
