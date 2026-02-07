# ProSkill Academy: Database Setup Guide 🗄️

This guide explains how to build the database from scratch for the ProSkill Academy project. It covers configuration, migration files, and the commands needed to create the tables.

---

## 1. Prerequisites (Environment Setup)

Before creating the database, ensure your environment is configured to connect to MySQL.

### Step 1.1: Start MySQL
1.  Open **XAMPP Control Panel**.
2.  Start the **Apache** and **MySQL** modules.
3.  Click "Admin" next to MySQL to open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`).

### Step 1.2: Create a Database
1.  In phpMyAdmin, click **New**.
2.  Enter the database name: `school_online_project` (or whatever naming convention you prefer).
3.  Click **Create**.

### Step 1.3: Configure `.env`
Laravel needs to know how to connect to this database.
1.  Open the file named `.env` in the root of your project.
2.  Find the `DB_` section and update it:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_online_project  <-- Name from Step 1.2
DB_USERNAME=root                   <-- Default XAMPP user
DB_PASSWORD=                       <-- Default XAMPP password is empty
```

---

## 2. Understanding Database Migrations

In Laravel, we don't write raw SQL (like `CREATE TABLE`). Instead, we use **Migrations**. These are PHP files that define the structure of your database. They live in `database/migrations/`.

### Key Migration Files in Project:

1.  **Users Table** (`0001_01_01_000000_create_users_table.php`)
    *   Creates the `users` table (id, name, email, password, role).
    *   The `role` column distinguishes between `admin`, `tutor`, and `student`.

2.  **Courses** (`...create_courses_table.php`)
    *   Creates `courses` table.
    *   Links to `users` table via `tutor_id`.

3.  **Contents** (`...create_course_contents_table.php`)
    *   Creates `course_contents` (videos/documents).
    *   Links to `courses` via `course_id`.

4.  **Enrollments** (`...create_enrollments_table.php`)
    *   Creates `enrollments` table.
    *   Links `users` (students) to `courses`.
    *   Tracks `payment_status` ('paid', 'pending').

5.  **Quizzes & Questions** (`...create_quizzes_table.php`, etc.)
    *   Creates tables for the assessment engine: `quizzes`, `questions`, `options`, `quiz_attempts`.

---

## 3. Creating the Database (Running Migrations)

Once configured, run the following commands in your terminal (VS Code Terminal or CMD).

### Command 1: Install Dependencies (If not done)
Ensure you have the necessary PHP packages.
```bash
composer install
```

### Command 2: Run Migrations (The Main Step)
This command reads all files in `database/migrations` and creates the tables in MySQL.
```bash
php artisan migrate
```

**Output you should see:**
```text
INFO  Running migrations.
2026_02_01_000000_create_users_table .................... 12ms DONE
2026_02_01_200002_create_courses_table .................. 10ms DONE
...
```

### Command 3: Resetting the Database (Optional)
If you made a mistake or want to start fresh (WARNING: Deletes all data):
```bash
php artisan migrate:fresh
```

### Command 4: Seeding Data (Optional)
If you have a `DatabaseSeeder.php` file to add dummy data (test users, courses):
```bash
php artisan db:seed
```

To do both (Reset + Seed):
```bash
php artisan migrate:fresh --seed
```

---

## 4. Verification

To verify that the database was created correctly:

1.  Go back to **phpMyAdmin**.
2.  Click on your database (`school_online_project`).
3.  You should see a list of tables:
    *   `users`
    *   `courses`
    *   `enrollments`
    *   `quizzes`
    *   `migrations` (Laravel uses this to track what it ran)
    *   ...and others.

## 5. Summary of Commands

| Action | Command |
| :--- | :--- |
| **Create Tables** | `php artisan migrate` |
| **Delete & Re-create** | `php artisan migrate:fresh` |
| **Add Dummy Data** | `php artisan db:seed` |
| **Make New Migration** | `php artisan make:migration create_table_name` |

---

## Example Code: How a Migration Looks
If you want to create a new table, for example, for "Assignments", you would run:
`php artisan make:migration create_assignments_table`

Then edit the file created in `database/migrations/`:
```php
public function up(): void
{
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->dateTime('due_date');
        $table->timestamps();
    });
}
```
And run `php artisan migrate` again.
