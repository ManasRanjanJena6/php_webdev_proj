# 📝 PHP Blog - CRUD Application with User Authentication

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Status](https://img.shields.io/badge/status-active-brightgreen)
![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)
![Built With](https://img.shields.io/badge/built%20with-PHP-orange)
![XAMPP](https://img.shields.io/badge/env-XAMPP-lightgrey)

---

## 🚀 Features

- ✅ User Registration & Login (with password hashing)
- 🛡️ Session-based Authentication
- 📄 Create, Read, Update, Delete (CRUD) for blog posts
- 🎨 Responsive UI with Bootstrap 5

---

## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Backend**: PHP (Core)
- **Database**: MySQL
- **Server**: Apache (via XAMPP)

---

## 📂 Project Structure

---

## 🧑‍💻 Installation & Setup (XAMPP)

1. **Download & Install XAMPP**  
   [XAMPP Download Link](https://www.apachefriends.org/index.html)

2. **Start Apache & MySQL** via XAMPP Control Panel.

3. **Clone or Copy this Repository** into your `htdocs` directory:

4. **Create MySQL Database**  
Open `phpMyAdmin` → create a database named `blog`.

5. **Run SQL to create tables:**

```sql
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL
);

CREATE TABLE posts (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
content TEXT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
user_id INT,
FOREIGN KEY (user_id) REFERENCES users(id)
);

6. **Access the app:**
http://localhost/blog-app/register.php