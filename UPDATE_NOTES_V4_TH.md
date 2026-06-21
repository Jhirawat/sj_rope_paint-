# UPDATE NOTES V4 - SJ ทาสีโรยตัว

V4 เพิ่มระบบ Operation Management สำหรับใช้งานหน้างานจริงต่อจาก V3

## เพิ่มใหม่ใน V4

### 1) ลายเซ็นดิจิทัลในใบงาน
- เซ็นบน Canvas ผ่านมือถือ/แท็บเล็ตได้
- รองรับ 3 ตำแหน่ง: ลูกค้า, หัวหน้าช่าง, ผู้ตรวจรับ
- บันทึกเป็นไฟล์ PNG ใน `storage/app/public/work-order-signatures`
- รองรับอัปโหลดไฟล์ลายเซ็นจากหน้าแก้ไขใบงานด้วย

### 2) พิมพ์ใบงาน PDF/A4
- เพิ่มปุ่ม `PDF / พิมพ์ A4`
- มีข้อมูลลูกค้า, สถานที่, QR Map, รายละเอียดงาน, รูปภาพ, ลายเซ็น
- ใช้ `window.print()` เพื่อให้บันทึกเป็น PDF ได้จาก Browser

### 3) QR Code เปิด Google Maps หน้างาน
- สร้างจาก `map_link` ก่อน
- ถ้าไม่มี map link จะสร้างจาก `latitude, longitude`
- แสดงในหน้าใบงานและเอกสารพิมพ์ A4

### 4) ปุ่มโทร/LINE/แผนที่จากใบงาน
- โทรหาลูกค้า `tel:`
- เปิด LINE จาก LINE ID
- เปิด Google Maps

### 5) แจ้งเตือนงานใกล้นัดสำรวจ / ใกล้เริ่มงาน
- Dashboard แสดงงานนัดสำรวจพรุ่งนี้
- Dashboard แสดงงานเริ่มพรุ่งนี้
- Dashboard แสดงงานเกินกำหนด

### 6) Activity Log ใบงาน
- เก็บประวัติการเปลี่ยนสถานะใบงาน
- ระบุสถานะเดิม, สถานะใหม่, ผู้แก้ไข, เวลา, หมายเหตุ

### 7) GPS Check-in
- เช็คอินหน้างานพร้อมพิกัด
- ใช้ปุ่ม “ใช้ตำแหน่งปัจจุบัน” จาก Browser Geolocation
- เก็บประวัติ Check-in หลายครั้งต่อใบงาน

### 8) Notification Center เบื้องต้น
- เพิ่มตาราง `admin_notifications`
- เพิ่มปุ่มศูนย์แจ้งเตือนงานใน Admin Header
- เตรียมต่อยอดแจ้งเตือนในระบบจริง

## Migration ใหม่

- `2026_01_01_000015_v4_operation_management.php`

เพิ่มตาราง:
- `work_order_status_logs`
- `work_order_checkins`
- `admin_notifications`

เพิ่ม field ใน `work_orders`:
- `accepted_at`
- `customer_signature_path`
- `foreman_signature_path`
- `inspector_signature_path`
- `last_checkin_at`
- `last_checkin_latitude`
- `last_checkin_longitude`

## หมายเหตุ

ระบบ QR Code ใน V4 ใช้บริการ CDN ภายนอกสำหรับสร้างรูป QR แบบง่าย ถ้าจะใช้งาน Production จริงระยะยาว แนะนำติดตั้ง Package QR Code ภายใน Laravel เพิ่มในภายหลัง
