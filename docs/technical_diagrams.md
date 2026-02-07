# ProSkill Academy - Technical Diagrams
## System Architecture Documentation

---

## 1. Component Diagram

This diagram reflects the actual modular structure of the **Laravel 12 + Livewire** application, organized by namespaces (Admin, Tutor, Student) and key services.

```mermaid
componentDiagram
    package "Frontend Module" {
        [Student Interface]
        [Tutor Interface]
        [Web Browser]
        [Mobile Interface]
    }

    package "Authentication Module" {
        [Login Component]
        [Registration Component]
        [Logout Component]
    }

    package "Backend Service" {
        package "User Management Package" {
            [Session Manager]
            [User Profile]
        }

        package "Course Management Package" {
            [Course Creator]
            [Course Viewer]
            [Enrollment System]
        }

        package "Payment System" {
            [Payment Processor]
            [Subscription Manager]
        }

        package "Tutor Services Package" {
            [Session Booking]
            [Chat Component]
            [Feedback Mechanism]
        }

        package "Admin Services Package" {
            [Instructor Management]
            [Report Generator]
        }
    }

    package "Database System" {
        [User Records]
        [Course Records]
        [Session Logs]
        [Payment Records]
    }

    %% Connections
    [Web Browser] --> [Frontend Module]
    [Frontend Module] --> [Authentication Module] : Login/Register

    [Authentication Module] --> [User Management Package] : Identity Verified
    [User Management Package] --> [Session Manager] : Manage Session

    [Student Interface] --> [Course Management Package] : Browse/Enroll
    [Course Management Package] --> [Payment System] : Initiate Payment
    [Payment System] --> [Course Management Package] : Confirm Access

    [Student Interface] --> [Tutor Services Package] : Book Session
    [Tutor Interface] --> [Tutor Services Package] : Manage Schedule
    [Chat Component] --> [Tutor Services Package] : Real-time Comm

    [Admin Services Package] --> [Course Management Package] : Quality Control
    [Admin Services Package] --> [Instructor Management] : Oversight

    [Backend Service] --> [Database System] : Store/Retrieve Data
```

### Key Modules Description

1.  **Localization Middleware**: Routes are prefixed with `{locale}` (ar/en) using `mcamara/laravel-localization`.
2.  **Student Module**: Handles course browsing, watching content (videos/docs), taking quizzes, and requesting certificates.
3.  **Tutor Module**: Allows creating courses, managing content (lessons), building quizzes, and updating tutor profile/CV.
4.  **Admin Module**: Focused on verification—approving pending courses and verifying new tutor registrations.
5.  **Livewire Components**: Handle dynamic, real-time features like Chat, Reviews, and Notifications without full page reloads.

---

## 2. Deployment Diagram

This diagram represents the current **local development environment** based on the XAMPP setup.

```mermaid
graph TB
    subgraph "Developer Workstation (Windows)"
        
        subgraph "Client Tool"
            Browser["🌐 Web Browser\n(Chrome/Edge)"]
        end

        subgraph "XAMPP Server Environment"
            Apache["🖥️ Apache Web Server\n(Port 80/443)"]
            MySQL["🗄️ MySQL Database\n(Port 3306)"]
            PHP["🐘 PHP 8.2+ Interpreter"]
        end

        subgraph "Development Tools"
            Artisan["⚙️ Artisan Serve\n(PHP Built-in Server:8000)"]
            Vite["⚡ Vite Dev Server\n(Node.js:5173)\nHot Module Replacement"]
        end

        subgraph "File System"
            Code["📂 Source Code\n(Laravel/Livewire)"]
            Assets["📂 Storage/Public\n(Uploads)"]
        end
    end

    %% Connections
    Browser -->|HTTP Requests| Artisan
    Browser -->|Asset Requests| Vite
    Browser -->|Direct Access| Apache

    Artisan -->|Executes| PHP
    PHP -->|Queries| MySQL
    PHP -->|Reads/Writes| Code
    PHP -->|Stores| Assets

    %% Styling
    classDef dev fill:#F5F5F5,stroke:#333
    classDef server fill:#FFF3E0,stroke:#E65100
    classDef tool fill:#E1F5FE,stroke:#0277BD

    class Browser dev
    class Apache,MySQL,PHP server
    class Artisan,Vite tool
```

---

## 3. Class Diagram

This diagram reflects the actual **Eloquent Models** and their relationships found in `App\Models`.

```mermaid
classDiagram
    %% User & Profile
    class User {
        +id: int
        +name: string
        +email: string
        +role: enum(admin,tutor,student)
        +isAdmin()
        +isTutor()
        +isStudent()
    }

    class TutorDetail {
        +id: int
        +user_id: int
        +bio: text
        +specialization: string
        +cv_path: string
        +status: enum(pending,approved,rejected)
    }

    %% Course System
    class Course {
        +id: int
        +tutor_id: int
        +title: string
        +price: decimal
        +status: enum(pending,approved,rejected)
        +getAverageRating()
        +getStudentsCount()
    }

    class CourseContent {
        +id: int
        +course_id: int
        +title: string
        +type: enum(video,document)
        +file_path: string
        +order: int
    }

    class ContentProgress {
        +id: int
        +user_id: int
        +course_content_id: int
        +completed: boolean
        +completed_at: datetime
    }

    class Enrollment {
        +id: int
        +user_id: int
        +course_id: int
        +payment_status: enum(paid,unpaid)
        +enrolled_at: datetime
    }

    class Review {
        +id: int
        +user_id: int
        +course_id: int
        +rating: int
        +comment: text
    }

    %% Assessment System
    class Quiz {
        +id: int
        +course_id: int
        +title: string
        +max_attempts: int
    }

    class Question {
        +id: int
        +quiz_id: int
        +question_text: string
        +type: string
    }

    class Option {
        +id: int
        +question_id: int
        +option_text: string
        +is_correct: boolean
    }

    class QuizAttempt {
        +id: int
        +quiz_id: int
        +user_id: int
        +score: float
        +passed: boolean
    }

    class CourseCertificate {
        +id: int
        +user_id: int
        +course_id: int
        +certificate_code: string
        +issued_at: datetime
        +status: enum(pending,issued,rejected)
    }

    %% Messaging
    class Message {
        +id: int
        +sender_id: int
        +receiver_id: int
        +body: text
        +is_read: boolean
    }

    %% Relationships
    User "1" -- "0..1" TutorDetail : has
    User "1" -- "*" Course : creates (as tutor)
    User "1" -- "*" Enrollment : has (as student)
    User "1" -- "*" Message : sends/receives
    User "1" -- "*" ContentProgress : tracks
    User "1" -- "*" CourseCertificate : earns

    Course "1" -- "*" CourseContent : contains
    Course "1" -- "*" Enrollment : has
    Course "1" -- "*" Review : receives
    Course "1" -- "*" Quiz : includes

    Quiz "1" -- "*" Question : has
    Question "1" -- "*" Option : has
    Quiz "1" -- "*" QuizAttempt : tracks

    Enrollment "1" -- "1" User : belongs to
    Enrollment "1" -- "1" Course : belongs to
```

---

## 4. Entity Relationship Diagram (ERD)

This diagram visualizes the exact database schema based on the **migration files**.

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email UK
        string password
        enum role "student, tutor, admin"
        timestamp email_verified_at
    }

    tutor_details {
        bigint id PK
        bigint user_id FK
        text bio
        string specialization
        string cv_path
        string status "pending, approved"
    }

    courses {
        bigint id PK
        bigint tutor_id FK
        string title
        text description
        decimal price
        string thumbnail
        string status "pending, approved"
    }

    course_contents {
        bigint id PK
        bigint course_id FK
        string title
        string type "video, document"
        string file_path
        int order
    }

    enrollments {
        bigint id PK
        bigint user_id FK
        bigint course_id FK
        string payment_status "paid, unpaid, pending"
        timestamp created_at
    }

    reviews {
        bigint id PK
        bigint user_id FK
        bigint course_id FK
        int rating
        text comment
    }

    quizzes {
        bigint id PK
        bigint course_id FK
        string title
        string description
        int max_attempts
    }

    questions {
        bigint id PK
        bigint quiz_id FK
        text question_text
        string type "multiple_choice"
    }

    options {
        bigint id PK
        bigint question_id FK
        string option_text
        boolean is_correct
    }

    quiz_attempts {
        bigint id PK
        bigint quiz_id FK
        bigint user_id FK
        decimal score
        boolean passed
    }

    course_certificates {
        bigint id PK
        bigint user_id FK
        bigint course_id FK
        string certificate_code UK
        string status "pending, issued"
    }

    messages {
        bigint id PK
        bigint sender_id FK
        bigint receiver_id FK
        text body
        boolean is_read
    }

    %% Relationships
    users ||--o| tutor_details : has
    users ||--o{ courses : creates
    users ||--o{ enrollments : makes
    users ||--o{ reviews : writes
    users ||--o{ quiz_attempts : takes
    users ||--o{ course_certificates : owns
    users ||--o{ messages : sends

    courses ||--o{ course_contents : contains
    courses ||--o{ enrollments : has
    courses ||--o{ reviews : receives
    courses ||--o{ quizzes : includes
    courses ||--o{ course_certificates : generates

    quizzes ||--o{ questions : contains
    questions ||--o{ options : has
```

---

## 5. Sequence Diagrams

### 5.1. Course Content Watching & Progress

```mermaid
sequenceDiagram
    actor Student
    participant Browser
    participant Controller as Student\CourseController
    participant DB as Database
    
    Student->>Browser: Opens Course Player
    Browser->>Controller: GET /student/courses/{id}/watch/{contentId}
    Controller->>DB: Fetch Content & Progress
    DB-->>Controller: Return Content Data
    Controller-->>Browser: Render Player View (Video/Doc)

    Note over Student, Browser: Student finishes video
    
    Browser->>Controller: POST /student/courses/.../complete
    Controller->>DB: Create/Update ContentProgress
    DB-->>Controller: Saved
    Controller->>DB: Check Course Completion %
    
    alt Course 100% Completed
        Controller-->>Browser: Update Progress UI & Show "Request Certificate"
    else In Progress
        Controller-->>Browser: Update Progress UI
    end
```

### 5.2. Quiz Attempt

```mermaid
sequenceDiagram
    actor Student
    participant Browser
    participant QuizCtrl as App\Http\Controllers\QuizController
    participant DB as Database

    Student->>Browser: Start Quiz
    Browser->>QuizCtrl: GET /student/quizzes/{id}
    QuizCtrl->>DB: Check Remaining Attempts
    
    alt Attempts Exceeded
        QuizCtrl-->>Browser: Error "Max attempts reached"
    else Attempts Available
        QuizCtrl-->>Browser: Show Quiz Form
    end

    Student->>Browser: Submit Answers
    Browser->>QuizCtrl: POST /student/quizzes/{id}
    QuizCtrl->>QuizCtrl: Calculate Score
    QuizCtrl->>DB: Save QuizAttempt
    
    auth Passed
        QuizCtrl->>DB: Mark as Passed
    else Failed
        QuizCtrl->>DB: Mark as Failed
    end

    QuizCtrl-->>Browser: Redirect to Results Page
```
