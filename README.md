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
        <li><a href="#teacher-routes">Teacher Routes</a></li>
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

![image](https://img.shields.io/badge/Symfony-000000?style=for-the-badge&logo=Symfony&logoColor=white) ![image](https://img.shields.io/badge/Swagger-85EA2D?style=for-the-badge&logo=Swagger&logoColor=white) ![image](https://img.shields.io/badge/JWT-000000?style=for-the-badge&logo=JSON%20web%20tokens&logoColor=white) ![image](https://img.shields.io/badge/rabbitmq-%23FF6600.svg?&style=for-the-badge&logo=rabbitmq&logoColor=white) ![image](https://img.shields.io/badge/PostgreSQL-green?style=for-the-badge)

This is a REST API application for a student portal. Students can view subjects and group lessons, submit and review homework assignments, check grades, manage personal data, be part of a group, and receive event notifications. Teachers can manage grades, homework assignments, and student submissions, as well as view upcoming lessons and subjects. User authentication is implemented using JWT. The admin panel for managers and administrators is built with Sonata Admin, allowing them to manage lessons, subjects, classrooms, students, teachers, and users. The project includes data validation and both unit and functional tests to ensure system reliability and correctness. A cron job is implemented to send birthday greetings to users. DataFixtures are used to initialize and test the database. An audit log tracks important changes for transparency, and Rector is used for automated code refactoring and upgrades.
