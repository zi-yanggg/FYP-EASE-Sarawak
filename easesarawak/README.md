# EASE Sarawak — Computing Technology Final Year Project

A booking and delivery management system for Sarawak, built with CodeIgniter 4. Features customer booking, Stripe payment processing, and an admin portal for order, user, promo code, service, and revenue management.

---

## Created by:

* Benjamin Hii
* Lim Zi Yang
* Aung Zin Htet
* Jostin Chok Yaw Seng

---

## Requirements

| Dependency | Version |
|------------|---------|
| PHP | >= 8.1 |
| MySQL | >= 5.7 |
| XAMPP | >= 8.1 |
| Composer | >= 2.x |

---

## Project Setup

### 1. Clone the repository

Place the project inside your XAMPP `htdocs` directory:

```
C:\xampp\htdocs\New\FYP-EASE-Sarawak\easesarawak\
```

### 2. Install dependencies

Open a terminal inside the project root and run:

```bash
composer install
```

### 3. Set up the environment file

Copy the example env file and rename it:

```bash
cp env .env
```

Open `.env` and configure the following:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://easesarawak.fyp/'

database.default.hostname = localhost
database.default.database = easesarawak
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

### 4. Create the database

Open **phpMyAdmin** (`http://localhost/phpmyadmin`) and create a database named:

```
easesarawak
```

Then run the migrations:

```bash
php spark migrate
```

### 5. Configure Stripe (optional, for payments)

Add your Stripe secret key to `.env`:

```ini
STRIPE_SECRET_KEY = sk_test_your_key_here
STRIPE_WEBHOOK_SECRET = whsec_your_secret_here
```

---

## Custom Local Domain Setup (`easesarawak.fyp`)

To access the project at `http://easesarawak.fyp/` instead of `http://localhost/New/FYP-EASE-Sarawak/easesarawak/public/`, follow these steps.

### Step 1 — Edit the Windows hosts file

Open **Notepad as Administrator** and open the file:

```
C:\Windows\System32\drivers\etc\hosts
```

Add the following line at the bottom:

```
127.0.0.1   easesarawak.fyp
```

Save and close the file.

### Step 2 — Enable Apache Virtual Hosts

Open:

```
C:\xampp\apache\conf\httpd.conf
```

Find and uncomment this line (remove the `#`):

```apache
# Include conf/extra/httpd-vhosts.conf
```

It should look like:

```apache
Include conf/extra/httpd-vhosts.conf
```

### Step 3 — Add the Virtual Host

Open:

```
C:\xampp\apache\conf\extra\httpd-vhosts.conf
```

Add the following block at the bottom:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/New/FYP-EASE-Sarawak/easesarawak/public"
    ServerName easesarawak.fyp
    <Directory "C:/xampp/htdocs/New/FYP-EASE-Sarawak/easesarawak/public">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

> If you still need the default `localhost` to work for other projects, also add this block:
>
> ```apache
> <VirtualHost *:80>
>     DocumentRoot "C:/xampp/htdocs"
>     ServerName localhost
> </VirtualHost>
> ```

### Step 4 — Update CodeIgniter base URL

In your `.env` file, set:

```ini
app.baseURL = 'http://easesarawak.fyp/'
```

### Step 5 — Restart Apache

Open the **XAMPP Control Panel** and click **Stop** then **Start** on Apache.

### Step 6 — Verify

Open your browser and go to:

```
http://easesarawak.fyp/
```

You should see the EASE Sarawak homepage.

---

## Project Structure

```
easesarawak/
├── app/
│   ├── Config/             # App, database, routes, filters configuration
│   ├── Controllers/        # Admin, Home, Login, CardPayment, Profile, etc.
│   ├── Database/           # Migrations and seeds
│   ├── Filters/            # Auth and admin access filters
│   ├── Helpers/            # Custom helper functions
│   ├── Models/             # OrderModel, UserModel, PaymentModel, etc.
│   └── Views/              # Booking, admin, auth, email templates
├── public/                 # Web root (point Apache DocumentRoot here)
│   └── assets/             # CSS, JS, images, fonts
├── tests/                  # PHPUnit tests (unit, database, session)
├── vendor/                 # Composer dependencies
├── writable/               # Cache, logs, sessions, uploads
├── .env                    # Environment configuration (not committed)
├── env                     # Environment template
└── composer.json
```

---

## Key Routes

| Method | URL | Description |
|--------|-----|-------------|
| GET | `/` | Homepage |
| GET | `/booking` | Booking page |
| GET/POST | `/payment` | Payment page (Stripe) |
| GET | `/login` | Login page |
| GET/POST | `/forgot_password` | Forgot password |
| GET | `/admin` | Admin dashboard |
| GET | `/order` | Admin — orders |
| GET | `/user` | Admin — users |
| GET | `/report` | Admin — revenue report |
| GET | `/report/export` | Export revenue CSV |
| GET | `/admin/promo_code` | Admin — promo codes |
| GET | `/admin/service_management` | Admin — service pricing |

---