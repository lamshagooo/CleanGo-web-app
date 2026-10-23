
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
