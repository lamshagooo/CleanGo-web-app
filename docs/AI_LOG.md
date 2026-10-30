
# CleanGo – AI Usage Log

## Milestone 1

### AI Tool Used
ChatGPT

### Purpose
AI was used to help understand project requirements,
create frontend pages, implement SPA navigation,
validate forms, and plan the database structure.

### Examples of AI Assistance

**Prompt 1:**
"Help me create a cleaner booking page using
HTML, Bootstrap, and JavaScript."

Result:
A booking form was developed with date, time,
duration, and price estimation.

**Prompt 2:**
"Help me create a cleaner profile and connect
it with the booking page."

Result:
Cleaner profiles were connected to booking pages
using URL hash parameters.

**Prompt 3:**
"Help me create an ERD for CleanGo with at
least five entities."

Result:
An ERD draft with six entities was created.

### AI Mistakes and Corrections

**Mistake 1 – Incorrect assumption about existing files**

AI assumed that cleaner-profile.html already existed.

How it was detected:
The project files were checked, and the page
was not present.

Correction:
A new cleaner-profile.html page was created,
connected to the cleaner cards, and tested.

**Mistake 2 – Missing dashboard navigation**

AI suggested creating a Cleaner Dashboard page
but did not initially include a navigation link.

How it was detected:
The page could not be opened through the
application's navigation.

Correction:
A Cleaner Dashboard link was added to
frontend/index.html, and the page was tested.

### Verification
The frontend pages and navigation were manually
tested in the browser.

### Notes
All booking and dashboard data are currently
demo data. Backend integration will be added
in later milestones.
----------------------------------------------------
## Milestone 2 – Database and DAO Layer

### AI Tool Used
ChatGPT

### Purpose
ChatGPT was used to support the development of the database and DAO layer for CleanGo. It helped with understanding database relationships, writing parts of the PHP code, and debugging errors.

The main focus was on implementing a reusable BaseDao class, connecting PHP to MySQL using PDO, and testing CRUD operations.

### Examples of AI Assistance

**Prompt 1: Database Structure and Relationships**

"Review the database structure for my CleanGo application. I have six entities: users, cleaner_profiles, services, cleaner_services, bookings, and reviews. Help me understand how to define their primary keys and foreign keys correctly and prepare an SQL script with sample data."

**Result:**
The database schema was prepared with six related tables. Primary keys, foreign keys, and sample records were added. The SQL script was imported and checked using phpMyAdmin.

**Prompt 2: DAO Implementation**

"Help me implement a reusable BaseDao class in PHP using PDO and prepared statements. I need basic CRUD methods that can be inherited by individual DAO classes. Keep the implementation simple and explain how inheritance works in this case."

**Result:**
ChatGPT helped generate the initial BaseDao implementation and parts of the individual DAO classes. The classes were organized using inheritance to avoid repeating database operations.

**Prompt 3: Composite Primary Key**

"My cleaner_services table uses a composite primary key instead of a single ID. How should I implement the read, update, and delete methods in its DAO class while keeping the existing BaseDao structure?"

**Result:**
Additional methods were implemented in CleanerServiceDao to work with both cleaner_id and service_id.

**Prompt 4: CRUD Testing and Debugging**

"Help me create a PHP test script to verify CRUD operations for my DAO classes. I want to test inserting, reading, updating, and deleting records without changing the existing application data."

**Result:**
A PHP test script was prepared and executed through the VS Code terminal. Temporary records were used to test CRUD operations, and the tests completed successfully.

### AI Mistakes and Corrections

**Mistake 1 – Database Configuration Error**

The initial PDO connection failed because the database configuration file was not returning the expected settings.

**How it was detected:**
An error appeared when executing the PHP connection test.

**Correction:**
The configuration file was corrected, and the connection was tested again successfully.

**Mistake 2 – PHP Test Execution Problem**

During CRUD testing, the PHP code was displayed as text instead of being executed.

**How it was detected:**
The terminal displayed the PHP statements instead of the expected test results.

**Correction:**
The PHP test file was corrected by adding the proper opening tag and required DAO files. The test was executed again and worked successfully.

### Verification

The database structure and sample records were checked using phpMyAdmin.

PHP syntax was checked for all DAO classes.

CRUD operations were tested using PHP CLI in the VS Code terminal.

All six DAO classes passed the CRUD tests.

### Notes

Milestone 2 includes the MySQL database schema, sample data, PDO connection, reusable BaseDao class, and six DAO classes.

The service layer, REST API endpoints, and frontend integration are planned for the next milestones.