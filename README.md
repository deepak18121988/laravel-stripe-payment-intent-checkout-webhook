# Laravel Stripe PaymentIntent Checkout

A complete real-world Stripe payment integration built with Laravel.

This project demonstrates a **production-level payment flow** using Stripe PaymentIntent, Stripe Elements, and Webhooks with proper validation and error handling.

---

## 🚀 Features

* Stripe PaymentIntent integration
* Stripe Elements (Card, Email, Billing Address)
* Custom checkout form (no Stripe hosted page)
* Frontend validation (JavaScript)
* Backend validation (Laravel)
* Webhook handling (success, failure, updates)
* Secure payment processing flow
* Success & failure handling
* Clean and structured architecture

---

## 🎯 Why This Project?

This project demonstrates how to build a **production-ready Stripe integration** instead of using basic hosted checkout.

It focuses on:

* Full control over UI/UX
* Secure backend validation
* Webhook-driven payment updates
* Real-world architecture

---

## 🔄 Payment Flow (Step-by-Step)

1. User opens checkout page
2. Laravel creates PaymentIntent via API
3. Client receives `client_secret`
4. Stripe Elements render:

   * Card input
   * Email (Link Authentication)
   * Billing Address
5. User fills details
6. Frontend validation (JavaScript)
7. Backend validation (Laravel)
8. Stripe `confirmPayment` triggered
9. 3D Secure (if required)
10. Webhook updates payment status in database
11. User redirected to:

* Success page
* Failure page

---

## 🧠 Real-world Concepts Covered

* PaymentIntent lifecycle handling
* Secure payment confirmation
* Webhook signature verification
* Handling asynchronous payments
* Error handling & validation
* Preventing invalid transactions

---

## 🛠 Tech Stack

* Laravel
* Stripe API
* Stripe JS (v3)
* Blade Templates
* JavaScript (Vanilla)

---

## ⚙️ Installation & Setup

### 1. Clone Repository

```bash
git clone https://github.com/deepak18121988/laravel-stripe-payment-intent-checkout-webhook
cd laravel-stripe-payment-intent-checkout-webhook
```

---

### 2. Install Dependencies

```bash
composer install
```

OR

```bash
composer update
```

---

### 3. Setup Environment

```bash
cp .env.example .env
```

Update `.env`:

```
APP_URL=http://localhost:8000

STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

---

### 4. Generate App Key

```bash
php artisan key:generate
```

---

## 🗄 Database Setup (Important)

### Run Migration + Seeder

```bash
php artisan migrate:fresh --seed
```

This will:

* Create all tables
* Insert required default data

---

### Required Tables

* payment_statuses
* psp_vendors
* psp_supported_payment_methods

---

### ⚠️ Notes

* Do NOT skip seeding — payment flow depends on it
* If seeder fails, run:

```bash
composer dump-autoload
```

---

## 💳 Stripe Setup

1. Create account on Stripe
2. Get API keys from dashboard
3. Add keys in `.env`
4. Setup webhook endpoint

---

### 🔗 Webhook Endpoint

```
POST /api/psp/webhooks/stripe
```

---

## 🔔 Webhook Configuration

```bash
stripe listen --forward-to localhost:8000/api/psp/webhooks/stripe
```

Copy the webhook signing secret and add it to `.env`:

```
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

---

## ▶️ Run Project

```bash
php artisan serve
```

Open in browser:

```
http://localhost:8000
```

---

## 🧪 Test Cards

Use Stripe test card:

```
Card Number: 4242 4242 4242 4242
Expiry: Any future date
CVC: Any 3 digits
```

---

## 🌍 Payment Flow Testing

* Successful payment → success page
* Failed payment → failure page
* Webhook updates DB automatically

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

*Add your screenshots here*

Example:

```
screenshots/checkout.png
screenshots/payment.png
screenshots/success.png
```

---

## ⚠️ Important Note

This implementation reflects real-world payment systems used in production applications.

No private or proprietary client code is included.

---

## 💼 Author

**Deepak Lohani**
Laravel Developer | Payment Integration Specialist

🔗 GitHub: https://github.com/deepak18121988

---

## ⭐ Support

If you find this useful, give it a ⭐ on GitHub!
