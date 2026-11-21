
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

----------------------------------------------------
## Milestone 3 – Business Logic, REST API, OpenAPI and External API

### AI Tools Used
- ChatGPT
- Codex
- Claude (Anthropic)

### What Each Tool Helped With

**ChatGPT** helped me read the official requirements, check which milestone is
currently active, choose a relevant external API, and write instructions for Codex.

**Codex** implemented the first version of Milestone 3 in the local project:
- six Service classes with validation (UserService, CleanerProfileService,
  ServiceService, CleanerServiceService, BookingService, ReviewService)
- FlightPHP JSON routes for all CRUD operations
- OpenAPI file (backend/docs/openapi.json) and Swagger UI (/docs)
- LocationService with OpenStreetMap Nominatim address search
- test scripts (backend/tests/smoke.php and a fake Nominatim server)

**Claude** reviewed the Codex code, made it simpler to explain, tested everything
on my XAMPP installation, fixed the problems that appeared there, and added
error logging for the external API.

### Important Prompts

**Prompt 1 (ChatGPT):**
"Check whether it is now Milestone 3 or Milestone 4."

Result: ChatGPT checked the official document. The current milestone is
Milestone 3 (deadline 23.11.2026 at 08:00).

**Prompt 2 (instructions sent to Codex):**
"Please do not implement the weather forecast feature. It is not relevant to
CleanGo. Instead, use the OpenStreetMap Nominatim API to search for cleaning
locations by address. Keep the implementation very simple: the user enters an
address, the backend calls the external API, the API returns matching location
information. Handle errors and timeouts. Document the endpoint in Swagger.
Remember that I am a second-year student and I must explain every part of this
project during my oral defense."

Result: Codex implemented GET /locations/search with Nominatim.

**Prompt 3 (Claude):**
"Help me finish Milestone 3. Everything must be very simple because I am a
student and I must defend it."

Result: Claude simplified the validation code and tested the API on XAMPP.

**Prompt 4 (Claude):**
"Make sure there are no unnecessary comments in the code."

Result: Explanatory comments added by AI were removed from the Milestone 3 code.

### AI Mistakes and Corrections

**Mistake 1 – Irrelevant external API**

The first plan for the external API was a weather forecast.

How it was detected:
I noticed that a weather forecast has no real use in an application for booking
cleaning services.

Correction:
Codex was told not to implement the weather feature. OpenStreetMap Nominatim
address search was used instead, because a cleaner needs to know the address
of the cleaning location.

**Mistake 2 – HTTPS requests did not work on XAMPP**

Codex used the cURL option `CURLSSLOPT_NATIVE_CA` to trust HTTPS certificates
on Windows. My XAMPP uses PHP 8.0.30 with cURL 7.76.1, where this option does
not exist. Because of that, every real address search returned
"The address provider is unavailable." (HTTP 502).

How it was detected:
The live search for "Vijecnica, Sarajevo" was tested through the API on XAMPP.
A temporary debug message showed the real cURL error:
"SSL certificate problem: unable to get local issuer certificate".

Correction:
LocationService now uses the certificate file that comes with XAMPP
(`C:/xampp/apache/bin/curl-ca-bundle.crt`). After this change, the live search
returned five matches.

**Mistake 3 – Wrong guess while debugging**

Before the real error message was visible, Claude guessed that the problem was
a proxy setting and added `CURLOPT_PROXY`. This did not fix anything.

How it was detected:
The API still returned 502. The debug message then showed that the real
problem was the SSL certificate.

Correction:
The proxy option was removed and the certificate problem was fixed instead.

**Mistake 4 – Tests written for a newer PHP version**

Codex ran its tests with a newer PHP version. On my XAMPP (PHP 8.0) the
database returns numbers as text, for example "2" instead of 2. The test
compared values with `===`, so the CRUD test failed even though the update
worked. The timeout test also failed, because the test waited only 10 seconds,
the same time as the API timeout.

How it was detected:
The CRUD test stopped with "experience_years did not update", and the location
test returned status 0 instead of 504.

Correction:
The update check now uses `==`, and the test waits up to 20 seconds.

**Mistake 5 – Code too complex for my level**

Some Codex code was correct but hard to explain, for example long regular
expressions for numbers, dates and times, and file locking for the
one-request-per-second limit.

How it was detected:
While reviewing the code before the oral defense.

Correction:
The checks now use `filter_var()`, `is_numeric()` and
`DateTime::createFromFormat()`. The request limit uses a simple file with the
time of the last request. The same rules were tested again.

### External API Error Log

Failed Nominatim requests (timeout, provider error, invalid data) are written
to `backend/logs/external-api.log`. The user receives a short error message,
and the rest of the API keeps working.

### Verification

All tests below were run on my computer with XAMPP (PHP 8.0.30, MySQL on port
3307, database cleango_db):

- PHP lint passed for all backend PHP files.
- `php backend/tests/smoke.php http://127.0.0.1:8093 crud` passed. It creates,
  reads, updates and deletes records for all six entities and checks invalid
  input, duplicates, missing records and foreign-key conflicts. Test records
  are deleted at the end.
- `php backend/tests/smoke.php http://127.0.0.1:8095 locations` passed with the
  fake Nominatim server (match, empty list, 429, invalid data, provider error,
  timeout).
- The error log contained entries for invalid data, provider error and timeout.
- The live Nominatim search for "Vijecnica, Sarajevo" returned five matches.
- Swagger UI (/docs) and /openapi.json returned 200.
- Git does not track backend/config/config.php, vendor/ or log files.

