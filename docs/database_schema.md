# Database Schema

This document provides a comprehensive overview of all database tables in the Counselign counseling management system, including field definitions, data types, constraints, and relationships.

## Database ERD (Text-based)

### Entities and Attributes

#### Users
- id (INT, PK, AUTO_INCREMENT)
- user_id (VARCHAR(10), UK, NOT NULL) - 10-digit unique identifier
- email (VARCHAR(255), UK, NOT NULL)
- password (VARCHAR(255), NOT NULL) - hashed
- role (ENUM: 'admin', 'counselor', 'student', NOT NULL)
- username (VARCHAR(100))
- profile_picture (VARCHAR(255)) - file path
- verification_token (VARCHAR(6))
- is_verified (BOOLEAN, DEFAULT FALSE)
- created_at (DATETIME, NOT NULL)
- last_login (DATETIME)
- last_activity (DATETIME)
- last_active_at (DATETIME)
- last_inactive_at (DATETIME)

#### Appointments
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- preferred_date (DATE, NOT NULL)
- preferred_time (VARCHAR(50), NOT NULL)
- method_type (VARCHAR(50), NOT NULL)
- consultation_type (VARCHAR(50))
- counselor_preference (VARCHAR(100), NOT NULL)
- description (TEXT)
- reason (TEXT)
- status (ENUM: 'pending', 'approved', 'rejected', 'rescheduled', 'completed', 'cancelled', NOT NULL)
- purpose (ENUM: 'Counseling', 'Psycho-Social Support', 'Initial Interview', NOT NULL)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### Counselors
- id (INT, PK, AUTO_INCREMENT)
- counselor_id (VARCHAR(20), FK→users.user_id, UK, NOT NULL)
- name (VARCHAR(100), NOT NULL)
- degree (VARCHAR(100), NOT NULL)
- email (VARCHAR(100), UK, NOT NULL)
- contact_number (VARCHAR(20), NOT NULL)
- address (TEXT, NOT NULL)
- civil_status (VARCHAR(20))
- sex (VARCHAR(10))
- birthdate (DATE)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### CounselorAvailability
- id (INT, PK, AUTO_INCREMENT)
- counselor_id (VARCHAR(20), FK→counselors.counselor_id, NOT NULL)
- available_days (ENUM: 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', NOT NULL)
- time_scheduled (VARCHAR(255))
- created_at (DATETIME, NOT NULL)

#### StudentPersonalInfo
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- last_name (VARCHAR(100))
- first_name (VARCHAR(100))
- middle_name (VARCHAR(100))
- date_of_birth (DATE)
- age (INT)
- sex (VARCHAR(10))
- civil_status (VARCHAR(20))
- contact_number (VARCHAR(20)) - 09XXXXXXXXX format
- fb_account_name (VARCHAR(100))
- place_of_birth (VARCHAR(100))
- religion (VARCHAR(50))
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentAcademicInfo
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- student_number (VARCHAR(20))
- course (VARCHAR(100))
- year_level (VARCHAR(20))
- section (VARCHAR(20))
- academic_status (VARCHAR(50))
- gwa (DECIMAL(5,2))
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentFamilyInfo
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- father_name (VARCHAR(100))
- father_occupation (VARCHAR(100))
- mother_name (VARCHAR(100))
- mother_occupation (VARCHAR(100))
- guardian_name (VARCHAR(100))
- guardian_relationship (VARCHAR(50))
- guardian_contact (VARCHAR(20))
- number_of_siblings (INT)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentAddressInfo
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- permanent_address (TEXT)
- present_address (TEXT)
- zip_code (VARCHAR(10))
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentOtherInfo
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- hobbies (TEXT)
- skills (TEXT)
- strengths (TEXT)
- weaknesses (TEXT)
- goals (TEXT)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentAwards
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- award_name (VARCHAR(200))
- award_type (VARCHAR(100))
- awarding_body (VARCHAR(200))
- date_received (DATE)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentGCSActivities
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- activity_name (VARCHAR(200))
- activity_type (VARCHAR(100))
- date_participated (DATE)
- role (VARCHAR(100))
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentServicesAvailed
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- service_name (VARCHAR(200))
- service_type (VARCHAR(100))
- date_availed (DATE)
- outcome (TEXT)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentServicesNeeded
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- service_name (VARCHAR(200))
- service_type (VARCHAR(100))
- priority (VARCHAR(20))
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentResidenceInfo
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- residence_type (VARCHAR(50)) - 'Dorm', 'Boarding House', 'Home'
- landlord_name (VARCHAR(100))
- landlord_contact (VARCHAR(20))
- monthly_rent (DECIMAL(10,2))
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### StudentFeedbackAnalytics
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- appointment_id (INT, FK→appointments.id)
- rating (TINYINT) - 1-5
- feedback_text (TEXT)
- sentiment_analysis (JSON)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### Notifications
- id (INT, PK, AUTO_INCREMENT)
- user_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- type (VARCHAR(50), NOT NULL)
- title (VARCHAR(255), NOT NULL)
- message (TEXT, NOT NULL)
- related_id (INT)
- is_read (BOOLEAN, DEFAULT FALSE)
- event_date (DATETIME)
- appointment_date (DATETIME)
- created_at (DATETIME, NOT NULL)

#### NotificationReads
- id (INT, PK, AUTO_INCREMENT)
- user_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- notification_type (VARCHAR(50), NOT NULL) - 'event', 'announcement'
- related_id (INT, NOT NULL)
- read_at (DATETIME, NOT NULL)

#### Announcements
- id (INT, PK, AUTO_INCREMENT)
- title (VARCHAR(255), NOT NULL)
- content (TEXT, NOT NULL)
- created_at (DATETIME, NOT NULL)

#### DailyQuotes
- id (INT, PK, AUTO_INCREMENT)
- quote_text (TEXT, NOT NULL)
- author_name (VARCHAR(255), NOT NULL)
- category (ENUM: 'Inspirational', 'Motivational', 'Wisdom', 'Life', 'Success', 'Education', 'Perseverance', 'Courage', 'Hope', 'Kindness', NOT NULL)
- source (VARCHAR(255))
- submitted_by_id (VARCHAR(10), FK→users.user_id)
- submitted_by_name (VARCHAR(100))
- submitted_by_role (VARCHAR(20))
- status (ENUM: 'pending', 'approved', 'rejected', NOT NULL)
- moderated_by (INT, FK→users.id)
- moderated_at (DATETIME)
- rejection_reason (TEXT)
- times_displayed (INT, DEFAULT 0)
- last_displayed_date (DATE)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### Resources
- id (INT, PK, AUTO_INCREMENT)
- title (VARCHAR(255), NOT NULL)
- description (TEXT)
- resource_type (ENUM: 'file', 'link', NOT NULL)
- file_name (VARCHAR(255))
- file_path (VARCHAR(500))
- file_type (VARCHAR(100))
- file_size (INT)
- external_url (VARCHAR(500))
- category (VARCHAR(100))
- tags (TEXT)
- uploaded_by (VARCHAR(10), FK→users.user_id, NOT NULL)
- visibility (ENUM: 'all', 'students', 'counselors', DEFAULT 'all')
- is_active (BOOLEAN, DEFAULT TRUE)
- view_count (INT, DEFAULT 0)
- download_count (INT, DEFAULT 0)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### Events
- id (INT, PK, AUTO_INCREMENT)
- title (VARCHAR(255), NOT NULL)
- date (DATE, NOT NULL)
- time (VARCHAR(20), NOT NULL)
- location (VARCHAR(255), NOT NULL)
- description (TEXT)
- created_at (DATETIME, NOT NULL)

#### Messages
- id (INT, PK, AUTO_INCREMENT)
- message_id (VARCHAR(50), UK, NOT NULL)
- sender_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- receiver_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- message_text (TEXT, NOT NULL)
- created_at (DATETIME, NOT NULL)

#### FollowUpAppointments
- id (INT, PK, AUTO_INCREMENT)
- original_appointment_id (INT, FK→appointments.id, NOT NULL)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- counselor_id (VARCHAR(20), FK→counselors.counselor_id, NOT NULL)
- follow_up_date (DATE, NOT NULL)
- status (VARCHAR(50), NOT NULL)
- notes (TEXT)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

#### AppointmentOptions
- id (INT, PK, AUTO_INCREMENT)
- appointment_id (INT, FK→appointments.id, NOT NULL)
- option_type (VARCHAR(100), NOT NULL)
- option_value (VARCHAR(255), NOT NULL)
- created_at (DATETIME, NOT NULL)

#### OptimizedAppointments
- id (INT, PK, AUTO_INCREMENT)
- student_id (VARCHAR(10), FK→users.user_id, NOT NULL)
- counselor_id (VARCHAR(20), FK→counselors.counselor_id, NOT NULL)
- preferred_date (DATE, NOT NULL)
- preferred_time (VARCHAR(50), NOT NULL)
- reason (TEXT)
- status (VARCHAR(50), NOT NULL)
- created_at (DATETIME, NOT NULL)
- updated_at (DATETIME, NOT NULL)

### Relationships

- Users (1) -- (0..*) Appointments : books (student_id FK)
- Users (1) -- (1) Counselors : is (counselor_id FK)
- Users (1) -- (1) StudentPersonalInfo : has (student_id FK)
- Users (1) -- (1) StudentAcademicInfo : has (student_id FK)
- Users (1) -- (1) StudentFamilyInfo : has (student_id FK)
- Users (1) -- (1) StudentAddressInfo : has (student_id FK)
- Users (1) -- (1) StudentOtherInfo : has (student_id FK)
- Users (1) -- (0..*) StudentAwards : has (student_id FK)
- Users (1) -- (0..*) StudentGCSActivities : has (student_id FK)
- Users (1) -- (0..*) StudentServicesAvailed : has (student_id FK)
- Users (1) -- (0..*) StudentServicesNeeded : has (student_id FK)
- Users (1) -- (1) StudentResidenceInfo : has (student_id FK)
- Users (1) -- (0..*) StudentFeedbackAnalytics : has (student_id FK)
- Counselors (1) -- (0..*) CounselorAvailability : has (counselor_id FK)
- Appointments (1) -- (0..*) Notifications : triggers (related_id FK)
- Users (1) -- (0..*) Notifications : receives (user_id FK)
- Users (1) -- (0..*) NotificationReads : marks as read (user_id FK)
- Users (1) -- (0..*) Announcements : receives (no direct FK, global)
- Users (1) -- (0..*) DailyQuotes : views/submits (submitted_by_id FK)
- Users (1) -- (0..*) Resources : accesses/uploads (uploaded_by FK)
- Users (1) -- (0..*) Events : attends (no direct FK, global)
- Users (1) -- (0..*) Messages : sends/receives (sender_id/receiver_id FK)
- Appointments (1) -- (0..*) FollowUpAppointments : generates (original_appointment_id FK)
- Appointments (1) -- (0..*) AppointmentOptions : has (appointment_id FK)
- Users (1) -- (0..*) OptimizedAppointments : books (student_id FK)

## Core User Management Tables

### Users Table
Primary table for system authentication and user management.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| user_id | VARCHAR(10) | UNIQUE, NOT NULL | 10-digit unique identifier |
| email | VARCHAR(255) | UNIQUE, NOT NULL | User email address |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| role | ENUM | NOT NULL | User role: 'admin', 'counselor', 'student' |
| username | VARCHAR(100) | NULL | Display name |
| profile_picture | VARCHAR(255) | NULL | Profile image file path |
| verification_token | VARCHAR(6) | NULL | Email verification token |
| is_verified | BOOLEAN | DEFAULT FALSE | Email verification status |
| created_at | DATETIME | NOT NULL | Account creation timestamp |
| last_login | DATETIME | NULL | Last login timestamp |
| logout_time | DATETIME | NULL | Last logout timestamp |
| last_activity | DATETIME | NULL | Last user activity |
| last_active_at | DATETIME | NULL | Last active timestamp |
| last_inactive_at | DATETIME | NULL | Last inactive timestamp |

### Counselors Table
Extended profile information for counselors.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| counselor_id | VARCHAR(20) | UNIQUE, NOT NULL, FK→users.user_id | Counselor identifier |
| name | VARCHAR(100) | NOT NULL | Full name |
| degree | VARCHAR(100) | NOT NULL | Academic degree |
| email | VARCHAR(100) | UNIQUE, NOT NULL | Contact email |
| contact_number | VARCHAR(20) | NOT NULL | Contact phone (09XXXXXXXXX format) |
| address | TEXT | NOT NULL | Residential address |
| civil_status | VARCHAR(20) | NULL | Civil status |
| sex | VARCHAR(10) | NULL | Gender |
| birthdate | DATE | NULL | Date of birth |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Counselor Availability Table
Manages counselor scheduling and availability.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| counselor_id | VARCHAR(20) | NOT NULL, FK→counselors.counselor_id | Counselor identifier |
| available_days | ENUM | NOT NULL | Day: 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' |
| time_scheduled | VARCHAR(255) | NULL | Time range (HH:MM-HH:MM) or NULL |
| created_at | DATETIME | NOT NULL | Record creation timestamp |

## Appointment Management Tables

### Appointments Table
Core table for managing counseling appointments.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| preferred_date | DATE | NOT NULL | Requested appointment date |
| preferred_time | VARCHAR(50) | NOT NULL | Requested time slot |
| method_type | VARCHAR(50) | NOT NULL | Consultation method |
| consultation_type | VARCHAR(50) | NULL | Consultation type |
| counselor_preference | VARCHAR(100) | NOT NULL | Preferred counselor ID or 'No preference' |
| description | TEXT | NULL | Appointment description |
| reason | TEXT | NULL | Cancellation/rejection reason |
| status | ENUM | NOT NULL, DEFAULT 'pending' | Status: 'pending', 'approved', 'rejected', 'rescheduled', 'completed', 'cancelled' |
| purpose | ENUM | NOT NULL | Purpose: 'Counseling', 'Psycho-Social Support', 'Initial Interview' |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Follow Up Appointments Table
Tracks follow-up sessions related to original appointments.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| original_appointment_id | INT | NOT NULL, FK→appointments.id | Original appointment ID |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| counselor_id | VARCHAR(20) | NOT NULL, FK→counselors.counselor_id | Counselor identifier |
| follow_up_date | DATE | NOT NULL | Follow-up appointment date |
| status | VARCHAR(50) | NOT NULL | Follow-up status |
| notes | TEXT | NULL | Follow-up notes |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Appointment Options Table
Additional options and preferences for appointments.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| appointment_id | INT | NOT NULL, FK→appointments.id | Appointment ID |
| option_type | VARCHAR(100) | NOT NULL | Option type |
| option_value | VARCHAR(255) | NOT NULL | Option value |
| created_at | DATETIME | NOT NULL | Record creation timestamp |

### Optimized Appointments Table
Optimized appointment scheduling data.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| counselor_id | VARCHAR(20) | NOT NULL, FK→counselors.counselor_id | Counselor identifier |
| preferred_date | DATE | NOT NULL | Preferred date |
| preferred_time | VARCHAR(50) | NOT NULL | Preferred time |
| reason | TEXT | NULL | Appointment reason |
| status | VARCHAR(50) | NOT NULL | Status |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

## Student Information Tables

### Student Personal Info Table
Basic personal information for students.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| last_name | VARCHAR(100) | NULL | Last name |
| first_name | VARCHAR(100) | NULL | First name |
| middle_name | VARCHAR(100) | NULL | Middle name |
| date_of_birth | DATE | NULL | Date of birth |
| age | INT | NULL | Age |
| sex | VARCHAR(10) | NULL | Gender |
| civil_status | VARCHAR(20) | NULL | Civil status |
| contact_number | VARCHAR(20) | NULL, REGEX: /^09[0-9]{9}$/ | Contact number |
| fb_account_name | VARCHAR(100) | NULL | Facebook account name |
| place_of_birth | VARCHAR(100) | NULL | Place of birth |
| religion | VARCHAR(50) | NULL | Religion |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Academic Info Table
Academic information for students.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| student_number | VARCHAR(20) | NULL | Student number |
| course | VARCHAR(100) | NULL | Academic course |
| year_level | VARCHAR(20) | NULL | Year level |
| section | VARCHAR(20) | NULL | Class section |
| academic_status | VARCHAR(50) | NULL | Academic status |
| gwa | DECIMAL(5,2) | NULL | Grade weighted average |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Family Info Table
Family background information.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| father_name | VARCHAR(100) | NULL | Father's name |
| father_occupation | VARCHAR(100) | NULL | Father's occupation |
| mother_name | VARCHAR(100) | NULL | Mother's name |
| mother_occupation | VARCHAR(100) | NULL | Mother's occupation |
| guardian_name | VARCHAR(100) | NULL | Guardian's name |
| guardian_relationship | VARCHAR(50) | NULL | Relationship to guardian |
| guardian_contact | VARCHAR(20) | NULL | Guardian's contact |
| number_of_siblings | INT | NULL | Number of siblings |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Address Info Table
Address information for students.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| permanent_address | TEXT | NULL | Permanent address |
| present_address | TEXT | NULL | Current address |
| zip_code | VARCHAR(10) | NULL | ZIP code |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Residence Info Table
Residence and housing information.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| residence_type | VARCHAR(50) | NULL | Type: 'Dorm', 'Boarding House', 'Home' |
| landlord_name | VARCHAR(100) | NULL | Landlord name |
| landlord_contact | VARCHAR(20) | NULL | Landlord contact |
| monthly_rent | DECIMAL(10,2) | NULL | Monthly rent amount |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Other Info Table
Additional personal information.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| hobbies | TEXT | NULL | Hobbies and interests |
| skills | TEXT | NULL | Skills and abilities |
| strengths | TEXT | NULL | Personal strengths |
| weaknesses | TEXT | NULL | Areas for improvement |
| goals | TEXT | NULL | Personal goals |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Awards Table
Academic and non-academic awards.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| award_name | VARCHAR(200) | NULL | Award name |
| award_type | VARCHAR(100) | NULL | Type of award |
| awarding_body | VARCHAR(200) | NULL | Organization granting award |
| date_received | DATE | NULL | Date received |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student GCS Activities Table
Guidance and Counseling Services activities.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| activity_name | VARCHAR(200) | NULL | Activity name |
| activity_type | VARCHAR(100) | NULL | Type of activity |
| date_participated | DATE | NULL | Participation date |
| role | VARCHAR(100) | NULL | Role in activity |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Services Availed Table
Services previously received.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| service_name | VARCHAR(200) | NULL | Service name |
| service_type | VARCHAR(100) | NULL | Type of service |
| date_availed | DATE | NULL | Date service was availed |
| outcome | TEXT | NULL | Service outcome |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Services Needed Table
Services currently needed.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| service_name | VARCHAR(200) | NULL | Service name |
| service_type | VARCHAR(100) | NULL | Type of service |
| priority | VARCHAR(20) | NULL | Priority level |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

### Student Feedback Analytics Table
Feedback and analytics from counseling sessions.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| student_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Student identifier |
| appointment_id | INT | NULL, FK→appointments.id | Related appointment |
| rating | TINYINT | NULL | Rating (1-5) |
| feedback_text | TEXT | NULL | Written feedback |
| sentiment_analysis | JSON | NULL | Sentiment analysis data |
| created_at | DATETIME | NOT NULL | Record creation timestamp |
| updated_at | DATETIME | NOT NULL | Last update timestamp |

## Communication and Notification Tables

### Notifications Table
System notifications for users.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| user_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Recipient user ID |
| type | VARCHAR(50) | NOT NULL | Notification type |
| title | VARCHAR(255) | NOT NULL | Notification title |
| message | TEXT | NOT NULL | Notification content |
| related_id | INT | NULL | Related record ID |
| is_read | BOOLEAN | DEFAULT FALSE | Read status |
| event_date | DATETIME | NULL | Related event date |
| appointment_date | DATETIME | NULL | Related appointment date |
| created_at | DATETIME | NOT NULL | Creation timestamp |

### Notification Reads Table
Tracks which notifications/events/announcements have been read.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| user_id | VARCHAR(10) | NOT NULL, FK→users.user_id | User ID |
| notification_type | VARCHAR(50) | NOT NULL | Type: 'event', 'announcement' |
| related_id | INT | NOT NULL | Related record ID |
| read_at | DATETIME | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Read timestamp |

### Messages Table
Direct messaging between users.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| message_id | VARCHAR(50) | UNIQUE, NOT NULL | Unique message identifier |
| sender_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Sender user ID |
| receiver_id | VARCHAR(10) | NOT NULL, FK→users.user_id | Receiver user ID |
| message_text | TEXT | NOT NULL | Message content |
| created_at | DATETIME | NOT NULL | Message timestamp |

## Content Management Tables

### Announcements Table
System-wide announcements.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| title | VARCHAR(255) | NOT NULL | Announcement title |
| content | TEXT | NOT NULL | Announcement content |
| created_at | DATETIME | NOT NULL | Creation timestamp |

### Daily Quotes Table
Inspirational quotes for display.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| quote_text | TEXT | NOT NULL | Quote content |
| author_name | VARCHAR(255) | NOT NULL | Quote author |
| category | ENUM | NOT NULL | Category: 'Inspirational', 'Motivational', 'Wisdom', 'Life', 'Success', 'Education', 'Perseverance', 'Courage', 'Hope', 'Kindness' |
| source | VARCHAR(255) | NULL | Quote source |
| submitted_by_id | VARCHAR(10) | NULL, FK→users.user_id | Submitter ID |
| submitted_by_name | VARCHAR(100) | NULL | Submitter name |
| submitted_by_role | VARCHAR(20) | NULL | Submitter role |
| status | ENUM | NOT NULL, DEFAULT 'pending' | Status: 'pending', 'approved', 'rejected' |
| moderated_by | INT | NULL, FK→users.id | Moderator ID |
| moderated_at | DATETIME | NULL | Moderation timestamp |
| rejection_reason | TEXT | NULL | Rejection reason |
| times_displayed | INT | DEFAULT 0 | Display count |
| last_displayed_date | DATE | NULL | Last display date |
| created_at | DATETIME | NOT NULL | Creation timestamp |
| updated_at | DATETIME | NOT NULL | Update timestamp |

### Resources Table
File and link resources for users.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| title | VARCHAR(255) | NOT NULL | Resource title |
| description | TEXT | NULL | Resource description |
| resource_type | ENUM | NOT NULL | Type: 'file', 'link' |
| file_name | VARCHAR(255) | NULL | Original file name |
| file_path | VARCHAR(500) | NULL | Server file path |
| file_type | VARCHAR(100) | NULL | MIME type |
| file_size | INT | NULL | File size in bytes |
| external_url | VARCHAR(500) | NULL | External link URL |
| category | VARCHAR(100) | NULL | Resource category |
| tags | TEXT | NULL | Search tags |
| uploaded_by | VARCHAR(10) | NOT NULL, FK→users.user_id | Uploader ID |
| visibility | ENUM | NULL, DEFAULT 'all' | Visibility: 'all', 'students', 'counselors' |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| view_count | INT | DEFAULT 0 | View count |
| download_count | INT | DEFAULT 0 | Download count |
| created_at | DATETIME | NOT NULL | Creation timestamp |
| updated_at | DATETIME | NOT NULL | Update timestamp |

### Events Table
Calendar events and activities.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Internal primary key |
| title | VARCHAR(255) | NOT NULL | Event title |
| date | DATE | NOT NULL | Event date |
| time | VARCHAR(20) | NOT NULL | Event time |
| location | VARCHAR(255) | NOT NULL | Event location |
| description | TEXT | NULL | Event description |
| created_at | DATETIME | NOT NULL | Creation timestamp |

## Database Relationships

- **Users** is the central table, linked to all other tables via `user_id`
- **Appointments** connect students to counselors and track counseling sessions
- **Counselors** extends user profiles for counseling staff
- **Student information tables** provide comprehensive student profiles
- **Notifications** handle system communication
- **Content tables** (announcements, quotes, resources) manage shared content
- All tables use auto-incrementing integer primary keys
- Foreign key relationships maintain referential integrity
- Timestamps track creation and modification times