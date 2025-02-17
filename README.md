# LearnUP - Knowledge Sharing Platform

## ✨ Overview
LearnUP is a platform designed to **share knowledge** through **PDF files** and **blog posts**. It provides an intuitive and collaborative environment to facilitate learning.

## 🚀 Features

### **User Features**
- ✅ Create and edit blog posts using **Quill.js Editor**
- ✅ Upload and attach **PDF files** to posts
- ✅ Preview PDF files using **PDF.js**
- ✅ Like/unlike posts (**toggle system**)
- ✅ Comment and reply to posts with notifications
- ✅ Elegant UI with **TailwindCSS**

### **Admin Features**
- ✅ Approve or reject user posts
- ✅ View statistics including:
  - Number of users, posts, likes, and comments
  - Active users (writers, admins, and regular users)
  - Log history
- ✅ Manage user roles (**User, Writer, Admin**)
- ✅ Remove inappropriate posts and comments
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

## 🏢 User Roles
| Role     | Capabilities |
|----------|--------------------------------------------------|
| **User** | Create posts (requires approval), Upload PDFs, Comment |
| **Writer** | Directly post without approval, Upload PDFs, Comment |
| **Admin** | Approve/reject posts, View statistics, Manage users, Monitor logs |

## 📊 Dashboard Statistics
- **Total posts, users, writers, admins, and logs**
- **Line charts for post creation, comments, and registrations (Last 7 Days)**
- **Bar charts for logs categorized by actions (login, create, comment, etc.)**

## 💀 Flow of Operations

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

## 🎨 UI Design
- **TailwindCSS for styling**
- **Green theme inspired by Kasetsart University**
- **Motivational learning quotes on the homepage**

## 📈 Logging System
- **All interactions log events** (Login, Logout, Post, Comment, Like)
- **Persistent login enabled** (Users don’t need to log in frequently)
- **Likes are recorded in `user_id, post_id` (toggle like/unlike)**

## 🚧 Installation Guide

### **Prerequisites**
Ensure you have the following installed on your system:
- **PHP 8.x**
- **Composer**
- **MySQL**
- **Node.js & npm**

### **Installation Steps**
1. **Clone the Repository**
   ```sh
   git clone https://github.com/your-repo/learnup.git
   cd learnup
   ```
2. **Install Dependencies**
   ```sh
   composer install
   npm install
   ```
3. **Configure Environment**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
   - Update `.env` with database credentials.

4. **Migrate Database & Link Local Storage**
   ```sh
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Build Assets**
   ```sh
   npm run build
   ```

6. **Start the Development Server**
   ```sh
   php artisan serve
   ```
   The application should now be running at `http://127.0.0.1:8000`.

## 🚀 Get Started
Start sharing knowledge with LearnUP today!

---
✨ **Built to make knowledge sharing easier and more efficient!**

