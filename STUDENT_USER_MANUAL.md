# Learning Management System (LMS) — Student Portal User Manual
**A Complete Step-by-Step Practical Guide for Students and Academic Mentors**

---

## Table of Contents
1. [Introduction & Purpose](#1-introduction--purpose)
2. [Portal Access & Login Procedures](#2-portal-access--login-procedures)
   - [2.1 System Requirements & Access URL](#21-system-requirements--access-url)
   - [2.2 Logging In with Student Credentials](#22-logging-in-with-student-credentials)
   - [2.3 Forgot Password & SMS OTP Verification](#23-forgot-password--sms-otp-verification)
   - [2.4 Session Security & Duration Tracking](#24-session-security--duration-tracking)
3. [Exploring the Student Dashboard](#3-exploring-the-student-dashboard)
   - [3.1 Global Header & Navigation Controls](#31-global-header--navigation-controls)
   - [3.2 Unified Student Sidebar](#32-unified-student-sidebar)
   - [3.3 Hero Welcome Banner](#33-hero-welcome-banner)
   - [3.4 The 4 Key Academic Information Cards](#34-the-4-key-academic-information-cards)
   - [3.5 Department Courses vs. Enrolled Courses Tables](#35-department-courses-vs-enrolled-courses-tables)
4. [Course Enrollment Walkthrough](#4-course-enrollment-walkthrough)
   - [4.1 When and Why to Enroll](#41-when-and-why-to-enroll)
   - [4.2 Step-by-Step Enrollment Process](#42-step-by-step-enrollment-process)
   - [4.3 Special Handling for 1st Year (Centralized S&H) Courses](#43-special-handling-for-1st-year-centralized-sh-courses)
5. [Accessing Course Materials & Study Resources](#5-accessing-course-materials--study-resources)
   - [5.1 Viewing Enrolled Courses](#51-viewing-enrolled-courses)
   - [5.2 Civil Services Track Integration](#52-civil-services-track-integration)
   - [5.3 Filtering Materials by Unit / Module](#53-filtering-materials-by-unit--module)
   - [5.4 Material Types (PDFs, PPTs, Video Links, OneDrive / SharePoint)](#54-material-types-pdfs-ppts-video-links-onedrive--sharepoint)
   - [5.5 In-Browser Preview & Offline Downloading](#55-in-browser-preview--offline-downloading)
6. [Academic Assessments & Coding Evaluations](#6-academic-assessments--coding-evaluations)
   - [6.1 Assessment Directory & Status Tabs](#61-assessment-directory--status-tabs)
   - [6.2 Public vs. Private Assessments](#62-public-vs-private-assessments)
   - [6.3 Due Dates, Deadlines & Timers](#63-due-dates-deadlines--timers)
7. [Taking an Assessment: Step-by-Step Execution](#7-taking-an-assessment-step-by-step-execution)
   - [7.1 Solving Multiple-Choice Questions (MCQs)](#71-solving-multiple-choice-questions-mcqs)
   - [7.2 Solving Interactive Coding Problems](#72-solving-interactive-coding-problems)
   - [7.3 Selecting Programming Language (Python, C, Java)](#73-selecting-programming-language-python-c-java)
   - [7.4 Running Code with Custom Input (stdin)](#74-running-code-with-custom-input-stdin)
   - [7.5 Testing Against Public vs. Hidden Test Cases](#75-testing-against-public-vs-hidden-test-cases)
   - [7.6 Final Submission & Instant Auto-Grading](#76-final-submission--instant-auto-grading)
8. [Performance Review & Report Generation](#8-performance-review--report-generation)
   - [8.1 Understanding the Score Breakdown](#81-understanding-the-score-breakdown)
   - [8.2 Generating & Printing the Academic Performance Report](#82-generating--printing-the-academic-performance-report)
9. [Account Management & Security](#9-account-management--security)
   - [9.1 Profile Verification](#91-profile-verification)
   - [9.2 Updating Your Password](#92-updating-your-password)
   - [9.3 Notifications & System Alerts](#93-notifications--system-alerts)
   - [9.4 Secure Logout](#94-secure-logout)
10. [Frequently Asked Questions (FAQ) & Troubleshooting](#10-frequently-asked-questions-faq--troubleshooting)

---

## 1. Introduction & Purpose

The **Learning Management System (LMS)** is the university's centralized digital learning and assessment platform. Designed specifically for collegiate academic operations, the portal provides every student with an individualized dashboard to:
1. **Enroll in semester courses** based on your specific academic regulation, year, and semester.
2. **Access comprehensive lecture materials**, PPTs, syllabi, and cloud resources uploaded by faculty.
3. **Attempt academic assessments**, including objective MCQs and real-time interactive coding exams evaluated against rigorous automated test cases.
4. **Track learning progress**, view instant scores, and generate verifiable performance reports for submission to academic guides and mentors.

> [!NOTE]
> This manual serves as an official technical reference. Follow each section step-by-step to maximize your academic productivity and prevent submission or enrollment issues.

---

## 2. Portal Access & Login Procedures

### 2.1 System Requirements & Access URL
- **Portal URL**: `http://127.0.0.1:8000` (Local Network) or the official institutional domain assigned by your campus IT.
- **Recommended Browsers**: Google Chrome (v100+), Mozilla Firefox (v100+), Microsoft Edge (v100+), or Apple Safari.
- **Device Support**: Fully responsive across Laptops, Desktops, Tablets, and Smartphones.

```
       +-------------------------------------------------------------+
       |                  STUDENT LOGIN SCREEN                       |
       |  Username: [ 211FA04001                          ]          |
       |  Password: [ ******************                  ] [Show]   |
       |  [x] Remember me                   [ Forgot Password? ]     |
       |                                                             |
       |                 [ SIGN IN TO PORTAL ]                       |
       +-------------------------------------------------------------+
```

---

### 2.2 Logging In with Student Credentials
1. Open your web browser and navigate to the portal login page (`/login`).
2. In the **Username / Email** field, enter your unique **10-character Student Register Number** (e.g., `211FA04001`) or your registered institutional email address.
3. In the **Password** field, enter your account password.
   - *Default Password*: Provided by your department coordinator (e.g., `Student#123` or your customized default).
4. *(Optional)* Check **"Remember Me"** if using a personal laptop to keep your session active.
5. Click **"Sign In"**. Upon authentication, you will be redirected automatically to the **Student Dashboard**.

---

### 2.3 Forgot Password & SMS OTP Verification
If you cannot recall your password, follow these self-service recovery steps:
1. On the login page, click the **"Forgot Password?"** link.
2. Enter your **Register Number** or registered **Mobile Number**.
3. Click **"Send Verification Code (SMS)"**.
4. The system will deliver a secure 6-digit One-Time Password (OTP) via SMS through the institutional DLT gateway.
5. Enter the OTP in the verification field.
6. Specify your new password (must be at least 8 characters with letters, numbers, and symbols) and confirm it.
7. Click **"Reset Password"**. You can now immediately sign in with your new password.

---

### 2.4 Session Security & Duration Tracking
- The LMS features an automated **User Session Tracker**. Every minute you remain actively engaged in the system (studying notes or solving assessments), your learning time is logged.
- For security on public library terminals, always log out when leaving your desk. Inactivity beyond 120 minutes will automatically terminate the session.

---

## 3. Exploring the Student Dashboard

Upon logging in, you arrive at the central **Student Dashboard** (`/`).

```
+-----------------------------------------------------------------------------------------+
| [=] LMS Portal   [Search courses, materials...]     (Theme) [Bell: 3] [Profile: Alex v] |
+-----------------------------------------------------------------------------------------+
| SIDEBAR   | HERO BANNER: Welcome back, Alex Johnson!                                    |
| • Home    | Program: B.Tech in Computer Science & Engineering                           |
| • Enroll  | [ + Enroll in Courses ]                                                     |
| • Courses +-----------------------------------------------------------------------------+
| • Assign  | [ My Department ]   [ My School ]       [ Curriculum ]    [ Active Enrolled ] |
|           | Computer Science    School of Computing 24 Courses Catalog  6 Courses Active  |
|           +-----------------------------------------------------------------------------+
|           | DEPARTMENT COURSES (Preview)            | MY ENROLLED COURSES (Quick Access)|
|           | • CS301 Database Systems [dep_cse]      | • CS301 DBMS  -> [View Materials] |
|           | • CS302 Operating Systems [dep_cse]     | • CS302 OS    -> [View Materials] |
+-----------------------------------------------------------------------------------------+
```

### 3.1 Global Header & Navigation Controls
- **Global Search Bar**: Quickly locate any enrolled course or published material by typing its name or subject code.
- **Notification Bell**: Displays real-time academic alerts (e.g., new materials uploaded by faculty, assessment deadlines, or graded submissions).
- **Theme Switcher**: Toggle between **Light Mode** (default) and **Dark Mode** for comfortable night studying.
- **Profile Avatar Dropdown**: Gives one-click access to **Profile Details**, **Change Password**, and **Sign Out**.

### 3.2 Unified Student Sidebar
The collapsible left sidebar provides three essential student shortcuts:
- **Enroll in Courses** (`/student/enroll`): Register for new semester subjects.
- **My Courses** (`/student/my-courses`): Access notes, study PDFs, and cloud links for your active subjects.
- **Assignments** (`/student/assignments`): View pending assessments, attempt coding tests, and review grades.

### 3.3 Hero Welcome Banner
Displays your greeting, degree level, and a primary call-to-action button to **"Enroll in Courses"**.

### 3.4 The 4 Key Academic Information Cards
1. **My Department**: Shows your parent academic department (e.g., *Computer Science and Engineering*) and degree program (*B.Tech*).
2. **My School**: Identifies your collegiate school (e.g., *School of Computing and Informatics*).
3. **Curriculum (Catalog)**: Total number of courses offered within your department under current regulations.
4. **Enrolled Courses**: Total count of active semester courses you have registered for.

### 3.5 Department Courses vs. Enrolled Courses Tables
- **Department Courses Table**: Displays the catalog of courses for your department with year, semester, and code.
- **Enrolled Courses Table**: Lists your active subjects with direct **"View Materials →"** buttons.

---

## 4. Course Enrollment Walkthrough

### 4.1 When and Why to Enroll
At the start of every academic semester, or when instructed by your department coordinator, you must register for your semester subjects. Until you enroll in a course, its materials and assignments will not appear on your dashboard.

```
       FLOWCHART: COURSE ENROLLMENT SEQUENCE
       =====================================
       [ 1. Select Regulation ] ➔ E.g., R22 (B.Tech)
                 │
                 ▼
       [ 2. Select Year & Sem ] ➔ E.g., Year 2, Semester 1
                 │
                 ▼
       [ 3. View Loaded Subjects] ➔ Department & 1st Year Centralized
                 │
                 ▼
       [ 4. Check Subject Boxes ] ➔ [x] Data Structures
                 │                ➔ [x] Digital Logic
                 ▼
       [ 5. Click 'Enroll' ] ➔ Instant Confirmation & Dashboard Sync
```

---

### 4.2 Step-by-Step Enrollment Process
1. Click **"Enroll in Courses"** in the sidebar or from the top dashboard hero banner.
2. **Select Academic Regulation**:
   - The dropdown automatically filters regulations matching your program level (e.g., `R22`, `R19`).
3. **Select Year & Semester**:
   - Choose your academic **Year** (`1`, `2`, `3`, or `4`).
   - Choose your **Semester** (`1` or `2`).
4. **Review Fetched Courses**:
   - The system triggers a real-time AJAX fetch and displays all matching subjects with their **Course Code**, **Subject Name**, and **Curriculum Year**.
5. **Select Your Courses**:
   - Check the checkbox next to each course you are attending this semester.
   - *Note*: Courses you are already enrolled in are marked with a green badge and disabled to prevent duplicates.
6. **Submit Enrollment**:
   - Click the green **"Enroll in Selected Courses"** button at the bottom.
   - You will see a success message: *"Successfully enrolled in selected courses."*

### 4.3 Special Handling for 1st Year (Centralized S&H) Courses
If you are a 1st-year student, your foundational subjects (Mathematics, Engineering Physics, Chemistry, English, Python Programming) are administered centrally by the **School of Applied Sciences & Humanities (S&H)**. 
- The enrollment system automatically detects 1st-year students and combines your home department subjects with all centralized S&H courses so you can enroll in all required common subjects without switching portals.

---

## 5. Accessing Course Materials & Study Resources

Navigate to **"My Courses"** (`/student/my-courses`) to review all enrolled courses.

```
+-----------------------------------------------------------------------------------------+
| MY ENROLLED COURSES                                                                     |
|                                                                                         |
| [ CARD: Database Management Systems ]      [ CARD: Operating Systems ]                  |
| Code: CS301 | Year 2, Sem 1                Code: CS302 | Year 2, Sem 1                  |
| Materials Available: 14 Resources          Materials Available: 9 Resources             |
| [ VIEW COURSE MATERIALS -> ]               [ VIEW COURSE MATERIALS -> ]                 |
+-----------------------------------------------------------------------------------------+
```

### 5.1 Viewing Enrolled Courses
Each course card shows:
- **Course Name & System Code** (e.g., `CS301: Database Management Systems`).
- **Academic Year & Semester**.
- **Total Materials Count** (number of lecture notes, slides, and links published by faculty).

### 5.2 Civil Services Track Integration
If you are selected for the university's specialized **Civil Services Coaching Program**, all Civil Services modules (General Studies, CSAT, Governance) will automatically appear in your course repository under a dedicated collegiate badge.

---

### 5.3 Filtering Materials by Unit / Module
When you click into a course (`/student/my-courses/{course}/materials`), materials are organized into tabs or module filters:
- **Unit 1 / Module 1**: Fundamentals & syllabus intro.
- **Unit 2 - Unit 5**: Detailed course units.
- **Lab & Practical**: Manuals, code templates, and experiments.
- **General / Reference**: Textbooks and question banks.

---

### 5.4 Material Types
Faculty publish four distinct types of learning assets:
1. **PDF Lecture Notes & Textbooks**: Standard university-formatted documents.
2. **PowerPoint Presentations (PPT/PPTX)**: Class lecture slide decks.
3. **Microsoft OneDrive & SharePoint Cloud Documents**: Direct high-speed links to faculty university cloud repositories.
4. **External Web & Video Links**: YouTube lecture links, NPTEL references, and technical documentation.

---

### 5.5 In-Browser Preview & Offline Downloading
- **Inline Preview**: Click **"View File"** to open PDFs directly within the built-in browser reader without downloading.
- **Direct Download**: Click **"Download"** to save files offline to your PC or phone.
- **Cloud Links**: Click **"Open OneDrive Document"** to access living documents synchronized with Microsoft 365.

---

## 6. Academic Assessments & Coding Evaluations

Navigate to **"Assignments"** (`/student/assignments`) to manage all academic tests, lab evaluations, and continuous internal evaluations (CIE).

```
+-----------------------------------------------------------------------------------------+
| ASSIGNMENTS REPOSITORY                                                                  |
| [ 24 Total ]   [ 4 Pending ]   [ 18 Completed ]   [ 2 Overdue ]   [ Avg Score: 87% ]    |
+-----------------------------------------------------------------------------------------+
| Tabs: [ ALL (24) ]  [ PENDING (4) ]  [ COMPLETED (18) ]  [ OVERDUE (2) ]                |
|                                                                                         |
| Search: [ Search title or course... ]         Course Filter: [ All Enrolled Courses v ] |
|                                                                                         |
| ASSIGNMENT CARD                                                                         |
| • Title: CIE-1: Python Object Oriented Programming & File I/O                           |
| • Course: CS204 Advanced Python Programming                                             |
| • Questions: 10 MCQs + 2 Coding Problems | Max Marks: 30                                |
| • Due Date: Nov 15, 2026 at 11:59 PM (Closes in 4 Days)                                 |
| • Status: [ PENDING SUBMISSION ]                                                        |
|   [ ATTEMPT / START ASSESSMENT -> ]                                                     |
+-----------------------------------------------------------------------------------------+
```

### 6.1 Assessment Directory & Status Tabs
- **All**: Complete historical list of all published assessments.
- **Pending**: Active tasks waiting for your submission before the deadline.
- **Completed / Graded**: Assessments you have submitted, showing awarded marks and auto-evaluation results.
- **Overdue**: Assessments whose due date has passed without submission.

### 6.2 Public vs. Private Assessments
- **Public Assessments**: Assigned to the entire enrolled class cohort.
- **Private Assessments**: Individualized remedial, make-up, or advanced problem sets assigned specifically to your register number. These appear automatically with a distinctive **Private Assignment** badge.

### 6.3 Due Dates, Deadlines & Timers
Every assignment card clearly highlights:
- **Due Date and Time** (e.g., `Nov 15, 2026 at 11:59 PM`).
- **Time Remaining Pill**: Indicates green (*Safe*), amber (*Due Soon*), or red (*Due in < 12 Hours*).
- **Submissions After Due Date**: If an assessment is closed by faculty, submissions are locked. Always complete your work well before the cutoff.

---

## 7. Taking an Assessment: Step-by-Step Execution

Click **"Attempt Assessment"** or **"View Assessment"** to open the test execution interface (`/student/assignments/{id}`).

```
+-----------------------------------------------------------------------------------------+
| CIE-1: Python Object Oriented Programming                       Max Marks: 30           |
| Course: CS204 | Faculty: Dr. K. Rao                             Due: Nov 15, 11:59 PM   |
+-----------------------------------------------------------------------------------------+
| SECTION 1: MULTIPLE CHOICE QUESTIONS (MCQs)                                             |
| Question 1 of 2: Which keyword is used to inherit a class in Python?          [2 Marks] |
|   ( ) A. extends                                                                        |
|   (o) B. class Child(Parent):                                                           |
|   ( ) C. inherits                                                                       |
|   ( ) D. super                                                                          |
+-----------------------------------------------------------------------------------------+
| SECTION 2: INTERACTIVE CODING PROBLEM                                                   |
| Question 2 of 2: Write a function to check if a string is a Palindrome.       [10 Marks]|
| Problem Statement: Return True if s reads the same backwards, else False.               |
|                                                                                         |
| Language: [ Python v ]                                                                  |
| Code Editor:                                                                            |
| +-------------------------------------------------------------------------------------+ |
| | 1 | def is_palindrome(s):                                                           | |
| | 2 |     cleaned = ''.join(c.lower() for c in s if c.isalnum())                      | |
| | 3 |     return cleaned == cleaned[::-1]                                             | |
| | 4 |                                                                                 | |
| | 5 | print(is_palindrome(input()))                                                   | |
| +-------------------------------------------------------------------------------------+ |
| Custom Input (stdin): [ racecar                      ]                                  |
| [ RUN CODE / TEST ] -> Output: True | Passed 2/2 Public Test Cases                      |
+-----------------------------------------------------------------------------------------+
|                                                          [ SUBMIT FINAL ASSESSMENT ]    |
+-----------------------------------------------------------------------------------------+
```

### 7.1 Solving Multiple-Choice Questions (MCQs)
1. Read the question text and code snippet carefully.
2. Click the radio button corresponding to your choice (**A**, **B**, **C**, or **D**).
3. To change your answer, select another radio button.

---

### 7.2 Solving Interactive Coding Problems
For practical coding questions, the portal provides a real-time cloud programming IDE:
1. **Read the Problem Specification**:
   - **Problem Statement**: Understand the objective.
   - **Input Format**: How the grading harness feeds data (via `stdin`).
   - **Output Format**: Expected standard output (`stdout`).
   - **Constraints**: Time limit (e.g., 2 seconds) and memory limits.

---

### 7.3 Selecting Programming Language
Use the language dropdown at the top-right of the code box:
- **Python** (`python`): For Python 3.x scripts.
- **C** (`c`): For standard C programs (compiled via GCC).
- **Java** (`java`): For standard Java classes (compiled via OpenJDK).

---

### 7.4 Running Code with Custom Input (stdin)
1. Write or paste your solution into the code editor.
2. Type custom test inputs into the **Custom Input (stdin)** text box.
3. Click **"Run Code / Test"**.
4. The system executes your program in an isolated cloud sandbox and returns the stdout result, compilation messages, or runtime errors in the terminal box below.

---

### 7.5 Testing Against Public vs. Hidden Test Cases
- **Public Test Cases**: Displayed directly on your screen (sample inputs and sample outputs). When you click **"Run Code"**, the evaluator checks these immediately.
- **Hidden Test Cases**: Edge cases (empty inputs, large arrays, boundary values) stored securely on the server. These are evaluated during final submission to compute your grade.

---

### 7.6 Final Submission & Instant Auto-Grading
1. Once all MCQs are marked and your code solutions pass your tests, click the large green **"Submit Assessment"** button at the bottom.
2. Confirm the browser prompt: *"Are you sure you want to submit? You cannot re-attempt this assessment."*
3. **Automated Evaluation Engine**:
   - The portal evaluates all MCQs against the master answer key.
   - The portal compiles and runs your code against all hidden test cases.
4. **Instant Score Result**:
   - You are immediately redirected to the result scorecard displaying:
     `"Assignment submitted! You scored 28 / 30 Marks."`

---

## 8. Performance Review & Report Generation

After submitting an assessment, the page transforms into an in-depth **Scorecard & Performance Review** (`/student/assignments/{id}`).

```
+-----------------------------------------------------------------------------------------+
| ASSESSMENT PERFORMANCE SCORECARD                                                        |
| Student: Alex Johnson (211FA04001)                Date: Nov 10, 2026, 11:45 AM          |
| Course: CS204 Advanced Python                     Marks: 28 / 30 (93.3% - Excellent)    |
+-----------------------------------------------------------------------------------------+
| SUMMARY: [ 9 Correct Questions ]   [ 1 Incorrect ]   [ 0 Unattempted ]                  |
+-----------------------------------------------------------------------------------------+
| QUESTION BREAKDOWN                                                                      |
| Question 1 (MCQ): [ CORRECT ] +2 Marks                                                  |
| • Your Answer: (B) class Child(Parent):                                                 |
| • Correct Answer: (B)                                                                   |
|                                                                                         |
| Question 2 (Coding): [ PASSED ] +10 Marks                                               |
| • Test Cases: Passed 5 / 5 (100% test coverage)                                         |
| • Execution Summary: Completed in 0.12s without runtime exceptions                       |
+-----------------------------------------------------------------------------------------+
| [ PRINT / DOWNLOAD PDF PERFORMANCE REPORT ]                                             |
+-----------------------------------------------------------------------------------------+
```

### 8.1 Understanding the Score Breakdown
- **Marks Awarded**: Total points scored out of maximum points.
- **Accuracy Percentage**: Percentage efficiency calculated automatically.
- **Question-by-Question Review**:
  - Highlights correct answers in green.
  - Highlights incorrect answers in red with the correct answer indicated.
  - Displays test case pass rates for coding challenges.

### 8.2 Generating & Printing the Academic Performance Report
For laboratory records, continuous internal evaluations (CIE), or submission to your academic guide:
1. Click **"Download / Print Report"** (`/student/assignments/{id}/download`).
2. A formal institutional evaluation certificate is rendered containing:
   - University watermark & header.
   - Student Full Name, Register Number, Department, and School.
   - Assignment Title, Course Code, and Submission Timestamp.
   - Full question breakdown, code listings, and faculty feedback notes.
3. Press `Ctrl + P` (or `Cmd + P` on Mac) to save directly as **PDF** or print a physical copy.

---

## 9. Account Management & Security

```
        USER ACCOUNT CONTROLS (Top-Right Header Menu)
        +-----------------------------------------------+
        | Alex Johnson                                  |
        | 211FA04001 • Student (CSE)                    |
        +-----------------------------------------------+
        | [x] My Profile (/profile)                     |
        | [x] Change Password (/password)               |
        | [x] Dark / Light Mode Toggle                  |
        +-----------------------------------------------+
        | [->] Sign Out (/logout)                       |
        +-----------------------------------------------+
```

### 9.1 Profile Verification
- Click your name in the top right and select **"Profile"** (`/profile`).
- Verify that your **Register Number**, **Department**, **School**, **Email**, and **Mobile Number** are correct.
- If your phone number or email changes, update it here to ensure you receive SMS OTP notifications.

### 9.2 Updating Your Password
1. Navigate to **"Change Password"** (`/password`) or click **Change Password** from the profile menu.
2. Enter your **New Password**.
3. Confirm the new password.
4. Click **"Save Changes"**.
5. *Security Tip*: Use a passphrase of at least 8 characters including uppercase letters, numbers, and special symbols. Never share your password with other students.

### 9.3 Notifications & System Alerts
- Click the **Bell Icon** in the top header.
- View real-time notifications when:
  - A faculty member uploads new lecture notes or question banks.
  - An assignment deadline is approaching.
  - An assessment submission is evaluated.

### 9.4 Secure Logout
Always click **"Sign Out"** at the bottom of the profile dropdown before closing your browser, especially on shared lab computers.

---

## 10. Frequently Asked Questions (FAQ) & Troubleshooting

### Q1: I cannot see any courses on my dashboard or enrollment page.
- **Cause**: Your student account might not have an assigned department or program level in your profile.
- **Solution**: Open `/profile`. If your Department shows *None* or *Unassigned*, contact your Department Coordinator or Faculty Advisor to update your profile mapping.

### Q2: Why does the course enrollment say "No courses found for this semester"?
- **Cause**: You may have selected a Regulation (e.g., `R19`) that does not offer courses for the selected year and semester in your department.
- **Solution**: Switch to your active cohort regulation (e.g., `R22`) and re-check the Year/Semester options.

### Q3: My code works on my local computer, but the portal says "Failed Test Cases".
- **Check 1 — Extraneous Prompts**: Do not include interactive text inside `input()`. E.g., write `x = int(input())`, **not** `x = int(input("Enter number: "))`. The grading engine compares exact string outputs.
- **Check 2 — Whitespace & Newlines**: Ensure your program does not output trailing spaces or blank lines unless expected.
- **Check 3 — Timeout**: If your code takes longer than 2.0 seconds (e.g., infinite loop or non-optimal $O(N^2)$ algorithm), it will terminate with a *Time Limit Exceeded* error.

### Q4: Can I edit or re-submit my assignment after clicking "Submit Assessment"?
- **Policy**: To maintain academic integrity, assignments allow **one final submission**. You cannot re-submit after clicking the final submit button. Use the **"Run Code / Test"** button as many times as you like before submitting.

### Q5: I forgot my password and did not receive the SMS OTP.
- **Solution 1**: Ensure your mobile number is registered with country code prefix (e.g., `+91`).
- **Solution 2**: Check your mobile network connectivity and SMS spam folder.
- **Solution 3**: If your mobile number was recently changed, contact your Department Coordinator to update your phone number in the portal directory.

### Q6: Can I take assessments on my mobile phone?
- **Answer**: Yes. The LMS is fully responsive. You can view course materials and answer MCQs comfortably on smartphones. For programming assessments with multi-line code, using a laptop or desktop computer with a physical keyboard is strongly recommended.

---

**End of Student Portal User Manual**  
*Technology Development (TD) & Academic Administration Directorate*  
*Document Version: 2.4 | Academic Year 2026–2027*

