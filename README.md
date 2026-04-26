# Laravel Stripe PaymentIntent Checkout

A complete real-world Stripe payment integration built with Laravel.

This project demonstrates a **production-level payment flow** using Stripe PaymentIntent, Stripe Elements, and Webhooks with proper validation and error handling.

---

## 🚀 Features

* Stripe PaymentIntent integration
* Stripe Elements (Card, Email, Billing Address)
* Custom checkout (no Stripe hosted page)
* Frontend + Backend validation
* Webhook handling (success, failure, updates)
* Secure payment flow
* Clean architecture

---

## 🎯 Why This Project?

This project shows how to build a **real-world Stripe integration** with:

* Full control over UI/UX
* Secure backend validation
* Webhook-based payment confirmation
* Production-level structure

---

## 🛠 Tech Stack

* Laravel
* Stripe API
* Stripe JS (v3)
* Blade Templates
* JavaScript

---

## ⚙️ Installation

### 1. Clone Repo

```bash
git clone https://github.com/deepak18121988/laravel-stripe-payment-intent-checkout-webhook
cd laravel-stripe-payment-intent-checkout-webhook
```

---

### 2. Install Dependencies

```bash
composer install
```

---

### 3. Setup Environment

```bash
cp .env.example .env
```

Update `.env`:

```
APP_URL=http://localhost:8000

STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=
```

---

### 4. Generate App Key

```bash
php artisan key:generate
```

---

## 🗄 Database Setup

```bash
php artisan migrate:fresh --seed
```

✔ Creates tables
✔ Inserts required data

---

## 💳 Stripe Setup

### Webhook Endpoint

```
POST /api/psp/webhooks/stripe
```

---

### Run Webhook Listener

```bash
stripe listen --forward-to localhost:8000/api/psp/webhooks/stripe
```

---

## ▶️ Run Project

```bash
php artisan serve
```

Open:

```
http://localhost:8000
```

---

## 🧪 Test Card

```
4242 4242 4242 4242
Any future date | Any CVC
```

---

## 🔐 Security

* Webhook signature verification enabled
* Payment confirmed via webhook (not frontend)
* Sensitive keys stored in `.env`

---

## 📁 Project Structure

```
app/
database/
 ├── migrations/
 ├── seeders/
resources/views/
routes/
```

---

## 📸 Screenshots


| Checkout | Payment Process | Success | Failure |
|----------|--------|--------|--------|
| ![Checkout](screenshots/checkout.png) | ![Payment Process](screenshots/payment_process.png) | ![Success](screenshots/payment_success.png) | ![Failure](screenshots/payment_fail.png) |


---

## 🌐 API

```
POST /api/psp/webhooks/stripe
```

---

## ⚠️ Notes

* Do not skip database seeding
* Run `composer dump-autoload` if error occurs

---

## 💼 Author

**Deepak Lohani**
Laravel Developer | Payment Integration Specialist

GitHub: https://github.com/deepak18121988

---

## ⭐ Support

If you find this useful, give it a ⭐ on GitHub!
