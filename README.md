

---

## 1. Clone the repository

```bash
git clone https://github.com/KhadijaOuf/quiz_platform.git
cd quiz_platform
```
---

## 2. Backend: PHP & Laravel setup

### Requirements

You must have:
* PHP 
* Composer
* A database (MySQL / PostgreSQL / SQLite)
* Node.js (for Inertia & Tailwind)

---

### Install PHP dependencies

```bash
composer install
```

---

### Environment file

edit `.env`:

* `APP_NAME`
* `APP_URL`
* Database credentials

Example (local):

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
```
---

### Generate application key

```bash
php artisan key:generate
```

---

## 3. Database setup

Create an empty database, then:

```bash
php artisan migrate
```
---

## 4. Frontend: Inertia + Tailwind

Install JS dependencies:

```bash
npm install
```

Build assets:

```bash
npm run dev
```

---

## 5. Run the app

```bash
php artisan serve
```

Visit:
```
http://127.0.0.1:8000
```
---
