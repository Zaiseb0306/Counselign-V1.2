# System Design

## Overview

The Counselign system is a comprehensive counseling management platform built for educational institutions to facilitate student-counselor interactions through appointment scheduling and tracking.

## System Architecture

### High-Level Architecture

- **Frontend Layer**: HTML/PHP views with JavaScript for client-side interactions
- **Application Layer**: CodeIgniter framework handling business logic, API endpoints, and database operations
- **Database Layer**: MySQL database for data persistence

### Component Diagram

```mermaid
graph TD
    A[Client Browser] --> B[Apache/Nginx Web Server]
    B --> C[CodeIgniter Application]
    C --> D[MySQL Database]

    A --> B
    C -.->|HTTP Requests| B
    C -.->|SQL Queries| D
```

## Database Schema

Based on the models, the key tables are:

### Users Table
- id (PK)
- user_id (unique, 10 chars)
- email
- password
- role (admin, counselor, student)
- verification_token
- is_verified
- username
- profile_picture
- created_at, last_login, etc.

### Appointments Table
- id (PK)
- student_id (FK to users.user_id)
- preferred_date
- preferred_time
- method_type
- consultation_type
- counselor_preference
- description, reason
- status (pending, approved, rejected, etc.)
- purpose (Counseling, Psycho-Social Support, Initial Interview)
- created_at, updated_at

### Counselors Table
- id (PK)
- counselor_id (FK to users.user_id)
- name, degree, email, contact_number, address
- civil_status, sex, birthdate

### Student Information Tables
- student_personal_info
- student_academic_info
- student_family_info
- student_address_info
- student_other_info
- student_awards
- student_gcs_activities
- student_services_availed
- student_services_needed
- student_residence_info
- student_feedback_analytics

### Other Tables
- counselor_availability
- notifications
- announcements
- quotes
- resources

## User Roles and Permissions

1. **Student**
   - View own profile
   - Book appointments
   - View appointment history
   - Provide feedback

2. **Counselor**
   - View assigned appointments
   - Update appointment status
   - View student profiles (with permission)
   - Manage availability

3. **Admin**
   - Full system access
   - User management
   - View all appointments and statistics
   - Manage announcements and resources

## Key Features

- Appointment scheduling and management
- User authentication and verification
- Role-based access control
- Counselor availability management
- Student profile management
- Appointment status tracking
- Dashboard analytics
- Follow-up session management
- History reports

## System Flowchart

```mermaid
flowchart TD
    A[User Accesses System] --> B[Login Page]
    B --> C{Authentication}
    C -->|Success| D{Determine Role}
    C -->|Failure| B

    D -->|Student| E[Student Dashboard]
    D -->|Counselor| F[Counselor Dashboard]
    D -->|Admin| G[Admin Dashboard]

    E --> H[View Profile]
    E --> I[Book Appointment]
    E --> J[View Appointment History]
    E --> K[Provide Feedback]

    F --> L[View Assigned Appointments]
    F --> M[Update Appointment Status]
    F --> N[Manage Availability]
    F --> O[View Student Profiles]

    G --> P[User Management]
    G --> Q[View All Appointments]
    G --> R[View Statistics]
    G --> S[Manage Announcements]

    H --> E
    I --> E
    J --> E
    K --> E

    L --> F
    M --> F
    N --> F
    O --> F

    P --> G
    Q --> G
    R --> G
    S --> G
```

## ERD (Entity Relationship Diagram)

```mermaid
erDiagram
    Users ||--o{ Appointments : "books"
    Users ||--|| Counselors : "is"
    Users ||--|| StudentPersonalInfo : "has"
    Users ||--|| StudentAcademicInfo : "has"
    Users ||--|| StudentFamilyInfo : "has"
    Users ||--|| StudentAddressInfo : "has"
    Users ||--|| StudentOtherInfo : "has"
    Users ||--o{ StudentAwards : "has"
    Users ||--o{ StudentGCSActivities : "has"
    Users ||--o{ StudentServicesAvailed : "has"
    Users ||--o{ StudentServicesNeeded : "has"
    Users ||--|| StudentResidenceInfo : "has"
    Users ||--o{ StudentFeedbackAnalytics : "has"
    Counselors ||--o{ CounselorAvailability : "has"
    Appointments ||--|| Notifications : "triggers"
    Users ||--o{ Announcements : "receives"
    Users ||--o{ Resources : "accesses"
    Users ||--o{ Quotes : "views"

    Users {
        int id PK
        string user_id UK
        string email UK
        string password
        string role
        string username
        string profile_picture
        datetime created_at
        datetime last_login
    }

    Appointments {
        int id PK
        string student_id FK
        date preferred_date
        string preferred_time
        string method_type
        string consultation_type
        string counselor_preference
        string description
        string status
        string purpose
        datetime created_at
        datetime updated_at
    }

    Counselors {
        int id PK
        string counselor_id FK
        string name
        string degree
        string email
        string contact_number
        string address
        string civil_status
        string sex
        date birthdate
    }

    StudentPersonalInfo {
        int id PK
        string student_id FK
        string last_name
        string first_name
        string middle_name
        date date_of_birth
        int age
        string sex
        string civil_status
        string contact_number
        string fb_account_name
        string place_of_birth
        string religion
    }
```

## Database Schema

(Details above in Database Schema section)

## Security Considerations

- Password hashing
- Email verification
- Role-based access
- Input validation
- SQL injection prevention via CodeIgniter ORM
- CSRF protection via CodeIgniter security features

## Technologies Used

- Backend: PHP 8+ (CodeIgniter 4)
- Database: MySQL
- Frontend: HTML5, CSS3, JavaScript
- Web Server: Apache/XAMPP

## Deployment

- Local development: XAMPP
- Production: Standard LAMP stack