# 🔒 Security Enhancements - PHP Blog Application

![Status](https://img.shields.io/badge/Status-Completed-brightgreen)
![Built With](https://img.shields.io/badge/Built%20With-PHP-blue)
![UI](https://img.shields.io/badge/UI-Bootstrap%205-purple)
![Security](https://img.shields.io/badge/Security-Enhanced-red)
![Roles](https://img.shields.io/badge/Feature-User%20Roles-yellowgreen)

> 🕒 **Timeline:** 10 Days  
> 📌 **Task 4 - Internship Project**  
> 🏢 **Company:** Apex Planet Pvt. Ltd.  
> 📁 [GitHub Repo](https://github.com/ManasRanjanJena6/php_webdev_proj/tree/main/Task_4/Security_Enhancements)

---

## 🎯 Objective

Secure the blog application against common web vulnerabilities by implementing:

- 🛡️ **Prepared Statements** to prevent SQL injection attacks.
- ✅ **Form Validation** for data integrity and user experience.
- 🔑 **User Roles and Permissions** for role-based access control.

---

## ✅ Features

### 🛡️ 1. Prepared Statements
- All database queries use **PDO** or **MySQLi** prepared statements.
- Protects against **SQL injection** attacks.
- Ensures secure handling of user inputs.

### ✅ 2. Form Validation
- **Server-Side Validation**:
  - Validates all form inputs to ensure data integrity.
  - Prevents invalid or malicious data from being processed.
- **Client-Side Validation**:
  - Provides instant feedback to users for a better experience.
  - Ensures required fields are filled and data formats are correct.

### 🔑 3. User Roles and Permissions
- Extended the `users` table to include roles (`admin`, `editor`, `user`).
- Implemented **Role-Based Access Control (RBAC)**:
  - Admin: Full access to all features.
  - Editor: Can create, edit, and delete posts.
  - User: Can view posts only.
- Restricted access to sensitive pages based on roles.

---

## 🧰 Tech Stack

| Layer        | Technology        |
|--------------|-------------------|
| Frontend     | HTML5, CSS3, Bootstrap 5 |
| Backend      | PHP (Core)        |
| Database     | MySQL             |
| Server       | Apache via XAMPP  |

---

## 📂 Folder Structure
Task_4/ 
├── db.php 
├── auth.php 
├── index.php 
├── view_posts.php 
├── edit_post.php 
├── delete_post.php 
├── create_post.php 
├── admin_dashboard.php 
├── editor_dashboard.php 
├── unauthorized.php 
├── assets/ 
│ └── style.css 
└── README.md
---

## 🧑‍💻 Installation & Setup

1. **Download & Install XAMPP**  
   👉 [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)

2. **Clone Repository** into `htdocs`  
   ```bash
   git clone https://github.com/ManasRanjanJena6/php_webdev_proj.git