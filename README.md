# LearnUP - Knowledge Sharing Platform

## 🌟 Overview
LearnUP is a platform where users can **share knowledge** through **PDF files** and **blog posts**. It is designed to **facilitate learning** in an intuitive and collaborative environment.

## 🚀 Features
### **User Features**
- ✅ Create and edit blog posts with **Quill.js Editor**
- ✅ Upload **PDF files** and attach them to posts
- ✅ Preview PDF files with **PDF.js**
- ✅ Like posts (**toggle system: like/unlike**)
- ✅ Comment and reply to posts with notifications
- ✅ Persistent login (**Remember Me Token + Cookies**)
- ✅ Beautiful UI with **TailwindCSS**

### **Admin Features**
- ✅ Approve or reject user posts
- ✅ View statistics on:
  - Number of users, posts, likes, and comments
  - Active users (writers, admins, regular users)
  - Log history
- ✅ Manage user roles (User, Writer, Admin)
- ✅ Delete inappropriate posts and comments
- ✅ Dashboard notification system
- ✅ Activity logs tracking **(Login, Post Creation, Comments, Likes)**

### **Notification System**
- ✅ Notify **Admin** when a new post is awaiting approval
- ✅ Notify **Users** when their post is **approved/rejected**
- ✅ Notify **Users** when someone **likes or comments** on their post

## 🛠️ Technology Stack
| Component      | Technology |
|---------------|------------|
| **Backend**   | Laravel (Latest) |
| **Frontend**  | Blade + TailwindCSS |
| **Database**  | MySQL |
| **Editor**    | Quill.js |
| **File Storage** | Laravel Storage (Local) |
| **PDF Preview** | PDF.js / iframe |
| **Authentication** | Laravel Custom Auth |
| **Graph & Analytics** | Chart.js |

## 🏗️ User Roles
| Role     | Capabilities |
|----------|--------------------------------------------------|
| **User** | Create posts (requires approval), Upload PDFs, Comment |
| **Writer** | Directly post without approval, Upload PDFs, Comment |
| **Admin** | Approve/reject posts, View statistics, Manage users, Monitor logs |

## 📊 Dashboard Statistics
- **Total posts, users, writers, admins, and logs**
- **Line charts for post creation, comments, and registrations (Last 7 Days)**
- **Bar charts for logs categorized by actions (login, create, comment, etc.)**

## 🔄 Flow of Operations
📌 **User**
1. **Login → Dashboard**
2. **Create a post + Upload PDFs**
3. **Wait for approval** (Admin Notification triggered)
4. **If approved, post is published**

📌 **Writer**
1. **Login → Dashboard**
2. **Create a post + Upload PDFs**
3. **Post goes live instantly**

📌 **Admin**
1. **Login → Dashboard**
2. **Approve/reject posts**
3. **Monitor statistics and logs**
4. **Manage user roles and content**

## 📂 File Structure (Frontend)
```
resources/
|- Auth/
|    
|- User (user & writer)/
|    |- dashboard.blade.php
|- Admin/
|    |- dashboard.blade.php
```

## 🎨 UI Design
- **Uses TailwindCSS for styling**
- **Green theme inspired by Kasetsart University**
- **Encouraging learning quotes on the homepage**

## 📌 Additional Notes
- **All interactions log events** (Login, Logout, Post, Comment, Like)
- **Persistent login enabled** (Users don’t need to log in frequently)
- **System records likes in `user_id, post_id` (toggle like/unlike)**

---
🚀 **LearnUP is built to make knowledge sharing easier and more efficient!**

