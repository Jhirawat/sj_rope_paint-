# UPDATE NOTES V5 - SJ ทาสีโรยตัว

## เป้าหมาย V5
เก็บงาน UX/UI ฝั่ง Guest/User และปรับหน้า Admin Settings ให้เหมาะกับเว็บทาสีโรยตัว ไม่ใช่เว็บสตรอเบอร์รี่ โดยใช้ต้นแบบเดิมเป็นแนวทางโครงระบบเท่านั้น

## Guest/User ที่เพิ่มและแก้ไข
- แก้ Layout หลักให้รองรับภาษา TH/EN ชัดเจนขึ้น
- เพิ่มตัวเปลี่ยนภาษาแบบมีไอคอนธง 🇹🇭 TH / 🇬🇧 EN
- แก้ Footer ให้ Social เป็นไอคอน ไม่แสดง URL ยาว ๆ
- Footer แยกข้อความ TH/EN ให้ดีขึ้น
- เพิ่ม Floating Contact Bar: โทร / LINE / ขอราคา
- หน้า Contact ทำใหม่เป็น 3 Card:
  - ข้อมูลติดต่อ
  - แผนที่ / เปิด Google Maps
  - ส่งข้อความด่วน
- ฟอร์มขอใบเสนอราคาใหม่แบบ Step Card:
  1. ข้อมูลลูกค้า
  2. ข้อมูลงาน
  3. ที่อยู่หน้างานและแผนที่
  4. รูปหน้างานและรายละเอียด
- ประเภทอาคารเปลี่ยนเป็น Dropdown
- จำนวนชั้นเปลี่ยนเป็นปุ่ม - / +
- เปลี่ยนคำว่า “พื้นที่ / สถานที่” เป็น “ชื่อสถานที่ / อาคาร / โครงการ”
- เพิ่ม Dropdown จังหวัด / อำเภอ / ตำบล / รหัสไปรษณีย์ พร้อม fallback data เบื้องต้น
- เพิ่มปุ่ม “ใช้ตำแหน่งปัจจุบัน”
- เพิ่มปุ่ม “ดึงจาก Google Maps”
- วางลิงก์ Google Maps แล้วดึง lat/lng ได้เมื่อ URL มีพิกัด
- แสดงแผนที่ Preview แบบ Real-time
- เปลี่ยน alert เป็น Toast
- เพิ่ม Preview รูปก่อนส่งใบเสนอราคา
- แจ้งเตือนกรณีเลือกรูปซ้ำหรือเกิน 10 รูป
- หน้า Projects แสดงข้อมูลแบบ Portfolio มากขึ้น:
  - รูปทั้งหมด
  - ก่อนทำ
  - ระหว่างทำ
  - หลังทำ

## Admin / SB Admin ที่เพิ่มและแก้ไข
- หน้า Website Settings ปรับเป็น Tab ใหม่:
  - ข้อมูลบริษัท
  - โลโก้ / Favicon
  - หน้าแรก
  - ติดต่อ / Social
  - SEO / แชร์ลิงก์
  - Theme
  - ใบเสนอราคา
- เพิ่มช่อง Logo หลายประเภท:
  - Logo Navbar TH
  - Logo Navbar EN
  - Logo Footer TH
  - Logo Footer EN
  - Logo Light
  - Logo Dark
  - Favicon
- เพิ่ม Hero Banner / รูปโฆษณาหน้าแรก
- เพิ่ม OG Image สำหรับแชร์ Facebook/LINE
- เพิ่ม Meta Title / Meta Description TH/EN
- เพิ่ม Browser Theme Color
- เพิ่ม Google Maps Link + Latitude/Longitude + Map Preview ใน Settings
- เพิ่ม Social channel แบบเลือกประเภท เช่น Facebook, LINE, TikTok, YouTube, Google Maps, Messenger, Instagram
- หน้าใบเสนอราคา Admin เพิ่ม:
  - รูปหน้างานที่ลูกค้าแนบ
  - Map Preview
  - จังหวัด / อำเภอ / ตำบล / รหัสไปรษณีย์
  - ปุ่มโทร / LINE / สร้างใบงาน

## Database / Migration
- เพิ่ม migration `2026_01_01_000016_v5_user_admin_ux_fields.php`
- เพิ่ม field ใน quotations:
  - province
  - district
  - subdistrict
  - postcode
  - details_short

## หมายเหตุ
- ระบบดึงพิกัดจาก Google Maps Link รองรับลิงก์ที่มี lat/lng อยู่ใน URL เช่น `?q=18.854859,98.561256` หรือ `@18.854859,98.561256`
- ลิงก์สั้น `maps.app.goo.gl` อาจต้องใช้ API/URL expand เพิ่มใน Production จริง
- Address dropdown มี fallback เบื้องต้น หากต้องการครบ 77 จังหวัดควรนำฐานข้อมูลจังหวัด/อำเภอ/ตำบลจากเว็บสตรอเบอร์รี่หรือ seed ชุดจริงมาเพิ่ม
