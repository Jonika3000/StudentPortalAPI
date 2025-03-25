<a id="readme-top"></a>
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#student-portal-api">About The Project</a>
    </li>
    <li>
      <a href="#technology-stack">Technology Stack</a>
    </li>
    <li>
      <a href="#features">Features</a>
    </li>
    <li>
      <a href="#api-endpoints">API Endpoints</a>
      <ul>
        <li><a href="#authentication-routes">Authentication Routes</a></li>
        <li><a href="#user-routes">User Routes</a></li>
        <li><a href="#grade-routes">Grade Routes</a></li>
        <li><a href="#homework-routes">Homework Routes</a></li>
        <li><a href="#student-submission-routes">Student Submission Routes</a></li>
        <li><a href="#lesson-routes">Lesson Routes</a></li>
        <li><a href="#classroom-routes">Classroom Routes</a></li>
      </ul>
    </li>
    <li>
      <a href="#installation">Installation</a>
    </li>
    <li>
      <a href="#running-tests">Running Tests</a>
    </li>
    <li>
      <a href="#usage">Usage</a>
    </li>
  </ol>
</details>

# STUDENT PORTAL API

![image](https://img.shields.io/badge/Symfony-000000?style=for-the-badge&logo=Symfony&logoColor=white) ![image](https://img.shields.io/badge/Swagger-85EA2D?style=for-the-badge&logo=Swagger&logoColor=white) ![image](https://img.shields.io/badge/JWT-000000?style=for-the-badge&logo=JSON%20web%20tokens&logoColor=white) ![image](https://img.shields.io/badge/PostgreSQL-green?style=for-the-badge)

This is a REST API application for a student portal. Students can view subjects and group lessons, submit and review homework assignments, check grades, manage personal data, be part of a group, and receive event notifications. Teachers can manage grades, homework assignments, and student submissions, as well as view upcoming lessons and subjects. User authentication is implemented using JWT. The admin panel for managers and administrators is built with Sonata Admin, allowing them to manage lessons, subjects, classrooms, students, teachers, and users. The project includes data validation and both unit and functional tests to ensure system reliability and correctness. A cron job is implemented to send birthday greetings to users. DataFixtures are used to initialize and test the database. An audit log tracks important changes for transparency, and Rector is used for automated code refactoring and upgrades. API documentation is provided using Swagger, allowing for easy exploration and testing of endpoints.

## Technology Stack

* Symfony Framework 7.1
* PHP 8.3
* JWT 3.1
* Rector 2.0
* Swagger 5.0.1
* Sonata Admin Bundle 4.3
* Swagger 8.6
* Profiler 7.1
* Monolog 3.10

## Features

- **User Authentication**: Secure user authentication using LexikJWTAuthenticationBundle.
- **Submissions Management**: Users can create, view, update, and delete student submissions.
- **Admin Panel**: Uses Sonata Admin for managing entities like users and submissions.
- **Middleware**: Custom security mechanisms ensure proper access control, including role-based authorization.
- **Policy (Security Voters)**: Ensures that only authorized users can manage specific data.
- **Database Transactions**: Ensures data integrity during operations.
- **Cron Jobs**: Automates scheduled tasks like user birthday.
- **Email Notifications**: Sends email alerts for key events (e.g., grade updates).
- **Logging and Debugging**: Uses Monolog for logging and Symfony Web Profiler for debugging.
- **API Documentation**: Integrated NelmioApiDocBundle for Swagger-based API documentation.
- **Fixtures**: Provides database seeding for initial setup and testing.
- **Database Migrations**: Uses Doctrine Migrations to manage database schema changes.
- **Services**: Implements a clean architecture approach using service classes.
- **Unit Tests**: Comprehensive unit and integration testing with PHPUnit.
- **Code Modernization (Rector)**: Uses Rector to automate PHP upgrades and enforce coding standards.

## API Endpoints

### Authentication Routes

- `POST /api/login`: Authenticate a user and return a JWT token.
- `POST /api/register`: Register a new user with details like name, email, password, gender, and avatar.

### User Routes

- `PUT /api/user/update`: Edit user profile information, including address, phone number, and avatar. Only authorized.
- `POST /api/user/password-reset/request`: Request a password reset by providing an email. Only authorized.
- `POST /api/user/password-reset`: Reset password using a reset token and new password. Only authorized.

### Student Submission Routes

- `POST /api/student/submission`: Create a student submission with a comment and attached files. Student only.
- `PUT /api/student/submission/{id}`: Update a student submission with a new comment and additional files. Author/Student only.
- `GET /api/student/submission/{id}`: Retrieve a specific student submission by ID. Authorized/Related only.
- `DELETE /api/student/submission/{id}`: Delete a student submission (restricted to the author or an admin). Author/Student only.

### Homework Routes

- `POST /api/homework`: Create a new homework assignment with a description, lesson ID, and deadline. Teacher only.
- `PUT /api/homework/{id}`: Update homework details, including the deadline. Author/Teacher only.
- `GET /api/homework/{id}`: Retrieve a specific homework assignment. Authorized/Related only.

### Grade Routes

- `POST /api/grade`: Assign a grade to a student submission. Author/Teacher only.
- `PUT /api/grade/{id}`: Update an existing grade with optional comments. Author/Teacher only.
- `DELETE /api/grade/{id}`:  Delete a grade. Author/Teacher only.

### Student Routes

- `GET /api/student/me`: Retrieves information about the currently authenticated student. Student only.
- `GET /api/student/{id}`: Get student details by ID. Teacher only.

### Classroom Routes

- `GET /api/classrooms/{id}`: Retrieve classroom details, including students and lessons. Teacher only.

### Lesson Routes

- `GET /api/lesson/{id}`: Retrieve lesson details, including subject. Authorized only.
- `GET /api/lessons`: Get a list of all lessons. Authorized only.

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/Jonika3000/StudentPortalAPI.git
    cd StudentPortalAPI
    ```

2. Copy the `.env.example` file to `.env` and update the environment variables as needed:

    ```bash
    cp .env .env.local
    ```

3. Install dependencies:

    ```bash
    composer install
    ```

4. Set up the Database:

    ```bash
    symfony console doctrine:database:create
    ``` 

5. Run Migrations:

    ```bash
    symfony console doctrine:migrations:migrate
    ```

6. Loading Fixtures in Symfony:

    ```bash
    symfony console doctrine:fixtures:load
    ```

6. Generate the SSL keys for JWT:

    ```bash
    php bin/console lexik:jwt:generate-keypair
    ```

8. Start the development server:

    ```bash
    symfony server:start
    ```

## Running Tests

Set up test environment (.env.test) and run unit tests:

```bash
php bin/phpunit
```

## Usage

This project is primarily an REST API with a Sonata Admin Panel:

- **Swagger**: Visit `/api/doc` to access the Swagger UI. This interface provides interactive API documentation, allowing you to test and explore the available API endpoints.

- **Sonata Admin**: Use the admin panel at `/admin` to manage classrooms, lessons, students, subjects, teachers, users, and other administrative tasks. Sonata Admin provides a user-friendly interface for backend management.

- **Profiler**: To monitor and debug application activities, visit `/_profiler`. Profiler offers detailed insights into requests, exceptions, database queries, and more. (Dev only).

- **Logs**: Symfony logs detailed information about application activity, including errors, warnings, and debug messages. Logs can be found in the var/log/ directory.

