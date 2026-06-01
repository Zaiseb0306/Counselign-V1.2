# System Flowchart

This flowchart illustrates the complete user journey through the Counselign counseling management system, covering all implemented features and functions for each user role (Student, Counselor, Admin).

```mermaid
flowchart TD
    A[User Accesses System] --> B[Login Page]
    B --> C{Authentication}
    C -->|Success| D{Determine Role}
    C -->|Failure| B

    D -->|Student| E[Student Dashboard]
    D -->|Counselor| F[Counselor Dashboard]
    D -->|Admin| G[Admin Dashboard]

    %% STUDENT FEATURES %%
    E --> H[View Profile]
    E --> I[Schedule Appointment]
    E --> J[My Appointments]
    E --> K[Follow-up Sessions]
    E --> L[Provide Feedback]
    E --> M[Messages]
    E --> N[Notification History]
    E --> O[Announcements]

    %% COUNSELOR FEATURES %%
    F --> P[Scheduled Appointments]
    F --> Q[View All Appointments]
    F --> R[Pending Feedback]
    F --> S[View Feedback]
    F --> T[Follow-up Sessions]
    F --> U[History Reports]
    F --> V[Messages]
    F --> W[Notification History]
    F --> X[Counselor Profile]
    F --> Y[Announcements]

    %% ADMIN FEATURES %%
    G --> Z[Admins Management]
    G --> AA[Recent Appointments]
    G --> BB[Scheduled Appointments]
    G --> CC[View All Appointments]
    G --> DD[Feedback Questions]
    G --> EE[View Feedback]
    G --> FF[Feedback Analytics]
    G --> GG[Follow-up Sessions]
    G --> HH[Data Analytics]
    G --> II[Resources]
    G --> JJ[Announcements]
    G --> KK[History Reports]
    G --> LL[Counselor Info]
    G --> MM[Account Settings]

    %% STUDENT SUB-FLOWS %%
    H --> E
    I --> II{Appointment Scheduling Process}
    II --> III[Select Consultation Type]
    III --> IV[Choose Date & Time]
    IV --> V[Select Counselor Preference]
    V --> VI[Provide Description & Reason]
    VI --> VII[Submit Request]
    VII --> VIII{Validation}
    VIII -->|Success| IX[Appointment Pending - Wait for Approval]
    VIII -->|Failure| IV
    IX --> J

    J --> JJ{Appointment Management}
    JJ --> KK[View Pending Appointments]
    JJ --> LL[View Approved Appointments]
    JJ --> MM[View Completed Appointments]
    JJ --> NN[View Rejected Appointments]
    JJ --> OO[View Rescheduled Appointments]
    KK --> J
    LL --> J
    MM --> J
    NN --> J
    OO --> J

    K --> KK{Completed Appointments}
    KK --> LL[View Completed Sessions]
    LL --> MM[Request Follow-up]
    MM --> NN{Eligibility Check}
    NN -->|Eligible| OO[Follow-up Created]
    NN -->|Not Eligible| PP[Cannot Request]
    OO --> K
    PP --> K

    L --> LL{Provide Feedback}
    LL --> MM[Rate Counseling Session 1-5]
    MM --> NN[Write Detailed Feedback]
    NN --> OO[Submit Feedback]
    OO --> E

    M --> MM{Messaging System}
    MM --> NN[View Counselor Messages]
    NN --> OO[Send Reply]
    OO --> PP[Message Sent]
    PP --> M

    N --> NN{Notification History}
    NN --> OO[View All Notifications]
    OO --> PP[Mark as Read]
    PP --> QQ[Clear Read Notifications]
    QQ --> N

    O --> OO{Announcements}
    OO --> PP[View All Announcements]
    PP --> QQ[Read Announcement Details]
    QQ --> O

    %% COUNSELOR SUB-FLOWS %%
    P --> PP{Scheduled Appointments}
    PP --> QQ[View Today's Appointments]
    QQ --> RR[View Upcoming Appointments]
    RR --> SS[View Past Appointments]
    SS --> TT[Update Appointment Status]
    TT --> UU{Status Options}
    UU -->|Approve| VV[Mark Approved]
    UU -->|Reject| WW[Mark Rejected]
    UU -->|Complete| XX[Mark Completed]
    UU -->|Reschedule| YY[Mark Rescheduled]
    VV --> ZZ[Send Notification to Student]
    WW --> ZZ
    XX --> ZZ
    YY --> ZZ
    ZZ --> P

    Q --> QQ{View All Appointments}
    QQ --> RR[Filter by Status/Date]
    RR --> SS[Search Appointments]
    SS --> TT[Bulk Status Updates]
    TT --> QQ

    R --> RR{Pending Feedback}
    RR --> SS[View Students Needing Feedback]
    SS --> TT[Send Feedback Reminders]
    TT --> UU[Track Feedback Completion]
    UU --> R

    S --> SS{View Feedback}
    SS --> TT[View All Student Feedback]
    TT --> UU[Filter by Rating/Date]
    UU --> VV[Export Feedback Reports]
    VV --> S

    T --> TT{Follow-up Management}
    TT --> UU[View Pending Follow-ups]
    UU --> VV[Schedule Follow-up Session]
    VV --> WW[Update Follow-up Status]
    WW --> XX[Send Follow-up Notifications]
    XX --> T

    U --> UU{History Reports}
    UU --> VV[Generate Appointment Reports]
    VV --> WW[Generate Feedback Reports]
    WW --> XX[Generate Follow-up Reports]
    XX --> YY[Export Reports to PDF/Excel]
    YY --> U

    V --> VV{Counselor Messaging}
    VV --> WW[View Student Messages]
    WW --> XX[Send Replies]
    XX --> YY[Message History]
    YY --> V

    W --> WW{Counselor Notifications}
    WW --> XX[View System Notifications]
    XX --> YY[View Appointment Updates]
    YY --> ZZ[Mark Notifications Read]
    ZZ --> W

    X --> XX{Counselor Profile}
    XX --> YY[View Profile Information]
    YY --> ZZ[Update Profile Details]
    ZZ --> AAA[Manage Availability Schedule]
    AAA --> BBB[Set Working Hours per Day]
    BBB --> X

    Y --> YY{Counselor Announcements}
    YY --> ZZ[View All Announcements]
    ZZ --> AAA[Read Announcement Details]
    AAA --> Y

    %% ADMIN SUB-FLOWS %%
    Z --> ZZ{Admins Management}
    ZZ --> AAA[Create New Admin]
    AAA --> BBB[Edit Admin Permissions]
    BBB --> CCC[View Admin Activity Logs]
    CCC --> DDD[Deactivate Admin Account]
    DDD --> Z

    AA --> AAA{Recent Appointments}
    AAA --> BBB[View Last 30 Days Appointments]
    BBB --> CCC[Filter by Status/Counselor]
    CCC --> DDD[Quick Status Updates]
    DDD --> AA

    BB --> BBB{Scheduled Appointments}
    BBB --> CCC[View All Scheduled Sessions]
    CCC --> DDD[Calendar View]
    DDD --> EEE[Export Schedule]
    EEE --> BB

    CC --> CCC{View All Appointments}
    CCC --> DDD[Comprehensive Appointment List]
    DDD --> EEE[Advanced Filtering]
    EEE --> FFF[Export Data]
    FFF --> CC

    DD --> DDD{Feedback Questions Management}
    DDD --> EEE[Create Feedback Questions]
    EEE --> FFF[Edit Existing Questions]
    FFF --> GGG[Activate/Deactivate Questions]
    GGG --> HHH[View Question Analytics]
    HHH --> DD

    EE --> EEE{View Feedback}
    EEE --> FFF[View All Feedback Submissions]
    FFF --> GGG[Filter by Date/Rating/Counselor]
    GGG --> HHH[Generate Feedback Reports]
    HHH --> EE

    FF --> FFF{Feedback Analytics}
    FFF --> GGG[View Rating Trends]
    GGG --> HHH[Analyze Feedback Categories]
    HHH --> III[Generate Analytics Reports]
    III --> JJJ[Export Analytics Data]
    JJJ --> FF

    GG --> GGG{Follow-up Sessions}
    GGG --> HHH[View All Follow-ups]
    HHH --> III[Schedule New Follow-ups]
    III --> JJJ[Monitor Follow-up Completion]
    JJJ --> KKK[Generate Follow-up Reports]
    KKK --> GG

    HH --> HHH{Data Analytics}
    HHH --> III[Appointment Statistics]
    III --> JJJ[User Activity Reports]
    JJJ --> KKK[Counselor Performance Metrics]
    KKK --> LLL[System Usage Analytics]
    LLL --> MMM[Export Analytics Dashboard]
    MMM --> HH

    II --> III{Resources Management}
    III --> JJJ[Upload New Resources]
    JJJ --> KKK[Categorize Resources]
    KKK --> LLL[Set Visibility Permissions]
    LLL --> MMM[Track Resource Usage]
    MMM --> NNN[Manage Resource Categories]
    NNN --> II

    JJ --> JJJ{Announcements Management}
    JJJ --> KKK[Create New Announcements]
    KKK --> LLL[Edit Announcements]
    LLL --> MMM[Schedule Announcement Timing]
    MMM --> NNN[Track Announcement Views]
    NNN --> OOO[Archive Old Announcements]
    OOO --> JJ

    KK --> KKK{History Reports}
    KKK --> LLL[Generate System Reports]
    LLL --> MMM[Export Historical Data]
    MMM --> NNN[Backup System Data]
    NNN --> KK

    LL --> LLL{Counselor Information}
    LLL --> MMM[View All Counselors]
    MMM --> NNN[Manage Counselor Profiles]
    NNN --> OOO[View Counselor Statistics]
    OOO --> PPP[Assign Counselor Permissions]
    PPP --> LL

    MM --> MMM{Account Settings}
    MMM --> NNN[Change Password]
    NNN --> OOO[Update Profile Information]
    OOO --> PPP[Configure System Preferences]
    PPP --> QQQ[Security Settings]
    QQQ --> MM
```