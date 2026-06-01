# ERD (Entity Relationship Diagram)

This Entity Relationship Diagram shows the relationships between all entities in the Counselign system, including users, appointments, counselors, student information, notifications, and system resources. Split into multiple diagrams for A4 portrait printing.

## 1. Core Entities: Users, Appointments, and Counselors

```mermaid
erDiagram
    Users ||--o{ Appointments : "books"
    Users ||--|| Counselors : "is"
    Counselors ||--o{ CounselorAvailability : "has"
    Appointments ||--o{ FollowUpAppointments : "generates"
    Appointments ||--o{ AppointmentOptions : "has"
    Users ||--o{ OptimizedAppointments : "books"

    Users {
        int id PK
        string user_id UK "10-digit unique identifier"
        string email UK
        string password "hashed"
        string role "admin/counselor/student"
        string username
        string profile_picture "file path"
        datetime created_at
        datetime last_login
        datetime logout_time
        datetime last_activity
        datetime last_active_at
        datetime last_inactive_at
        boolean is_verified "email verification"
        string verification_token
    }

    Appointments {
        int id PK
        string student_id FK "references users.user_id"
        date preferred_date
        string preferred_time
        string method_type "Individual/Group"
        string consultation_type
        string counselor_preference "counselor_id or 'No preference'"
        text description
        text reason
        string status "pending/approved/rejected/rescheduled/completed/cancelled"
        string purpose "Counseling/Psycho-Social Support/Initial Interview"
        datetime created_at
        datetime updated_at
    }

    Counselors {
        int id PK
        string counselor_id FK "references users.user_id"
        string name
        string degree
        string email
        string contact_number
        text address
        string civil_status "Single/Married/etc."
        string sex "Male/Female"
        date birthdate
        datetime created_at
        datetime updated_at
    }

    CounselorAvailability {
        int id PK
        string counselor_id FK "references counselors.counselor_id"
        string available_days "Monday/Tuesday/etc."
        string time_scheduled "HH:MM-HH:MM or NULL"
        datetime created_at
    }

    FollowUpAppointments {
        int id PK
        int original_appointment_id FK
        string student_id FK
        string counselor_id FK
        date follow_up_date
        string status
        text notes
        datetime created_at
        datetime updated_at
    }

    AppointmentOptions {
        int id PK
        int appointment_id FK
        string option_type
        string option_value
        datetime created_at
    }

    OptimizedAppointments {
        int id PK
        string student_id FK
        string counselor_id FK
        date preferred_date
        string preferred_time
        string reason
        string status
        datetime created_at
        datetime updated_at
    }
```

## 2. Student Information Entities

```mermaid
erDiagram
    Users ||--|| StudentPersonalInfo : "has"
    Users ||--|| StudentAcademicInfo : "has"
    Users ||--|| StudentFamilyInfo : "has"
    Users ||--|| StudentAddressInfo : "has"
    Users ||--|| StudentOtherInfo : "has"
    Users ||--|| StudentResidenceInfo : "has"

    Users {
        int id PK
        string user_id UK "10-digit unique identifier"
        string email UK
        string password "hashed"
        string role "admin/counselor/student"
        string username
        string profile_picture "file path"
        datetime created_at
        datetime last_login
        datetime logout_time
        datetime last_activity
        datetime last_active_at
        datetime last_inactive_at
        boolean is_verified "email verification"
        string verification_token
    }

    StudentPersonalInfo {
        int id PK
        string student_id FK "references users.user_id"
        string last_name
        string first_name
        string middle_name
        date date_of_birth
        int age
        string sex
        string civil_status
        string contact_number "09XXXXXXXXX format"
        string fb_account_name
        string place_of_birth
        string religion
        datetime created_at
        datetime updated_at
    }

    StudentAcademicInfo {
        int id PK
        string student_id FK
        string student_number
        string course
        string year_level
        string section
        string academic_status
        decimal gwa
        datetime created_at
        datetime updated_at
    }

    StudentFamilyInfo {
        int id PK
        string student_id FK
        string father_name
        string father_occupation
        string mother_name
        string mother_occupation
        string guardian_name
        string guardian_relationship
        string guardian_contact
        int number_of_siblings
        datetime created_at
        datetime updated_at
    }

    StudentAddressInfo {
        int id PK
        string student_id FK
        string permanent_address
        string present_address
        string zip_code
        datetime created_at
        datetime updated_at
    }

    StudentOtherInfo {
        int id PK
        string student_id FK
        string hobbies
        string skills
        string strengths
        string weaknesses
        string goals
        datetime created_at
        datetime updated_at
    }

    StudentResidenceInfo {
        int id PK
        string student_id FK
        string residence_type "Dorm/Boarding House/Home"
        string landlord_name
        string landlord_contact
        decimal monthly_rent
        datetime created_at
        datetime updated_at
    }
```

## 3. Student Activities and Feedback Entities

```mermaid
erDiagram
    Users ||--o{ StudentAwards : "has"
    Users ||--o{ StudentGCSActivities : "has"
    Users ||--o{ StudentServicesAvailed : "has"
    Users ||--o{ StudentServicesNeeded : "has"
    Users ||--o{ StudentFeedbackAnalytics : "has"

    Users {
        int id PK
        string user_id UK "10-digit unique identifier"
        string email UK
        string password "hashed"
        string role "admin/counselor/student"
        string username
        string profile_picture "file path"
        datetime created_at
        datetime last_login
        datetime logout_time
        datetime last_activity
        datetime last_active_at
        datetime last_inactive_at
        boolean is_verified "email verification"
        string verification_token
    }

    StudentAwards {
        int id PK
        string student_id FK
        string award_name
        string award_type
        string awarding_body
        date date_received
        datetime created_at
        datetime updated_at
    }

    StudentGCSActivities {
        int id PK
        string student_id FK
        string activity_name
        string activity_type
        date date_participated
        string role
        datetime created_at
        datetime updated_at
    }

    StudentServicesAvailed {
        int id PK
        string student_id FK
        string service_name
        string service_type
        date date_availed
        string outcome
        datetime created_at
        datetime updated_at
    }

    StudentServicesNeeded {
        int id PK
        string student_id FK
        string service_name
        string service_type
        string priority
        datetime created_at
        datetime updated_at
    }

    StudentFeedbackAnalytics {
        int id PK
        string student_id FK
        int appointment_id FK
        int rating "1-5"
        text feedback_text
        json sentiment_analysis
        datetime created_at
        datetime updated_at
    }
```

## 4. Communications and Notifications Entities

```mermaid
erDiagram
    Appointments ||--o{ Notifications : "triggers"
    Users ||--o{ Notifications : "receives"
    Users ||--o{ Announcements : "receives"
    Users ||--o{ Messages : "sends/receives"
    Users ||--o{ NotificationReads : "marks as read"

    Users {
        int id PK
        string user_id UK "10-digit unique identifier"
        string email UK
        string password "hashed"
        string role "admin/counselor/student"
        string username
        string profile_picture "file path"
        datetime created_at
        datetime last_login
        datetime logout_time
        datetime last_activity
        datetime last_active_at
        datetime last_inactive_at
        boolean is_verified "email verification"
        string verification_token
    }

    Appointments {
        int id PK
        string student_id FK "references users.user_id"
        date preferred_date
        string preferred_time
        string method_type "Individual/Group"
        string consultation_type
        string counselor_preference "counselor_id or 'No preference'"
        text description
        text reason
        string status "pending/approved/rejected/rescheduled/completed/cancelled"
        string purpose "Counseling/Psycho-Social Support/Initial Interview"
        datetime created_at
        datetime updated_at
    }

    Notifications {
        int id PK
        string user_id FK
        string type "appointment/event/announcement/message"
        string title
        text message
        int related_id "references other table IDs"
        boolean is_read
        datetime event_date
        datetime appointment_date
        datetime created_at
    }

    NotificationReads {
        int id PK
        string user_id FK
        string notification_type "event/announcement"
        int related_id
        datetime read_at
    }

    Announcements {
        int id PK
        string title
        text content
        datetime created_at
    }

    Messages {
        int id PK
        string message_id "unique identifier"
        string sender_id FK
        string receiver_id FK
        text message_text
        datetime created_at
    }
```

## 5. Resources and Events Entities

```mermaid
erDiagram
    Users ||--o{ Resources : "accesses"
    Users ||--o{ DailyQuotes : "views"
    Users ||--o{ Events : "attends"

    Users {
        int id PK
        string user_id UK "10-digit unique identifier"
        string email UK
        string password "hashed"
        string role "admin/counselor/student"
        string username
        string profile_picture "file path"
        datetime created_at
        datetime last_login
        datetime logout_time
        datetime last_activity
        datetime last_active_at
        datetime last_inactive_at
        boolean is_verified "email verification"
        string verification_token
    }

    DailyQuotes {
        int id PK
        text quote_text
        string author_name
        string category "Inspirational/Motivational/etc."
        string source
        string submitted_by_id FK
        string submitted_by_name
        string submitted_by_role
        string status "pending/approved/rejected"
        int moderated_by FK
        datetime moderated_at
        text rejection_reason
        int times_displayed
        date last_displayed_date
        datetime created_at
        datetime updated_at
    }

    Resources {
        int id PK
        string title
        text description
        string resource_type "file/link"
        string file_name
        string file_path
        string file_type
        int file_size
        string external_url
        string category
        text tags
        string uploaded_by FK
        string visibility "all/students/counselors"
        boolean is_active
        int view_count
        int download_count
        datetime created_at
        datetime updated_at
    }

    Events {
        int id PK
        string title
        date date
        string time
        string location
        text description
        datetime created_at
    }
```