# ตั้งค่าฐานข้อมูล SJ ทาสีโรยตัว

ให้สร้างฐานข้อมูลใน phpMyAdmin ชื่อ:

```text
sj_rope_painting
```

แล้วในไฟล์ `.env` ให้ใช้ค่านี้:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sj_rope_painting
DB_USERNAME=root
DB_PASSWORD=
```

จากนั้นรัน:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```
