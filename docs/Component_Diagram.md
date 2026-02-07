# ProSkill Academy - Component Diagram

This diagram describes the high-level modular structure of the system, showing how the Frontend, Authentication, Backend Services, and Database interact.

```mermaid
graph TB
    %% Styling Definitions - Large Font & Bold
    classDef default fontSize:24px,font-weight:bold;
    classDef package fill:#eef2f5,stroke:#333,stroke-width:3px;
    classDef subpackage fill:#fff,stroke:#666,stroke-width:2px,stroke-dasharray: 5 5;
    classDef component fill:#e3f2fd,stroke:#1565c0,stroke-width:2px;
    classDef database fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px;

    %% 1. Frontend Module
    subgraph Frontend_Module ["Frontend Module"]
        direction TB
        subgraph Interfaces ["User Interfaces"]
            direction TB
            Student_UI["Student Interface"]
            Tutor_UI["Tutor Interface"]
            Admin_UI["Admin Interface"]
        end
        Web_Browser["Web Browser"]
        Mobile_App["Mobile Interface"]
    end

    %% 2. Authentication Module
    subgraph Auth_Module ["Authentication Module"]
        direction TB
        Login_Comp["Login Component"]
        Register_Comp["Registration Component"]
        Logout_Comp["Logout Component"]
    end

    %% 3. Backend Service
    subgraph Backend_Service ["Backend Service"]
        direction TB
        
        subgraph User_Mgmt_Pkg ["User Management Package"]
            Session_Mgr["Session Manager"]
            User_Profile["User Profile"]
        end

        subgraph Course_Mgmt_Pkg ["Course Management Package"]
            Course_Creator["Course Creator"]
            Course_Viewer["Course Viewer"]
            Enrollment_Sys["Enrollment System"]
        end

        subgraph Payment_Sys_Pkg ["Payment System"]
            Payment_Proc["Payment Processor"]
            Sub_Mgr["Subscription Manager"]
        end

        subgraph Tutor_Svc_Pkg ["Tutor Services Package"]
            Session_Booking["Session Booking"]
            Chat_Comp["Chat Component"]
            Feedback_Mech["Feedback Mechanism"]
        end

        subgraph Admin_Svc_Pkg ["Admin Services Package"]
            Instructor_Management["Instructor Management"]
            Report_Generator["Report Generator"]
        end
        
        %% STRICT INTERNAL VERTICAL STACKING
        %% Linking specific nodes ensures standardized width and flow
        Session_Mgr ~~~ Course_Viewer
        Course_Viewer ~~~ Payment_Proc
        Payment_Proc ~~~ Chat_Comp
        Chat_Comp ~~~ Report_Generator
    end

    %% 4. Database System
    subgraph Database_System ["Database System"]
        direction TB
        User_DB[("User Records")]
        Course_DB[("Course Records")]
        Payment_DB[("Payment Records")]
        Session_DB[("Session Logs")]
    end

    %% --- STRICT GLOBAL VERTICAL LAYOUT ---
    %% Connecting the bottom of one section to the top of the next
    Mobile_App ~~~ Login_Comp
    Logout_Comp ~~~ Session_Mgr
    
    %% Crucial: Link the lowest element of Backend to the top of Database
    Report_Generator ~~~ User_DB

    %% --- Connections ---

    %% Client -> Frontend
    Web_Browser --> Frontend_Module
    Mobile_App --> Frontend_Module

    %% Frontend -> Auth
    Student_UI --> Auth_Module
    Tutor_UI --> Auth_Module
    Auth_Module --> Login_Comp
    
    %% Auth -> Backend (User Mgmt)
    Login_Comp -->|Verify Identity| Session_Mgr
    Session_Mgr -->|Load Data| User_Profile

    %% Student Actions: Course & Payment
    Student_UI -->|Browse| Course_Viewer
    Student_UI -->|Enroll| Enrollment_Sys
    Enrollment_Sys -->|Process Fee| Payment_Proc
    Payment_Proc -->|Confirm| Sub_Mgr
    Payment_Proc -->|Log| Payment_DB

    %% Student Actions: Tutor Interaction
    Student_UI -->|Book Session| Session_Booking
    Student_UI -->|Chat| Chat_Comp
    Student_UI -->|Rate| Feedback_Mech

    %% Tutor Actions
    Tutor_UI -->|Create Course| Course_Creator
    Tutor_UI -->|Manage Schedule| Session_Booking
    Tutor_UI -->|Reply| Chat_Comp

    %% Admin Actions
    Admin_UI --> Admin_Svc_Pkg
    Instructor_Management -->|Approve| User_Profile
    Report_Generator -->|Analyze| Course_DB

    %% Backend Persistence
    User_Mgmt_Pkg --> User_DB
    Course_Mgmt_Pkg --> Course_DB
    Tutor_Svc_Pkg --> Session_DB

    %% Apply Classes
    class Frontend_Module,Auth_Module,Backend_Service,Database_System package
    class User_Mgmt_Pkg,Course_Mgmt_Pkg,Payment_Sys_Pkg,Tutor_Svc_Pkg,Admin_Svc_Pkg subpackage
    class Student_UI,Tutor_UI,Web_Browser,Login_Comp,Session_Mgr,Course_Creator,Payment_Proc component
    class User_DB,Course_DB,Payment_DB,Session_DB database
```

## Detailed Flow Description

1.  **Frontend Module**: The entry point for all users (Web/Mobile).
2.  **Authentication**: Users (Student/Tutor) authenticate via Login/Register components.
3.  **User Management**: Upon login, the Session Manager establishes context.
4.  **Course Management**: Students browse/view courses; Tutors create content.
5.  **Payment System**: Handles transactions when students enroll.
6.  **Tutor Services**: Facilitates booking, real-time chat, and quality feedback.
7.  **Admin Services**: Allows oversight of instructors and system reporting.
8.  **Database**: The final persistence layer for all modules.
