# Teacher Portal

## Overview

The Teacher Portal is a web application built with Laravel, Inertia.js, and Vue.js. It provides an interface for teachers to manage their students, Subjects and Marks.

## Features

- User authentication and management with Laravel Breeze
- Dynamic data handling with Inertia.js and Vue.js
- CRUD operations for students
- Real-time data updates and form handling

## Prerequisites

Ensure you have the following installed:

- [PHP](https://www.php.net/downloads) (version 8.0 or higher)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/en/download/) (version 16 or higher)
- [NPM](https://www.npmjs.com/get-npm) (comes with Node.js)
- [MySQL](https://dev.mysql.com/downloads/) or [PostgreSQL](https://www.postgresql.org/download/) (depending on your setup)

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository:**

   ```bash
   git clone https://github.com/Israr22/teacher-portal.git
   cd teacher-portal

2. **Install PHP dependencies**

    ```bash
    composer install

3. **Set up the environment file**
Update the .env file with your database configuration and other settings
Open the .env file and set the following values:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=teacher_portal
    DB_USERNAME=root
    DB_PASSWORD=
    
4. **If you are using PostgreSQL, just change:**
   ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=teacher_portal
    DB_USERNAME=root
    DB_PASSWORD=
   
5. **Run migrations and seed the database**

    ```bash
    php artisan migrate --seed
    ```

   Now you will see the screen like<br>
   ![Teminal]([images/Screenshot 2024-08-09 002927.png](https://github.com/Israr22/teacher-portal/blob/main/images/Screenshot%202024-08-09%20002927.png))<br>
   Please type "yes" and it will create a database in your database server with name wich you provide in .env<br>
   Now by using Seeder I already insert 5 records of students and one record of teacher

7. **Install JavaScript dependencies**

    ```bash
    npm install

8. **Build the assets for development**

    ```bash
    npm run dev
    
9. **Or For production builds, use:**
    ```bash
    npm run build

10. **Serve the application**
    ```bash
    php artisan serve

11. **By default, the application will be accessible at**
    [http://localhost:8000](http://localhost:8000)<br>

    Open this localhost and put these credentials<br>
    Email:-
    ```bash
    israr@example.com
    ```

    and <br>
    password:- 
    ```bash
    password
    ```

    put these credentials and login
    
13. **Additionally, you can configure SMTP credentials for forgot password or reset password functionality. To do this, add your SMTP settings to the `.env` file:**
    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.your-email-provider.com
    MAIL_PORT=587
    MAIL_USERNAME=your-email@example.com
    MAIL_PASSWORD=your-email-password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=your-email@example.com
    MAIL_FROM_NAME="${APP_NAME}"

