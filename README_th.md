# 📚 LearnUP - แพลตฟอร์มแบ่งปันความรู้

[🇺🇸 English](./README.md) | [🇹🇭 ภาษาไทย](./README_th.md)

## ✨ ภาพรวม
LearnUP เป็นแพลตฟอร์มที่ออกแบบมาเพื่อ **แบ่งปันความรู้** ผ่าน **ไฟล์ PDF** และ **โพสต์บล็อก** ซึ่งมอบสภาพแวดล้อมที่ใช้งานง่ายและสามารถทำงานร่วมกันได้เพื่อส่งเสริมการเรียนรู้

#### 📸 [ภาพเพิ่มเติม](README_IMAGES.md)

## 🚀 คุณสมบัติ

### **คุณสมบัติสำหรับผู้ใช้**
- ✅ สร้างและแก้ไขโพสต์บล็อกโดยใช้ **Quill.js Editor**
- ✅ อัปโหลดและแนบ **ไฟล์ PDF** ไปกับโพสต์
- ✅ ดูตัวอย่างไฟล์ PDF ด้วย **PDF.js**
- ✅ กดถูกใจ / ยกเลิกถูกใจโพสต์ (**ระบบ Toggle**)
- ✅ แสดงความคิดเห็นและตอบกลับโพสต์ พร้อมระบบแจ้งเตือน
- ✅ UI ที่สวยงามด้วย **TailwindCSS**

### **คุณสมบัติสำหรับผู้ดูแลระบบ**
- ✅ อนุมัติหรือปฏิเสธโพสต์ของผู้ใช้
- ✅ ดูสถิติต่างๆ เช่น:
  - จำนวนผู้ใช้, โพสต์, การกดถูกใจ, และความคิดเห็น
  - ผู้ใช้ที่ใช้งานอยู่ (Writer, Admin, และ User ทั่วไป)
  - ประวัติการใช้งานระบบ (Logs)
- ✅ จัดการบทบาทของผู้ใช้ (**User, Writer, Admin**)
- ✅ ลบโพสต์และความคิดเห็นที่ไม่เหมาะสม
- ✅ ระบบแจ้งเตือนในแดชบอร์ด
- ✅ ติดตามกิจกรรมในระบบ **(เข้าสู่ระบบ, สร้างโพสต์, แสดงความคิดเห็น, กดถูกใจ)**

### **ระบบแจ้งเตือน**
- ✅ แจ้งเตือน **Admin** เมื่อมีโพสต์ใหม่รออนุมัติ
- ✅ แจ้งเตือน **User** เมื่อโพสต์ของพวกเขาถูก **อนุมัติ / ปฏิเสธ**
- ✅ แจ้งเตือน **User** เมื่อมีคน **กดถูกใจหรือแสดงความคิดเห็น** บนโพสต์ของพวกเขา

## 🛠️ เทคโนโลยีที่ใช้
| ส่วนประกอบ      | เทคโนโลยี |
|---------------|------------|
| **Backend**   | Laravel (ล่าสุด) |
| **Frontend**  | Blade + TailwindCSS |
| **Database**  | MySQL |
| **Editor**    | Quill.js |
| **File Storage** | Laravel Storage (Local) |
| **PDF Preview** | PDF.js / iframe |
| **Authentication** | Laravel Custom Auth |
| **Graph & Analytics** | Chart.js |

## 🏢 บทบาทของผู้ใช้
| บทบาท     | ความสามารถ |
|----------|--------------------------------------------------|
| **User** | สร้างโพสต์ (ต้องรออนุมัติ), อัปโหลด PDF, แสดงความคิดเห็น |
| **Writer** | โพสต์ได้ทันทีโดยไม่ต้องรออนุมัติ, อัปโหลด PDF, แสดงความคิดเห็น |
| **Admin** | อนุมัติ / ปฏิเสธโพสต์, ดูสถิติ, จัดการผู้ใช้, ตรวจสอบ Log |

## 📊 สถิติในแดชบอร์ด
- **จำนวนโพสต์, ผู้ใช้, Writer, Admin, และ Logs**
- **กราฟเส้นแสดงแนวโน้มการสร้างโพสต์, แสดงความคิดเห็น, และการลงทะเบียน (7 วันที่ผ่านมา)**
- **กราฟแท่งแสดง Log จำแนกตามประเภท (เข้าสู่ระบบ, สร้างโพสต์, แสดงความคิดเห็น ฯลฯ)**

## 📑 ลำดับการทำงาน

📌 **User**
1. **เข้าสู่ระบบ → ไปที่แดชบอร์ด**
2. **สร้างโพสต์ + อัปโหลด PDF**
3. **รอการอนุมัติ** (ระบบแจ้งเตือน Admin)
4. **เมื่อได้รับการอนุมัติ โพสต์จะถูกเผยแพร่**

📌 **Writer**
1. **เข้าสู่ระบบ → ไปที่แดชบอร์ด**
2. **สร้างโพสต์ + อัปโหลด PDF**
3. **โพสต์จะถูกเผยแพร่ทันที**

📌 **Admin**
1. **เข้าสู่ระบบ → ไปที่แดชบอร์ด**
2. **อนุมัติ / ปฏิเสธโพสต์**
3. **ตรวจสอบสถิติและ Log**
4. **จัดการบทบาทของผู้ใช้และเนื้อหา**

## 🎨 การออกแบบ UI
- **ใช้ TailwindCSS ในการตกแต่ง**
- **ธีมสีเขียวได้รับแรงบันดาลใจจากมหาวิทยาลัยเกษตรศาสตร์**
- **มีคำคมสร้างแรงบันดาลใจในการเรียนรู้บนหน้าแรก**

## 📈 ระบบบันทึกกิจกรรม (Logs)
- **บันทึกทุกกิจกรรมที่เกิดขึ้นในระบบ** (เข้าสู่ระบบ, ออกจากระบบ, สร้างโพสต์, แสดงความคิดเห็น, กดถูกใจ)
- **รองรับการเข้าสู่ระบบอัตโนมัติ** (ผู้ใช้ไม่ต้องเข้าสู่ระบบบ่อยๆ)
- **ระบบกดถูกใจบันทึกในรูปแบบ `user_id, post_id` (กดถูกใจ/ยกเลิกถูกใจได้)**

## 🚧 วิธีติดตั้ง

### **สิ่งที่ต้องเตรียมก่อนติดตั้ง**
- **PHP 8.x**
- **Composer**
- **MySQL**
- **Node.js & npm**

### **ขั้นตอนการติดตั้ง**
1. **โคลนโปรเจค**
   ```sh
   git clone https://github.com/your-repo/learnup.git
   cd learnup
   ```
2. **ติดตั้ง Dependencies**
   ```sh
   composer install
   npm install
   ```
3. **ตั้งค่า Environment**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
   - อัปเดตไฟล์ `.env` ด้วยค่าการเชื่อมต่อฐานข้อมูลของคุณ

4. **รันการ Migrate Database และลิงก์ Storage**
   ```sh
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **คอมไพล์ไฟล์ Assets**
   ```sh
   npm run build
   ```

6. **เริ่มรันเซิร์ฟเวอร์**
   ```sh
   php artisan serve
   ```
   แอปพลิเคชันจะเริ่มทำงานที่ `http://127.0.0.1:8000`

## 🚀 เริ่มต้นใช้งานเลย!
เริ่มแบ่งปันความรู้ไปกับ LearnUP วันนี้!

---
✨ **พัฒนาเพื่อทำให้การแบ่งปันความรู้เป็นเรื่องง่ายและมีประสิทธิภาพมากขึ้น!**
