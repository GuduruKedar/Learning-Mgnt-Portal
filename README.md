# 🎓 Learning Management System (LMS)

Welcome to the LMS project! This guide provides a complete, step-by-step procedure to set up the development environment using **Laravel Herd**, install the required tools, and get the project running on your local machine.

## 📑 Table of Contents
- [Prerequisites](#-prerequisites)
- [1. Install Laravel Herd](#1-install-laravel-herd)
- [2. Project Setup](#2-project-setup)
- [3. Database Setup](#3-database-setup)
- [4. Running the Project](#4-running-the-project)

---

## 🛠 Prerequisites

To run this Laravel project locally, you will need:
- **[Laravel Herd](https://herd.laravel.com/)**: A blazing fast, native Laravel and PHP development environment (which replaces the need for XAMPP). It automatically includes PHP 8.x, Composer, and the Laravel Installer.

---

## 1. Install Laravel Herd

1. **Download:** Get the Laravel Herd installer for Windows from the official website: [herd.laravel.com](https://herd.laravel.com/).
2. **Install:** Run the installer and follow the standard installation steps.
3. **Launch:** Open Laravel Herd. It will automatically set up PHP (e.g., PHP 8.2 / 8.3) and Composer on your system's path.
4. **Services (Database):** If you are using Herd Pro, ensure the **MySQL** service is started in the Herd services tab. If you are using the free version of Herd, you can use a lightweight database server like [DBngin](https://dbngin.com/) or a standard MySQL/MariaDB installation.

> **Note:** Because Herd includes Composer out of the box, you do not need to install Composer separately!

---

## 2. Project Setup

Once Laravel Herd is running, follow these steps to configure the project.

1. **Open Terminal:**
   Open a terminal (PowerShell or Command Prompt) and navigate to the project directory:
   ```bash
   cd path\to\your\project
   ```
   *(Since you placed the project in your Herd directory, it is likely `cd C:\Users\yourusername\Herd\lms`)*

2. **Install PHP Dependencies:**
   Run the following command to install all the required Laravel packages:
   ```bash
   composer install
   ```

3. **Environment Configuration:**
   Copy the example environment file to create your active `.env` file:
   ```bash
   copy .env.example .env
   ```

4. **Generate Application Key:**
   Generate the unique encryption key for your Laravel app:
   ```bash
   php artisan key:generate
   ```

---

## 3. Database Setup

1. **Create Database:** Open your preferred database management tool (like Herd's built-in database manager, TablePlus, or HeidiSQL) and create a new MySQL database named `lms_db`.
2. **Update `.env`:** Open the `.env` file in your project folder using a text editor. Update the database section to match your setup:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lms_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   > **Note:** Default local databases usually use `root` as the username with a blank password.

3. **Run Migrations:**
   In your terminal, run the following command to create all the necessary database tables:
   ```bash
   php artisan migrate
   ```
   > **Tip:** If the project requires default testing data, run `php artisan migrate --seed` instead.

---

## 4. Running the Project

Because you are using Laravel Herd, your site might already be automatically served!

1. **Herd Auto-Serving:**
   Since your project is located in the Herd parked directory (`Herd\lms`), Herd automatically serves it. You can simply open your browser and visit:
   **`http://lms.test`**

2. **Manual Serving (Alternative):**
   If you prefer to run it manually using artisan, ensure you are in the project root and run:
   ```bash
   php artisan serve
   ```
   Then navigate to **`http://127.0.0.1:8000`** in your web browser.

The project should now be running! 🎉
