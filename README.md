# 🧪 AllergenGo

AllergenGo is a Laravel-based application designed to help people with allergies find suitable foods for them at restaurants. This setup guide is tailored for running the project using **Laravel Herd on Windows**.

---

## 🚀 Project Setup

### 1. Install Laravel Herd

Download and install [Laravel Herd for Windows](https://herd.laravel.com/).

Herd simplifies setting up a laravel app, no virtual machines needed. It will handle php installations for you.

---

### 2. Clone the Repository

Clone the project into Herd’s default directory (usually `/herd`). Must be inside herd folder:

git clone <your-repo-url> /herd/allergen-go


### 3. Set up the database

Make sure Mysql is installed.

mysql -u root -p
CREATE DATABASE allergen_go;

### 4. set up app

 inside the env, update these values:

    DB_DATABASE=allergen_go #whatever you names your DB
    DB_USERNAME=root
    DB_PASSWORD=           # (leave blank or enter your MySQL root password)
    APP_URL=http://allergen-go.test #herd should add for you, and will add entry into your hosts file

 Once that is done, run the below to populate your DB with the correct tables:

 php artisan:migrate

 App should work on this url, ow whatever is in your env APP URL (ensure this matches herd):

 http://a-go.test/admin/login
