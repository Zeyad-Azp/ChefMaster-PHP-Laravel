git clone https://github.com/ZeyadAzab/ChefMaster-Laravel.git
    cd ChefMaster-Laravel
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Configuration:**
    *   Create a `.env` file from the example: `cp .env.example .env`
    *   Generate the application key: `php artisan key:generate`
    *   Add your Spoonacular API keys in `.env`:
        ```env
        SPOONACULAR_API_KEYS=key1,key2,key3
        ```

4.  **Database Setup:**
    *   Ensure the SQLite file exists: `touch database/database.sqlite` (or `echo. > database/database.sqlite` on Windows).
    *   Run migrations to create the schema:
        ```bash
        php artisan migrate
        ```

5.  **إليك ملف **README.md** احترافي وشامل لمشروعك، مصمم خصيصاً ليعكس متطلبات **Assignment 2** لطلاب كلية الحاسبات والذكاء الاصطناعي بجامعة القاهرة، مع مراعاة تفاصيل مشروعك "ChefMaster".

---

# ChefMaster – Laravel MVC Migration
### IS333 Web-Based Information Systems | Assignment 2
**Faculty of Computers and Artificial Intelligence – Cairo University**

## 📌 Project Overview
This project is a migration of the **ChefMaster** Single-Page Application (SPA) originally built in Assignment 1 into a full **Laravel MVC** architecture. It features a robust backend for recipe management, third-party API integration with Spoonacular, and automated testing, all structured according to modern PHP conventions.

## 🚀 Key Requirements Fulfilled
*   **MVC Architecture:** Recreated all features from Assignment 1 using Laravel Models, Controllers, and Blade Views.
*   **Database Management:** Migrated raw SQL schemas into **Laravel Migrations** using **SQLite** for seamless submission and portability.
*   **Validation:** Implementation of dual-layer validation:
    *   **Server-side:** Utilizing Laravel's built-in validation rules in Controllers.
    *   **Client-side:** Retaining original JavaScript validation logic.
*   **Blade Templating:** Created a Master Layout (`app.blade.php`) with reusable header and footer components.
*   **Third-Party API:** Integrated Spoonacular API with a quota-rotation system, handling errors gracefully and storing keys securely in the `.env` file.
*   **Automated Testing:** Includes at least three automated tests (Feature and Unit tests) to verify core functionality.

## 🛠️ Technical Stack
*   **Framework:** Laravel 11.x
*   **Language:** PHP 8.2+
*   **Database:** SQLite (configured for submission)
*   **Frontend:** Blade Templating Engine, CSS, and JavaScript
*   **Testing:** PHPUnit / Pest

## 📥 Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/ZeyadAzab/ChefMaster-Laravel.git
    cd ChefMaster-Laravel
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Configuration:**
    *   Create a `.env` file from the example: `cp .env.example .env`
    *   Generate the application key: `php artisan key:generate`
    *   Add your Spoonacular API keys in `.env`:
        ```env
        SPOONACULAR_API_KEYS=key1,key2,key3
        ```

4.  **Database Setup:**
    *   Ensure the SQLite file exists: `touch database/database.sqlite` (or `echo. > database/database.sqlite` on Windows).
    *   Run migrations to create the schema:
        ```bash
        php artisan migrate
        ```

5.  **Run the application:**
    ```bash
    php artisan serve
    ```
    Access the project at: `[http://127.0.0](http://127.0.0)إليك ملف **README.md** احترافي وشامل لمشروعك، مصمم خصيصاً ليعكس متطلبات **Assignment 2** لطلاب كلية الحاسبات والذكاء الاصطناعي بجامعة القاهرة، مع مراعاة تفاصيل مشروعك "ChefMaster".

---

# ChefMaster – Laravel MVC Migration
### IS333 Web-Based Information Systems | Assignment 2
**Faculty of Computers and Artificial Intelligence – Cairo University**

## 📌 Project Overview
This project is a migration of the **ChefMaster** Single-Page Application (SPA) originally built in Assignment 1 into a full **Laravel MVC** architecture. It features a robust backend for recipe management, third-party API integration with Spoonacular, and automated testing, all structured according to modern PHP conventions.

## 🚀 Key Requirements Fulfilled
*   **MVC Architecture:** Recreated all features from Assignment 1 using Laravel Models, Controllers, and Blade Views.
*   **Database Management:** Migrated raw SQL schemas into **Laravel Migrations** using **SQLite** for seamless submission and portability.
*   **Validation:** Implementation of dual-layer validation:
    *   **Server-side:** Utilizing Laravel's built-in validation rules in Controllers.
    *   **Client-side:** Retaining original JavaScript validation logic.
*   **Blade Templating:** Created a Master Layout (`app.blade.php`) with reusable header and footer components.
*   **Third-Party API:** Integrated Spoonacular API with a quota-rotation system, handling errors gracefully and storing keys securely in the `.env` file.
*   **Automated Testing:** Includes at least three automated tests (Feature and Unit tests) to verify core functionality.

## 🛠️ Technical Stack
*   **Framework:** Laravel 11.x
*   **Language:** PHP 8.2+
*   **Database:** SQLite (configured for submission)
*   **Frontend:** Blade Templating Engine, CSS, and JavaScript
*   **Testing:** PHPUnit / Pest

## 📥 Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/ZeyadAzab/ChefMaster-Laravel.git
    cd ChefMaster-Laravel
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Configuration:**
    *   Create a `.env` file from the example: `cp .env.example .env`
    *   Generate the application key: `php artisan key:generate`
    *   Add your Spoonacular API keys in `.env`:
        ```env
        SPOONACULAR_API_KEYS=key1,key2,key3
        ```

4.  **Database Setup:**
    *   Ensure the SQLite file exists: `touch database/database.sqlite` (or `echo. > database/database.sqlite` on Windows).
    *   Run migrations to create the schema:
        ```bash
        php artisan migrate
        ```

5.  **Run the application:**
    ```bash
    php artisan serve
    ```
    Access the project at: `[http://127.0.0.1:8000](http://127.0.0.1:8000)`

## 🧪 Running Tests
To run the automated tests required by the assignment:
```bash
php artisan test
