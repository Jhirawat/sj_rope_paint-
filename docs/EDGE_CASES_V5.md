# EDGE CASES V5

## Guest/User
- เปลี่ยนภาษาเป็น EN แล้ว Footer ต้องไม่มีภาษาไทยหลุด
- เปลี่ยนภาษาเป็น EN แล้วปุ่ม Floating และ Form ต้องแปลครบ
- วาง Google Maps link ที่ไม่มี lat/lng ต้องขึ้น Toast ไม่ใช่ alert
- Browser ปฏิเสธตำแหน่ง ต้องขึ้น Toast ภาษาไทย/อังกฤษตาม locale
- อัปโหลดรูปซ้ำ ต้องเตือนและ Preview ไม่ซ้ำ
- อัปโหลดเกิน 10 รูป ต้องเตือน
- ไม่เลือกจังหวัด/อำเภอ/ตำบล แต่มี Google Maps Link ต้องยังส่งคำขอได้
- มีพิกัด แต่ไม่มี Google Maps Link ต้องแสดง Preview Map ได้

## Admin
- ตั้งค่า Logo แต่ละประเภทแล้วต้องไม่ทับกัน
- Favicon Preview ต้องแสดงได้
- Social URL ว่าง ต้องไม่ทำให้ Footer พัง
- เพิ่ม/ลบเบอร์ติดต่อหลายเบอร์แล้วบันทึก JSON ได้ถูกต้อง
- แก้พิกัดใน Settings แล้ว Map Preview ต้องเปลี่ยน
- ใบเสนอราคาที่ไม่มีรูปต้องแสดง “ไม่มีรูปแนบ”
- ใบเสนอราคาที่ไม่มีพิกัดต้องไม่แสดง iframe ว่าง

## Production Notes
- หากต้องใช้ Reverse Geocoding จริง ควรเพิ่ม Google Geocoding API หรือ OpenStreetMap Nominatim และทำ rate-limit
- หากต้องรองรับ maps.app.goo.gl แบบเต็ม ควรเพิ่ม URL expander ฝั่ง backend
