# Edge Cases สำหรับ SJ ทาสีโรยตัว v1

## Quotation

1. ลูกค้าไม่กรอกชื่อ/เบอร์โทร ต้องไม่สามารถส่งฟอร์มได้
2. เบอร์โทรซ้ำ ให้เชื่อมกับ customer เดิมด้วย `firstOrCreate`
3. จำนวนชั้นต่ำกว่า 1 หรือมากกว่า 200 ต้อง reject
4. อัปโหลดไฟล์ไม่ใช่รูปภาพ ต้อง reject
5. อัปโหลดรูปเกิน 5MB ต่อไฟล์ ต้อง reject
6. ส่งฟอร์มสำเร็จต้องสร้าง quotation_no ไม่ซ้ำ
7. แอดมินเปลี่ยนสถานะได้เฉพาะค่าที่กำหนด

## Service

1. slug ซ้ำต้อง reject
2. หาก service มี project อ้างอิง ห้ามลบ
3. หากภาษาอังกฤษว่าง หน้าบ้านต้อง fallback ภาษาไทย
4. ซ่อน service แล้วหน้าบ้านต้องไม่แสดง

## Project

1. slug ซ้ำต้อง reject
2. Project ที่ `is_active=false` ต้องไม่แสดงหน้าบ้าน
3. หากไม่มีรูปก่อน/หลัง ต้องยังแสดง placeholder ได้
4. วันที่สิ้นสุดก่อนวันเริ่ม ควรเพิ่ม validation เพิ่มใน production รอบถัดไป

## Article

1. บทความ draft ต้องไม่แสดงหน้าบ้าน
2. slug ซ้ำต้อง reject
3. ถ้า publish ให้ใส่ published_at อัตโนมัติ

## Admin/Auth

1. คนไม่ login เข้าหลังบ้านไม่ได้
2. role ไม่ถูกต้องต้องโดน 403
3. ห้ามลบบัญชีตัวเอง
4. Password ว่างตอนแก้ไข user ต้องคงรหัสเดิม

## Security

1. ทุกฟอร์มมี CSRF
2. Output หลักใช้ `e()` / blade escaping
3. เพิ่ม Security Headers middleware
4. File upload จำกัดชนิดและขนาด
