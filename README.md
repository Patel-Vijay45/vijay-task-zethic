# 🛒 Laravel eCommerce API – Zethic Technologies Task

This is a feature-rich eCommerce API built using **Laravel 11**, designed with best practices including:
- Service & Repository pattern
- Event-Listener-Job architecture
- Email notifications (via Mailtrap)
- PDF invoice generation
- Fake payment gateway
- Scribe API documentation
- Queued jobs, Policies, and Observers

---

## 🚀 Features

- User & Admin roles with login/register/logout
- Product & category management (CRUD, soft deletes)
- Order placement with item-level detail
- Invoice PDF generation (DomPDF)
- Notifications via queued jobs
- Event-based architecture: `OrderPlaced`, `SendOrderEmailJob`
- Custom artisan command to cancel old orders
- Fake webhook-based payment integration
- RESTful API with Scribe-generated documentation
- Log product creation and deletion events 

---

## ⚙️ Tech Stack

- PHP 8.3
- Laravel 11
- Sanctum (API authentication)
- MySQL
- DomPDF (invoice generation)
- Laravel Scribe (API docs)
- Mailtrap (for test emails)

---

## 🛠 Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/your-username/zethic-laravel-ecommerce-api.git
cd zethic-laravel-ecommerce-api
```
 
### 2. Install dependencies

```bash
composer install
```
 
### 3. Environment configuration

```bash
cp .env.example .env
php artisan key:generate



DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_pass
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="Zethic eCommerce"

```


### 4. Run migrations & seeders

```bash
php artisan migrate --seed
```
 
### 5. Serve the app

```bash
php artisan serve
```
 
### 6. Start queue worker (for jobs)

```bash
php artisan queue:work
```
 

 ## 📬 API Documentation


```bash
http://localhost:8000/docs
```
---
 ## 🧾 Custom Artisan Commands
Cancel old unprocessed orders:

```bash
php artisan orders:cancel-old
```
---
 ## Postman Collection link bellow
https://indus4-6903.postman.co/workspace/Indus-Workspace~afa29aa1-839d-403c-88c0-eaf60192be0d/collection/39495880-beaab2f4-196b-4c46-9a66-01073c589252?action=share&creator=39495880
  
---
