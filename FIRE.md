# 🔥 CN Tech Store - FIRE PROJECT

ระบบร้านค้าออนไลน์ (E-Commerce) แบบ Full Stack PHP + MySQL + Stripe

---

## 🚀 Project Overview

CN Tech Store คือระบบร้านค้าออนไลน์ที่พัฒนาแบบ custom ด้วย PHP โดยรองรับ:

- 🛒 ระบบสินค้า (Products)
- 🛍️ ตะกร้าสินค้า (Cart)
- 📦 ระบบออเดอร์ (Orders)
- 💳 Payment Gateway (Stripe / Bank / COD)
- 📊 ระบบฐานข้อมูล MySQL
- 📱 รองรับ Mobile Web

---

## 🧠 Tech Stack

- PHP (Core Backend)
- MySQL (Database)
- HTML / CSS / JavaScript
- Stripe API (Payment)
- Session Cart System
- InfinityFree Hosting

---

## 📦 System Modules

### 1. Product System
- แสดงสินค้า
- ดึงจาก MySQL
- รองรับ image + price + description

---

### 2. Cart System
- ใช้ PHP Session
- เพิ่ม / ลบสินค้า
- คำนวณราคารวม

---

### 3. Order System
- สร้าง order หลัง checkout
- บันทึก order_items
- เก็บ customer data

---

### 4. Payment System

#### 💳 Stripe (Card Payment)
- Stripe Checkout Session
- Redirect ไปหน้า Stripe
- Webhook (future upgrade)

#### 🏦 Bank Transfer
- BCEL / LDB / LNE / NAT (mock gateway)
- Redirect ไป API ธนาคาร

#### 🚚 Cash on Delivery (COD)
- สร้าง order แบบ pending
- แสดง POS order page

---

## 🗄️ Database Structure

### Tables

- users
- products
- categories
- carts (optional)
- orders
- order_items
- payments (future)
- shipping (future)

---

## ⚙️ Current Architecture

---

## 🔥 Current Status

### ✔ Completed
- Product listing
- Cart system
- Order creation
- Payment selection UI
- Stripe redirect (test mode)
- COD system

### ⚠️ Issues
- No Stripe webhook yet
- No admin dashboard
- No product management UI
- No authentication system
- Basic UI/UX only

---

## 🚧 Roadmap

### Phase 1 (MVP) ✔
- Basic e-commerce system

---

### Phase 2 (UI/UX Upgrade)
- Product detail page
- Search system
- Filter (price/category)
- Responsive UI (mobile-first)

---

### Phase 3 (Admin System)
- Add/Edit/Delete products
- Order management dashboard
- Stock control

---

### Phase 4 (Payment Production)
- Stripe webhook integration
- Payment verification system
- Anti-fraud logic

---

### Phase 5 (Scale System)
- Caching (performance)
- API separation (/api layer)
- CDN for images
- Optimization for traffic

---

## 🧠 Notes

- Stripe supports only USD / THB (not LAK)
- LAK must be converted for display only
- InfinityFree has limitations (no cron, limited PHP extensions)

---

## ⚡ Goal

Build a lightweight Lazada-style e-commerce system using PHP with real payment integration and scalable structure.