Setup Path

cd E:\xampp\htdocs\Clone\FYP-EASE-Sarawak\easesarawak
composer install

Install dependencies: PHP 7.4–8.2 with extensions (intl, mbstring, json, curl, openssl), Composer, and a web server (XAMPP’s Apache/MySQL works). Place the repo in E:/xampp/htdocs/FYP.
Configure CI: copy env → .env; set CI_ENVIRONMENT = development, then uncomment app.baseURL and point it at your host (e.g. http://ease-sarawak/ once the vhost is ready). Update the DB section with your MySQL credentials (username, password, database name).
Database: create the schema in phpMyAdmin and import sql/order.sql plus sql/user.sql (or run php spark migrate if you have migrations). Verify tables populate and credentials in .env match.
Apache host: in httpd-vhosts.conf add a vhost pointing to E:/xampp/htdocs/FYP/public; in C:\Windows\System32\drivers\etc\hosts map 127.0.0.1 ease-sarawak. Restart Apache.
Assets/links: ensure your views use helpers (base_url('assets/...'), site_url('policy'), etc.) so navigation and resources respect the new base URL.
Launch: start Apache/MySQL from XAMPP, browse to http://ease-sarawak/. For CLI tasks use php spark … in E:/xampp/htdocs/FYP.
Verify: log in as admin, test /report chart + export, check key pages and forms. Keep an eye on writable/logs/ and browser console for errors. If you change configs, clear caches with php spark cache:clear.

Run migrations:

Run seeders:

All migrations maintain proper foreign key relationships, and seeders are designed to run in the correct order to avoid constraint violations!

php spark migrate

php spark db:seed Database_seeder
Seed individual tables:
php spark db:seed Users_seeder
php spark db:seed Admins_seeder
php spark db:seed Delivery_seeder
# etc.
Reset and re-seed the database:
php spark migrate:rollback    # Roll back all migrations
php spark migrate              # Run migrations again
php spark db:seed Database_seeder  # Seed all data


Testing Login Credentials:
https://fyp.easesarawak.com/admin
allan96@gmail.com
123

Ben@gmail.com
123