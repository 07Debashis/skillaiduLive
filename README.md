# Skilledu — PHP + MySQL E-Learning Platform

A modern, dark-themed e-learning website built with **PHP 8 + MySQL + Tailwind CDN**.
Drop-in for **XAMPP**.

## 🚀 Setup (XAMPP)

1. **Copy the folder** to your XAMPP htdocs:
   ```
   C:\xampp\htdocs\skilledu\
   ```
   (On Mac: `/Applications/XAMPP/htdocs/skilledu/`)

2. **Start Apache + MySQL** in the XAMPP control panel.

3. **Create the database**:
   - Open <http://localhost/phpmyadmin>
   - Click **Import**, choose `database/schema.sql`, click **Go**.
   - This creates the `skilledu` database with sample categories and 3 demo courses.

4. **(Optional) Update DB credentials** in `config/db.php` if your MySQL user/password is not the default `root` / empty.

5. **Open the site**: <https://skillaidu.com/index.php>

## 👤 First admin

The **first user who signs up automatically becomes admin**.
Go to <https://skillaidu.com/signup.php> and create your account.
After login you'll see an **Admin** link in the navbar.

## 📂 Structure

```
skilledu/
├── assets/         CSS, JS, images
├── config/db.php   PDO database connection
├── includes/       header, footer, sidebar, functions
├── auth/           login / register / logout handlers
├── admin/          admin dashboard + CRUD
├── pages/          public pages (courses, course-view)
├── database/       schema.sql to import
├── index.php       landing page
├── login.php
└── signup.php
```

## ✨ Features

- 🎨 Modern dark UI (Tailwind CDN + custom CSS)
- 🔐 Secure auth (PHP `password_hash` / `password_verify`, sessions)
- 👑 Auto-admin for first signup, role-based access
- 📚 Full CRUD for courses (admin panel)
- 👥 User management (promote/demote)
- 🔍 Search + category filter on course catalog
- 🎬 YouTube embeds + .mp4 video support on course detail
- 📱 Responsive layout

## 🛠 Adding courses daily

Login as admin → click **Admin** → **Manage Courses** → **+ New course**.
Paste a thumbnail URL (Unsplash, Imgur, etc.) and a YouTube link, then publish.

## 🔒 Production notes

Before going live: change DB credentials, enable HTTPS, and consider rate-limiting login attempts.
