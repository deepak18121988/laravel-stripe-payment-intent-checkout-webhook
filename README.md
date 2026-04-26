# Laravel Stripe PaymentIntent Checkout

A complete real-world Stripe payment integration built with Laravel.

This project demonstrates a full production-level payment flow using Stripe PaymentIntent, Stripe Elements, and Webhooks with proper validation and error handling.

---

## 🚀 Features

* Stripe PaymentIntent integration
* Stripe Elements (Card, Email, Billing Address)
* Custom checkout form (not Stripe hosted page)
* Frontend validation (JavaScript)
* Backend validation (Laravel)
* Webhook handling (payment success, failure, updates)
* Payment processing flow
* Success & failure pages
* Clean and structured code

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
7. Backend validation (Laravel API)
8. Stripe `confirmPayment` triggered
9. If required → 3D Secure authentication
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

## ⚙️ Configuration

### 1. Clone Repository

git clone https://github.com/deepak18121988/laravel-stripe-payment-intent-checkout-webhook

---

### 2. Install Dependencies

composer install

OR 

composer update

---

### 3. Setup Environment

Create `.env` file and add:

STRIPE_KEY=your_stripe_publishable_key

STRIPE_SECRET=your_stripe_secret_key

STRIPE_WEBHOOK_SECRET=your_webhook_secret

APP_URL=http://localhost:8000

---

### 4. Generate App Key

php artisan key:generate

---

### 5. Database Setup (Important)

To properly run this project, you need to setup the database along with required default data.

---

### A. Configure Database

Update your `.env` file:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=your_database_name

DB_USERNAME=root

DB_PASSWORD=

---

### B. Run Migrations

This will create all required tables:

php artisan migrate

---

### C. Seed Default Data

This project requires some default data like:

* Payment Statuses (Pending, Completed, Failed, etc.)
* PSP Vendor (Stripe)
* Supported Payment Methods

Run the following command:

php artisan db:seed

---

### D. Fresh Setup (Recommended)

If you are setting up the project for the first time, run:

php artisan migrate:fresh --seed

This will:

* Drop all tables
* Recreate tables
* Insert required default data

---

### E. Verify Database

After seeding, check these tables:

* payment_statuses ✅
* psp_vendors ✅
* psp_supported_payment_methods ✅

These are required for payment flow to work correctly.

---

### ⚠️ Important Notes

* Do NOT skip seeding — payment flow depends on it
* Make sure `.env` database credentials are correct
* If you face any seeder error, run:

composer dump-autoload

---

### 🧪 Quick Check

If everything is working:

* Checkout page loads
* PaymentIntent creates successfully
* Payment status updates in DB after webhook

---

Now your database is fully ready for Stripe payment flow 🚀

---

## 💳 Stripe Setup

1. Create account on Stripe
2. Get API keys from Stripe Dashboard
3. Add keys in `.env` file
4. Setup webhook endpoint

Example webhook URL:

http://localhost:8000/stripe/webhook

---

## 🔔 Webhook Configuration

Install Stripe CLI and run:

stripe listen --forward-to localhost:8000/api/psp/webhooks/stripe

Copy the webhook signing secret and add it to `.env`:

STRIPE_WEBHOOK_SECRET=your_webhook_secret

---

## ▶️ Run Project

php artisan serve

Open in browser:

http://localhost:8000

---

## 🧪 Test Cards

Use Stripe test card:

Card Number: 4242 4242 4242 4242
Expiry: Any future date
CVC: Any 3 digits

---

## 🌍 Payment Flow Testing

* Successful payment → redirected to success page
* Failed payment → redirected to failure page
* Webhook updates payment status in database

---

## 📸 Screenshots

(Add screenshots here)

* Checkout Page
* Payment Form
* Success Page
* Error Handling

---

## ⚠️ Important Note

This project is inspired by real-world client implementations involving Stripe payment systems, without exposing any private or proprietary source code.

---

## 💼 Author

Deepak Lohani
Laravel Developer | Stripe Payment Specialist

GitHub: https://github.com/deepak18121988

---

## ⭐ If you find this useful

Give it a star ⭐ on GitHub — it helps others discover the project!